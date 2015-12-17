/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC ( contact@vinades.vn )
 * @Copyright ( C ) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

function gen_client_id(a){
	$(a).prop('disabled', 1);
	$.post(
		script_name + '?' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 
		'genclientid=1', function(res) {
			$(a).prop('disabled', 0);
			document.getElementById('idclientid').value = res;
		}
	);
}

function gen_client_secret(a){
	$(a).prop('disabled', 1);
	$.post(
		script_name + '?' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(), 
		'genclientsecret=1', function(res) {
			$(a).prop('disabled', 0);
			document.getElementById('idclientsecret').value = res;
		}
	);
}

function nv_del_row(client_id) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(
			script_name + '?' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
			'delete=1&client_id=' + client_id, function(res) {
			var r_split = res.split("_");
			if (r_split[0] == 'OK') {
				window.location.href = window.location.href;
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
}