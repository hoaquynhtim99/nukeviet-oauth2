<!-- BEGIN: main -->
<form role="form" method="post">
	<div class="form-group clearfix">
		{LANG.oauth_hello} <strong>{USERNAME}</strong>!<br /><strong>{ROW.client_title}</strong> {LANG.oauth_message}.
	</div>
	<div class="form-group clearfix">
		<div class="pull-right">
			<input type="hidden" name="authorized" value="1"/>
			<input type="submit" name="authorizedyes" value="{LANG.oauth_yes}" class="btn btn-success"/>
			&nbsp;
			<input type="submit" name="authorizedno" value="{LANG.oauth_no}" class="btn btn-danger"/>
		</div>
	</div>
	<div class="form-group clearfix">
		<div class="pull-right">
			<a class="btn btn-link" href="{URL_LOGOUT}">{LANG.oauth_logout}</a>
		</div>
	</div>
</form>
<!-- END: main -->