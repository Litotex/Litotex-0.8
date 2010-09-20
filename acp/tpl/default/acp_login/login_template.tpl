{literal} 
<script>
$.validator.setDefaults({
	submitHandler: function() { form.submit() },
	highlight: function(input) {
		$(input).addClass("ui-state-highlight");
	},
	unhighlight: function(input) {
		$(input).removeClass("ui-state-highlight");
	}
});
  $(document).ready(function(){
    $("#loginForm").validate({
  		rules: {
			username: {
				required: true,
			},
			password: {
				required: true,
			},},
			messages: {
			username: "Bitte trage deinen Usernamen ein",
			password: "Bitte trage das Kennwort ein"
			}
  });
  });
  </script>
{/literal} 


<div class="login">
		<form class="cmxform" id="loginForm" method="post" action="index.php?package=acp_login&amp;action=loginsubmit">
			<fieldset class="ui-widget ui-widget-content ui-corner-all">
				<legend class="ui-widget ui-widget-header ui-corner-all">{#LN_LOGIN_ACP#}</legend>
				<p>
					<label for="username">{#LN_LOGIN_USERNAME#}</label>
					<input id="username" name="username" class="required ui-widget-content" />
				</p>
				<p>
					<label for="password">{#LN_LOGIN_PASSWORD#}</label>
					<input id="password" type="password" name="password" class="required ui-widget-content" />
				</p>
				<p>
					<button class="submit" type="submit">{#LN_LOGIN_LINKNAME#}</button>
				</p>
				<p>
				<div class="forget"><a href="index.php?package=acp_login&amp;action=forget">{#LN_LOGIN_FORGET_LINKNAME#}</a></div> 
				</p>
			</fieldset>
		</form>
</div>