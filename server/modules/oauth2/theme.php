<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_MOD_OAUTH2' ) ) die( 'Stop!!!' );

/**
 * nv_main_theme()
 * 
 * @param mixed $array
 * @return
 */
function nv_main_theme( $array )
{
	global $module_file, $lang_module, $module_info;

	$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $array );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_app_theme()
 * 
 * @param mixed $array
 * @return
 */
function nv_app_theme( $array )
{
	global $module_file, $lang_module, $module_info;

	$xtpl = new XTemplate( 'app.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $array );

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_info_theme()
 * 
 * @param mixed $message
 * @param mixed $link
 * @param string $type
 * @return
 */
function nv_info_theme( $message, $link, $type = 'info' )
{
	global $module_file, $lang_module, $module_info, $global_config;

	$xtpl = new XTemplate( 'info.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'MESSAGE', $message );
	$xtpl->assign( 'LINK', $link );
	
	if( $type == 'error' )
	{
		$xtpl->parse( 'main.error' );
	}
	else
	{
		$xtpl->parse( 'main.info' );
	}

	$xtpl->assign( 'SITE_NAME', $global_config['site_name'] );
	$xtpl->assign( 'THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA );
	$size = @getimagesize( NV_ROOTDIR . '/' . $global_config['site_logo'] );
	$logo = preg_replace( '/\.[a-z]+$/i', '.svg', $global_config['site_logo'] );
	if( ! file_exists( NV_ROOTDIR . '/' . $logo ) )
	{
		$logo = $global_config['site_logo'];
	}
	$xtpl->assign( 'LOGO_SRC', NV_BASE_SITEURL . $logo );
	$xtpl->assign( 'LOGO_WIDTH', $size[0] );
	$xtpl->assign( 'LOGO_HEIGHT', $size[1] );

	if( isset( $size['mime'] ) and $size['mime'] == 'application/x-shockwave-flash' )
	{
		$xtpl->parse( 'main.swf' );
	}
	else
	{
		$xtpl->parse( 'main.image' );
	}
		
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

/**
 * nv_authorize_theme()
 * 
 * @param mixed $client_data
 * @param mixed $user_info
 * @return
 */
function nv_authorize_theme( $client_data, $user_info )
{
	global $module_file, $lang_module, $module_info, $global_config;

	$xtpl = new XTemplate( 'authorize.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	
	$client_data['client_title'] = empty( $client_data['client_title'] ) ? $client_data['client_id'] : $client_data['client_title'];
	
	$xtpl->assign( 'ROW', $client_data );
	$xtpl->assign( 'USERNAME', $user_info['full_name'] );
	$xtpl->assign( 'URL_LOGOUT', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . '=logout' );

	$xtpl->assign( 'SITE_NAME', $global_config['site_name'] );
	$xtpl->assign( 'THEME_SITE_HREF', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA );
	$size = @getimagesize( NV_ROOTDIR . '/' . $global_config['site_logo'] );
	$logo = preg_replace( '/\.[a-z]+$/i', '.svg', $global_config['site_logo'] );
	if( ! file_exists( NV_ROOTDIR . '/' . $logo ) )
	{
		$logo = $global_config['site_logo'];
	}
	$xtpl->assign( 'LOGO_SRC', NV_BASE_SITEURL . $logo );
	$xtpl->assign( 'LOGO_WIDTH', $size[0] );
	$xtpl->assign( 'LOGO_HEIGHT', $size[1] );

	if( isset( $size['mime'] ) and $size['mime'] == 'application/x-shockwave-flash' )
	{
		$xtpl->parse( 'main.swf' );
	}
	else
	{
		$xtpl->parse( 'main.image' );
	}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}