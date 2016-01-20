<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 05/07/2010 09:47
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	'name' => 'Oauth 2',
	'modfuncs' => 'main,authorize,token,resource,app',
	'is_sysmod' => 0,
	'virtual' => 0,
	'version' => '4.0.25',
	'date' => 'Wed, 20 Oct 2015 00:00:00 GMT',
	'author' => 'PHAN TAN DUNG (phantandung92@gmail.com)',
	'note' => '',
	'uploads_dir' => array(
		$module_upload
	)
);