<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_MOD_OAUTH2', true );

/**
 * oauth2_getlang()
 * 
 * @param string $key
 * @param string $default
 * @return
 */
function oauth2_getlang( $key = '', $default = '' )
{
	global $lang_module;
	
	if( isset( $lang_module[$key] ) ) return $lang_module[$key];
	return $default ? $default : $key;
}