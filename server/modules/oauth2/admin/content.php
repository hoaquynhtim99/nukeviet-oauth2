<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 - 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$client_id = $nv_Request->get_title( 'client_id', 'get', '' );
$error = '';

if( ! empty( $client_id ) )
{
	$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_clients WHERE client_id = :client_id';
	$sth = $db->prepare( $sql );
	$sth->bindParam( ':client_id', $client_id, PDO::PARAM_STR );
	$sth->execute();
	$array = $sth->fetch();
	
	if( empty( $array ) )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
	
	$page_title = $lang_module['edit'];
	$form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;client_id=' . $client_id;
}
else
{
	$array = array(
		'client_id' => '',
		'client_title' => '',
		'client_secret' => '',
		'redirect_uri' => ''
	);
	
	$page_title = $lang_module['add'];
	$form_action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['client_id'] = nv_substr( $nv_Request->get_title( 'client_id', 'post', '', true ), 0, 80 );
	$array['client_title'] = nv_substr( $nv_Request->get_title( 'client_title', 'post', '', true ), 0, 80 );
	$array['client_secret'] = nv_substr( $nv_Request->get_title( 'client_secret', 'post', '', true ), 0, 80 );
	$array['redirect_uri'] = nv_substr( $nv_Request->get_title( 'redirect_uri', 'post', '', false ), 0, 2000 );
	
	if( empty( $array['client_id'] ) )
	{
		$error = $lang_module['content_error_id'];
	}
	elseif( empty( $array['client_title'] ) )
	{
		$error = $lang_module['content_error_title'];
	}
	elseif( empty( $array['client_secret'] ) )
	{
		$error = $lang_module['content_error_secret'];
	}
	else
	{
		$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_clients WHERE client_id = :client_id' . ( $client_id ? ' AND client_id != ' . $db->quote( $client_id ) : '' );
		$sth = $db->prepare( $sql );
		$sth->bindParam( ':client_id', $array['client_id'], PDO::PARAM_STR );
		$sth->execute();
		$num = $sth->fetchColumn();
		
		if( ! empty( $num ) )
		{
			$error = $lang_module['content_error_exists'];
		}
		else
		{
			if( ! $client_id )
			{
				$sql = 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_clients (
					client_id, client_title, client_secret, redirect_uri, grant_types, scope, user_id, addtime, updatetime 
				) VALUES (
					' . $db->quote( $array['client_id'] ) . ', :client_title, :client_secret, :redirect_uri, NULL, NULL, ' . $db->quote( $admin_info['userid'] ) . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ' 
				)';
			}
			else
			{
				$sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_clients SET 
					client_title = :client_title, client_secret = :client_secret, redirect_uri = :redirect_uri, updatetime = ' . NV_CURRENTTIME . '
				WHERE client_id = ' . $db->quote( $client_id );
			}

			try
			{
				$down_groups = implode( ',', $array['down_groups'] );
				
				$sth = $db->prepare( $sql );
				$sth->bindParam( ':client_title', $array['client_title'], PDO::PARAM_STR );
				$sth->bindParam( ':client_secret', $array['client_secret'], PDO::PARAM_STR );
				$sth->bindParam( ':redirect_uri', $array['redirect_uri'], PDO::PARAM_STR );
				$sth->execute();
				
				if( $sth->rowCount() )
				{
					if( $client_id )
					{
						nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit', 'ID: ' . $client_id, $admin_info['userid'] );
					}
					else
					{
						nv_insert_logs( NV_LANG_DATA, $module_name, 'Add', 'ID: ' . $array['client_id'], $admin_info['userid'] );
					}
	
					$nv_Cache->delMod( $module_name );
					Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
					die();
				}
				else
				{
					$error = $lang_module['errorsave'];
				}
			}
			catch( PDOException $e )
			{
				$error = $lang_module['errorsave'];
			}
		}
	}
}

$xtpl = new XTemplate( 'content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'FORM_ACTION', $form_action );
$xtpl->assign( 'DATA', $array );
$xtpl->assign( 'UPLOADS_DIR', NV_UPLOADS_DIR . '/' . $module_upload . '/files' );

$xtpl->assign( 'READONLY', empty( $client_id ) ? '' : ' readonly="readonly"' );
$xtpl->assign( 'DISABLED', empty( $client_id ) ? '' : ' disabled="disabled"' );

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';