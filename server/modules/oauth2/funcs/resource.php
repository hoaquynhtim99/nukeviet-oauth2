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

$request = OAuth2\Request::createFromGlobals();

// Handle a request to a resource and authenticate the access token
if( ! $server->verifyResourceRequest( $request ) )
{
	$server->getResponse()->send();
	die;
}

$token = $server->getAccessTokenData( $request );
$result = array();

if( $token['user_id'] )
{
	$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . intval( $token['user_id'] ) . ' AND active =1';
	$user = $db->query( $sql )->fetch();
	
	if( ! empty( $user ) )
	{
		$result = array(
			'id' => md5( $global_config['sitekey'] . $user['userid'] ),
			'link' => preg_replace( "/^" . nv_preg_quote( NV_BASE_SITEURL ) . "([a-z]{2})\//", NV_BASE_SITEURL, nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '/'. md5( $global_config['sitekey'] . $user['userid'] ), true ) ),
			'email' => $user['email'],
			'first_name' => $user['first_name'],
			'last_name' => $user['last_name'],
			'name' => $user['username'],
			'gender' => $user['gender']
		);
	}
	
	unset( $user );
	
	$db->query( 'DELETE FROM ' . $dbtable_config['access_token_table'] . ' WHERE user_id = ' . intval( $token['user_id'] ) );
}

echo json_encode( $result );
die();