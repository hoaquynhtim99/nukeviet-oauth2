<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

// RewriteRule ^oauth2/(resource|token|authorize)$ index.php?nv=oauth2&op=$1&%{QUERY_STRING} [L]

global $db;

$deletetables = true;
$sql_drop_module = array();

$langsite = $db->query( 'SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup = 1' )->fetchAll( PDO::FETCH_COLUMN );
if( sizeof( $langsite ) > 1 )
{
	foreach( $langsite as $_lang )
	{
		if( $_lang != $lang )
		{
	 		$result = $db->query( 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $_lang . '_modules WHERE module_data = ' . $db->quote( $module_data ) );
			
			if( $result->fetchColumn() )
			{
				$deletetables = false;
				break;
			}
		}
	}
}

if( $deletetables === true )
{
	$array_table = array(
		'clients',
		'access_tokens',
		'authorization_codes',
		'refresh_tokens',
		'users',
		'scopes',
		'jwt',
		'jti',
		'public_keys'
	);
	$table = $db_config['prefix'] . '_' . $module_data;
	$result = $db->query( 'SHOW TABLE STATUS LIKE ' . $db->quote( $table . '_%' ) );
	while( $item = $result->fetch( ) )
	{
		$name = substr( $item['name'], strlen( $table ) + 1 );
		if( preg_match( '/^' . $db_config['prefix'] . '\_' . $module_data . '\_/', $item['name'] ) and ( preg_match( '/^([0-9]+)$/', $name ) or in_array( $name, $array_table ) ) )
		{
			$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $item['name'];
		}
	}
}

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_clients (
  client_id             VARCHAR(80)   NOT NULL,
  client_title          VARCHAR(80)   NOT NULL DEFAULT '',
  client_secret         VARCHAR(80)   NOT NULL,
  redirect_uri          VARCHAR(2000),
  grant_types           VARCHAR(80),
  scope                 VARCHAR(4000),
  user_id               VARCHAR(80),
  addtime				INT(11) unsigned NOT NULL DEFAULT '0',
  updatetime			INT(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (client_id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_access_tokens (
  access_token         VARCHAR(40)    NOT NULL,
  client_id            VARCHAR(80)    NOT NULL,
  user_id              VARCHAR(80),
  expires              TIMESTAMP      NOT NULL,
  scope                VARCHAR(4000),
  PRIMARY KEY (access_token)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_authorization_codes (
  authorization_code  VARCHAR(40)    NOT NULL,
  client_id           VARCHAR(80)    NOT NULL,
  user_id             VARCHAR(80),
  redirect_uri        VARCHAR(2000),
  expires             TIMESTAMP      NOT NULL,
  scope               VARCHAR(4000),
  id_token            VARCHAR(1000),
  PRIMARY KEY (authorization_code)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_refresh_tokens (
  refresh_token       VARCHAR(40)    NOT NULL,
  client_id           VARCHAR(80)    NOT NULL,
  user_id             VARCHAR(80),
  expires             TIMESTAMP      NOT NULL,
  scope               VARCHAR(4000),
  PRIMARY KEY (refresh_token)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_users (
  username            VARCHAR(80),
  password            VARCHAR(80),
  first_name          VARCHAR(80),
  last_name           VARCHAR(80),
  email               VARCHAR(80),
  email_verified      BOOLEAN,
  scope               VARCHAR(4000)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_scopes (
  scope               VARCHAR(80)  NOT NULL,
  is_default          BOOLEAN,
  PRIMARY KEY (scope)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_jwt (
  client_id           VARCHAR(80)   NOT NULL,
  subject             VARCHAR(80),
  public_key          VARCHAR(2000) NOT NULL
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_jti (
  issuer              VARCHAR(80)   NOT NULL,
  subject             VARCHAR(80),
  audiance            VARCHAR(80),
  expires             TIMESTAMP     NOT NULL,
  jti                 VARCHAR(2000) NOT NULL
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $module_data . "_public_keys (
  client_id            VARCHAR(80),
  public_key           VARCHAR(2000),
  private_key          VARCHAR(2000),
  encryption_algorithm VARCHAR(100) DEFAULT 'RS256'
) ENGINE=MyISAM";