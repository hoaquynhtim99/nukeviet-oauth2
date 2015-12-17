<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form method="post" action="{FORM_ACTION}">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col class="w200"/>
			<tbody>
				<tr>
					<td class="text-right text-strong">{LANG.content_title}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span></td>
					<td>
						<input type="text" name="client_title" value="{DATA.client_title}" class="form-control w500" maxlength="80">
					</td>
				</tr>
				<tr>
					<td class="text-right text-strong">{LANG.content_id}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span></td>
					<td>
						<div class="input-group w500">
							<input{READONLY} type="text" id="idclientid" name="client_id" value="{DATA.client_id}" class="form-control" maxlength="15" placeholder="{LANG.content_id_note}">
							<span class="input-group-btn">
								<button{DISABLED} class="btn btn-default" type="button" onclick="gen_client_id(this);"><i class="fa fa-recycle"></i></button>
							</span>
						</div>
					</td>
				</tr>
				<tr>
					<td class="text-right text-strong">{LANG.content_secret}<span class="fa-required text-danger">(<em class="fa fa-asterisk"></em>)</span></td>
					<td>
						<div class="input-group w500">
							<input type="text" id="idclientsecret" name="client_secret" value="{DATA.client_secret}" class="form-control" maxlength="32" placeholder="{LANG.content_secret_note}">
							<span class="input-group-btn">
								<button class="btn btn-default" type="button" onclick="gen_client_secret(this);"><i class="fa fa-recycle"></i></button>
							</span>
						</div>
					</td>
				</tr>
				<tr>
					<td class="text-right text-strong">{LANG.content_redirect_uri}</td>
					<td>
						<input type="text" name="redirect_uri" value="{DATA.redirect_uri}" class="form-control w500 m-bottom" maxlength="2000" placeholder="{LANG.content_redirect_uri_note}">
						<em>{LANG.content_redirect_uri_note1}</em>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2">
						<input type="submit" name="submit" value="{GLANG.submit}" class="btn btn-primary">
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<!-- END: main -->