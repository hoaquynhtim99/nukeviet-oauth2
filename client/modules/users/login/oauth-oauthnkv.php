<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 26 Oct 2014 08:34:25 GMT
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

use OAuth\OAuth2\Service\NukeViet;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

// Session storage
$storage = new Session();

$serviceFactory = new \OAuth\ServiceFactory();

// Setup the credentials for the requests
$credentials = new Credentials( '145770550207935', 'gqzwvrhc9oqqkvyzeqrk1tiph3ldqhn3', NV_MAIN_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=oauth&server=oauthnkv' );

// Instantiate the NukeViet service using the credentials, http client and storage mechanism for the token
$NukeVietService = $serviceFactory->createService( 'nukeviet', $credentials, $storage, array(), NULL );

// Error
if( $nv_Request->isset_request( 'error', 'get' ) )
{
	$error = $nv_Request->get_title( 'error', 'get', '' );
	$error_description = $nv_Request->get_title( 'error_description', 'get', '' );
	
	if( ! empty( $error_description ) )
	{
		$error .= ': ' . $error_description;
	}
	
	$nv_redirect = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true );
	user_info_exit_redirect( $error, $nv_redirect );
}

if( ! empty( $_GET['code'] ) )
{
	// This was a callback request from NukeViet, get the token
	$token = $NukeVietService->requestAccessToken( $_GET['code'] );
	
	// Send a request with it
	$result = json_decode( $NukeVietService->request('/'), true );

	if( isset( $result['email'] ) )
	{
		$attribs = array(
			'identity' => $result['link'],
			'result' => 'is_res',
			'id' => $result['id'],
			'contact/email' => $result['email'],
			'namePerson/first' => $result['first_name'],
			'namePerson/last' => $result['last_name'],
			'namePerson' => $result['name'],
			'person/gender' => $result['gender'],
			'server' => $server,
			'current_mode' => 3
		);
	}
	else
	{
		$attribs = array( 'result' => 'notlogin' );
	}
	
	$nv_Request->set_Session( 'openid_attribs', serialize( $attribs ) );

	$op_redirect = ( defined( 'NV_IS_USER' )) ? 'editinfo/openid' : 'login';
    $nv_redirect = nv_get_redirect();
    if( !empty( $nv_redirect ) ) $nv_redirect = '&nv_redirect=' . $nv_redirect;
	Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op_redirect . '&server=' . $server . '&result=1' . $nv_redirect );
	exit();
}
else
{
	$url = $NukeVietService->getAuthorizationUri();
	Header( 'Location: ' . $url );
	exit();
}