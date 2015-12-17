<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>{LANG.content_title}</th>
				<th>{LANG.content_id}</th>
				<th>{LANG.content_secret}</th>
				<th class="w150 text-center">{LANG.function}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{ROW.client_title}</td>
				<td>{ROW.client_id}</td>
				<td>{ROW.client_secret}</td>
				<td class="text-center">
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_row('{ROW.client_id}');">{GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
		<!-- BEGIN: generate_page -->
		<tfoot>
			<tr>
				<td colspan="4">
					{GENERATE_PAGE}
				</td>
			</tr>
		</tfoot>
		<!-- END: generate_page -->
	</table>
</div>
<!-- END: main -->