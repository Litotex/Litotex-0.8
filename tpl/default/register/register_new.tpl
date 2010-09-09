{include file=$HEADER}
<h2>Registrierung</h2>
{literal} 
<style type="text/css">
label { display: inline-block; width: 100px; }
legend { padding: 0.5em; }
fieldset fieldset label { display: block; }
#registerForm { width: 100%; }
#registerForm label { width: 250px; }
#registerForm label.error, #registerForm button.submit { margin-left: 253px; }

</style>


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
			firstname: "required",
			lastname: "required",
			username: {
				required: true,
				minlength: 5
			},
			password: {
				required: true,
				minlength: 5
			},
			confirm_password: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			},
			email: {
				required: true,
				email: true
			},
				regeln:"required"
			},
			messages: {
			username: {
				required: "Bitte trage hier einen Usernamen ein",
				minlength: "Der Username muss mindestes 5 Zeichen haben"
			},
			password: {
				required: "Bitte trage dein Kennwort ein",
				minlength: "Das Kennwort muss 5 Zeichen haben"
			},
			confirm_password: {
				required: "Bitte trage erneut dein Kennwort ein",
				minlength: "Das Kennwort muss 5 Zeichen haben",
				equalTo: "Die Kennwörter sind unterschiedlich"
			},
			email: "Bitte trage eine richtige Mailadresse ein",
			regeln: "AGBs nicht zugestimmt"
			}
	
  });
  
  });
  </script>
{/literal} 

<br>
<form class="cmxform" id="registerForm" method="post" action="index.php?package=register&action=register_submit">
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">{#LN_NOTE_REGISTER_2#}</legend>
		<p>
			<label for="username">{#LN_NAME_USERNAME#}</label>
			<input id="username" name="username" class="required ui-widget-content" minlength="2" />
		<p>
		<p>
			<label for="password">{#LN_NAME_PASSWORD#}</label>
			<input id="password" name="password" class="required ui-widget-content" minlength="2" />
		<p>
		<p>
			<label for="confirm_password">{#LN_NAME_PASSWORD2#}</label>
			<input id="confirm_password" name="confirm_password" class="required ui-widget-content" minlength="2" />
		<p>
			<label for="email">{#LN_NAME_EMAIL#}</label>
			<input id="email" name="email" class="required email ui-widget-content" />
		</p>
		<p>
			<label for="regeln">{#LN_NOTE_REGISTER_3#}</label>
			<input id="regeln" name="regeln" type="checkbox" class="required ui-widget-content" />
		</p>
		<p>
			<button class="submit" type="submit">{#IN_REGISTER_P_6#}</button>
		</p>
	</fieldset>
</form>


{include file=$FOOTER}