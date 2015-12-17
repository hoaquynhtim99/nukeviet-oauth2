<!-- BEGIN: main -->
<div class="centered">
	<div class="authorize-box">
		<div class="text-center margin-top-50 margin-bottom-lg">
			<!-- BEGIN: image -->
			<a title="{SITE_NAME}" href="{THEME_SITE_HREF}"><img src="{LOGO_SRC}" width="{LOGO_WIDTH}" height="{LOGO_HEIGHT}" alt="{SITE_NAME}" /></a>
			<!-- END: image -->
			<!-- BEGIN: swf -->
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="{LOGO_WIDTH}" height="{LOGO_HEIGHT}" >
				<param name="wmode" value="transparent" />
				<param name="movie" value="{LOGO_SRC}" />
				<param name="quality" value="high" />
				<param name="menu" value="false" />
				<param name="seamlesstabbing" value="false" />
				<param name="allowscriptaccess" value="samedomain" />
				<param name="loop" value="true" />
				<!--[if !IE]> <-->
				<object type="application/x-shockwave-flash" width="{LOGO_WIDTH}" height="{LOGO_HEIGHT}" data="{LOGO_SRC}" >
					<param name="wmode" value="transparent" />
					<param name="pluginurl" value="http://www.adobe.com/go/getflashplayer" />
					<param name="loop" value="true" />
					<param name="quality" value="high" />
					<param name="menu" value="false" />
					<param name="seamlesstabbing" value="false" />
					<param name="allowscriptaccess" value="samedomain" />
				</object>
				<!--> <![endif]-->
			</object>
			<!-- END: swf -->
		</div>
		<div class="page panel panel-default margin-top-lg box-shadow bg-white">
			<div class="panel-body">
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
							<a href="{URL_LOGOUT}">{LANG.oauth_logout}</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- END: main -->