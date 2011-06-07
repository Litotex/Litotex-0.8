{include file=$HEADER}
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
    $("#registerForm").validate({
  		rules: {
			password: {
				required: false,
				minlength: 5
			},
			confirm_password: {
				required: false,
				minlength: 5,
				equalTo: "#password"
			},
			email: {
				required: false,
				email: true
			},
				rules:"required"
			},
			messages: {
			password: {
				required: "Bitte trage dein Kennwort ein",
				minlength: "Das Kennwort muss 5 Zeichen haben"
			},
			confirm_password: {
				required: "Bitte trage erneut dein Kennwort ein",
				minlength: "Das Kennwort muss 5 Zeichen haben",
				equalTo: "Die Kennw&ouml;rter sind unterschiedlich"
			},
			email: "Bitte trage eine richtige Mailadresse ein"
			
			}
	
  });
  
  });
  </script>
{/literal} 

<br>
<form class="cmxform" id="registerForm" method="post" action="index.php?package=edit_profile&amp;action=profile_submit">
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">{#LN_EDIT_PROFILE_CAPTION#}</legend>
		<p>
			<label for="password">{#LN_EDIT_PASSWORD#}</label>
			<input id="password" name="password" class="required ui-widget-content" minlength="2" >
		<p>
		<p>
			<label for="confirm_password">{#LN_EDIT_PASSWORD_2#}</label>
			<input id="confirm_password" name="confirm_password" class="required ui-widget-content" minlength="2" >
		<p>
			<label for="email">{#LN_EDIT_EMAIL#}</label>
			<input id="email" name="email" class="required email ui-widget-content" value="{$EMAIL}"/>
		</p>
		<p>
			<button class="submit" type="submit">{#LN_EDIT_PROFILE_SAVE_BUTTON#}</button>
		</p>
	</fieldset>
</form>


{include file=$FOOTER}