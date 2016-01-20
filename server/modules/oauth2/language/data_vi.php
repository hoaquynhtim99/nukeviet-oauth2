<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if (! defined('NV_ADMIN')) {
    die('Stop!!!');
}

/**
 * Note:
 * 	- Module var is: $lang, $module_file, $module_data, $module_upload, $module_theme, $module_name
 * 	- Accept global var: $db, $db_config, $global_config
 */

try {
	$sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_clients
	(client_id, client_title, client_secret, redirect_uri, grant_types, scope, user_id, addtime, updatetime) VALUES 
	('145770550207935', 'Test App', 'gqzwvrhc9oqqkvyzeqrk1tiph3ldqhn3', '', NULL, NULL, '1', " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")");
	$sth->execute();
} catch(PDOException $e) {
}