<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_MOD_OAUTH2' ) ) die( 'Stop!!!' );

// Autoloading (composer is preferred, but for this example let's just do this)
require_once ( NV_ROOTDIR . '/modules/' . $module_file . '/OAuth2/Autoloader.php' );
OAuth2\Autoloader::register();

$dbtable_config  = array(
	'client_table' => $db_config['prefix'] . '_' . $module_data . '_clients',
	'access_token_table' => $db_config['prefix'] . '_' . $module_data . '_access_tokens',
	'refresh_token_table' => $db_config['prefix'] . '_' . $module_data . '_refresh_tokens',
	'code_table' => $db_config['prefix'] . '_' . $module_data . '_authorization_codes',
	'user_table' => $db_config['prefix'] . '_' . $module_data . '_users',
	'jwt_table'  => $db_config['prefix'] . '_' . $module_data . '_jwt',
	'jti_table'  => $db_config['prefix'] . '_' . $module_data . '_jti',
	'scope_table'  => $db_config['prefix'] . '_' . $module_data . '_scopes',
	'public_key_table'  => $db_config['prefix'] . '_' . $module_data . '_public_keys',
);

// Use $db of NukeViet (NukeViet 4 using PDO) - Quá đẹp :)
$storage = new OAuth2\Storage\Pdo( $db, $dbtable_config );

// Pass a storage object or array of storage objects to the OAuth2 server class
$server = new OAuth2\Server( $storage );

// Add the "Authorization Code" grant type (this is where the oauth magic happens)
$server->addGrantType( new OAuth2\GrantType\AuthorizationCode( $storage ) );