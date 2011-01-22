{include file=$HEADER}
<h2>{#LN_LOGIN_FORGET_NOTE1#}</h2>
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
			email: {
				required: true,
				email: true
			},
			messages: {
			email: "Bitte trage eine richtige Mailadresse ein"
			}
	
  });
  
  });
  </script>
{/literal} 

<br>
<form class="cmxform" id="registerForm" method="post" action="index.php?package=login&amp;action=forget_submit">
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">{#LN_LOGIN_FORGET_NOTE2#}</legend>
		<p>
			<label for="email">{#LN_LOGIN_EMAIL#}</label>
			<input id="email" name="email" class="required email ui-widget-content" />
		</p>
		<p>
			<button class="submit" type="submit">{#LN_LOGIN_MAIL_SENDEN#}</button>
		</p>
	</fieldset>
</form>


{include file=$FOOTER}