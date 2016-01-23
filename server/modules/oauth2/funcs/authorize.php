<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_MOD_OAUTH2' ) ) die( 'Stop!!!' );

// Include our OAuth2 Server object
require_once NV_ROOTDIR . '/modules/' . $module_file . '/server.php';

// Cái này không phải Request nào cũng có
$_GET['state'] = mt_rand(0, 999);

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();

// Validate the authorize request
if( ! $server->validateAuthorizeRequest( $request, $response ) )
{
	$parameters = $response->getParameters();
	$httpheaders = $response->getHttpHeaders();
	
	// Báo lỗi ở đây, không trả về trình duyệt, chưa thấy trường nào nào cần thiết phải trả về trình duyệt ở đây
	//if( ! isset( $httpheaders['Location'] ) and ! isset( $httpheaders['location'] ) )
	//{
		$contents = nv_info_theme( oauth2_getlang( $parameters['error_description'], $parameters['error_description'] ), 'javascript:void(0);', $type = 'error' );
		
		include NV_ROOTDIR . '/includes/header.php';
		echo nv_site_theme( $contents, false );
		include NV_ROOTDIR . '/includes/footer.php';
	//}
	
	$response->send();
	die;
}

// Thành viên đăng nhập
if( ! defined( 'NV_IS_USER' ) )
{
	header( 'Location:' . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login/simple&nv_redirect=" . nv_redirect_encrypt( $client_info['selfurl'] ) . '&nv_header=' . md5( $client_info['session_id'] . $global_config['sitekey'] ) );
	die();
}

// Chỗ này cần xác định SCOPES nữa, sẽ phát triển ở phiên bản kế tiếp
// Lấy client data
$client_id = $request->query( 'client_id', $request->request( 'client_id' ) );
$client_data = $storage->getClientDetails( $client_id );

// Truy vấn vào CSDL xem đã có authorization code còn hạn không, nếu có trả về nếu không có phải hỏi thành viên có đồng ý không
$sql = 'SELECT * FROM ' . $dbtable_config['code_table'] . ' WHERE client_id = :client_id AND user_id = :user_id';
$sth = $db->prepare( $sql );
$sth->bindParam( ':client_id', $client_id, PDO::PARAM_STR );
$sth->bindParam( ':user_id', $user_info['userid'], PDO::PARAM_STR );
$sth->execute();
$authorization = $sth->fetch();

if( ! empty( $authorization ) )
{
	$authorization['expires'] = strtotime( $authorization['expires'] );
	
	if( $authorization['expires'] <= NV_CURRENTTIME )
	{
		$storage->expireAuthorizationCode( $authorization['authorization_code'] );
	}
	else
	{
		// Authorization code còn hạn thì trả về kết quả, không cần hỏi lại
		$authorizecontroller = $server->getAuthorizeController();
		
		$params = array(
			'scope' => $authorizecontroller->getScope(),
			'state' => $authorizecontroller->getState(),
			'client_id' => $authorizecontroller->getClientId(),
			'redirect_uri' => $authorizecontroller->getRedirectUri() ? $authorizecontroller->getRedirectUri() : $client_data['redirect_uri'],
			'response_type' => $authorizecontroller->getResponseType(),
		);
		
		$result = array( 'query' => array() );
		$result['query']['code'] = $authorization['authorization_code'];
		$result['query']['state'] = $params['state'];
		
		$uri = $authorizecontroller->buildUri( $params['redirect_uri'], $result );
		$response->setRedirect( $authorizecontroller->getConfig( 'redirect_status_code' ), $uri );
		
		header( 'Location: ' . $response->getHttpHeader( 'Location' ) );
		
		$response->send();
		die();
	}
}

// Display an authorization form
if( ! $nv_Request->isset_request( 'authorized', 'post' ) )
{
	$contents = nv_authorize_theme( $client_data, $user_info );
	
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_site_theme( $contents, false );
	include NV_ROOTDIR . '/includes/footer.php';
}

// Print the authorization code if the user has authorized your client
$is_authorized = $nv_Request->isset_request( 'authorizedyes', 'post' );
$server->handleAuthorizeRequest( $request, $response, $is_authorized, $user_info['userid'] );

if( $is_authorized )
{
	header( 'Location: ' . $response->getHttpHeader( 'Location' ) );
}

$response->send();
die();