<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

// Creat Client ID
if( $nv_Request->isset_request( 'genclientid', 'post' ) )
{
	$client_id = '';
	while( 1 )
	{
		$client_id = mt_rand( 100, 999 ) . mt_rand( 100, 999 ) . mt_rand( 100, 999 ) . mt_rand( 100, 999 ) . mt_rand( 100, 999 );
		
		$sql = 'SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_clients WHERE client_id = :client_id';
		$stmt = $db->prepare( $sql );
		$stmt->bindParam( ':client_id', $client_id, PDO::PARAM_STR );
		$stmt->execute();	
		if( ! $stmt->fetchColumn() ) break;
	}
	
	include NV_ROOTDIR . '/includes/header.php';
	echo $client_id;
	include NV_ROOTDIR . '/includes/footer.php';
}

// Creat Client Secret
if ( $nv_Request->isset_request( 'genclientsecret', 'post' ) )
{
	include NV_ROOTDIR . '/includes/header.php';
	echo strtolower( nv_genpass( 32 ) );
	include NV_ROOTDIR . '/includes/footer.php';
}

// Delete Client
if( $nv_Request->isset_request( 'delete', 'post' ) )
{
	$client_id = $nv_Request->get_title( 'client_id', 'post', '' );
	
	$sql = 'SELECT client_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_clients WHERE client_id = :client_id';
	$sth = $db->prepare( $sql );
	$sth->bindParam( ':client_id', $client_id, PDO::PARAM_STR );
	$sth->execute();
	
	$client_id = $sth->fetchColumn();
	
	if( empty( $client_id ) ) die( 'NO_' . $client_id );
	
	$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_clients WHERE client_id = ' . $db->quote( $client_id ) );
	$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_access_tokens WHERE client_id = ' . $db->quote( $client_id ) );
	$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_authorization_codes WHERE client_id = ' . $db->quote( $client_id ) );
	$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_jwt WHERE client_id = ' . $db->quote( $client_id ) );
	$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_public_keys WHERE client_id = ' . $db->quote( $client_id ) );
	$db->query( 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_refresh_tokens WHERE client_id = ' . $db->quote( $client_id ) );
	
	nv_del_moduleCache( $module_name );
	
	include NV_ROOTDIR . '/includes/header.php';
	echo 'OK_' . $client_id;
	include NV_ROOTDIR . '/includes/footer.php';
}

$page_title = $lang_module['list'];
$per_page = $nv_Request->get_int( 'per_page', 'get', 20 );
$page = $nv_Request->get_int( 'page', 'get', 1 );

$db->sqlreset()->select( 'COUNT(*)' )->from( $db_config['prefix'] . '_' . $module_data . '_clients' );

$num_items = $db->query( $db->sql() )->fetchColumn();

$db->select( '*' )/*->order( 'id DESC' )*/->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
$result = $db->query( $db->sql() );

$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

while( $row = $result->fetch() )
{
	$row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;client_id=' . $row['client_id'];
	
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.loop' );
}

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';