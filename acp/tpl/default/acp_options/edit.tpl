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
    $("#OptionEditForm").validate({
  		rules: {
			package: {
				required: true,
				minlength: 3
			},
			Okey: {
				required: true,
				minlength: 3
			},
			Ovalue: {
				required: true,
				minlength: 1
			},
			Odefault: {
				required: true,
				minlength: 1
			},
				rules:"required"
			},
			messages: {
			package: {
				required: "Dieses Feld darf nicht leer sein.",
				minlength: "Mindestes 3 Zeichen notwendig"
			},
			Okey: {
				required: "Dieses Feld darf nicht leer sein",
				minlength: "Mindestes 3 Zeichen notwendig"
			},
			Ovalue: {
				required: "Dieses Feld darf nicht leer sein",
				minlength: "Mindestes 1 Zeichen notwendig"
			},
			Odefault: {
				required: "Dieses Feld darf nicht leer sein",
				minlength: "Mindestes 1 Zeichen notwendig"
			}
			}
	
  });
  
  });
  </script>
{/literal} 

<br>
<form class="cmxform" id="OptionEditForm" method="post" action="index.php?package=acp_options&amp;action=save&id={$edit_id}">
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">{#LN_OPTION_EDIT#}</legend>
		<p>
			<label for="package">{#LN_OPTION_TITEL_1#}</label>
			<input id="package" name="package" class="required ui-widget-content" minlength="2" value="{$Option_package}" >
		</p>
		<p>
			<label for="Okey">{#LN_OPTION_TITEL_2#}</label>
			<input id="Okey" name="Okey" class="required ui-widget-content" minlength="2"  value="{$Option_key}" >
		</p>
		<p>
			<label for="Ovalue">{#LN_OPTION_TITEL_3#}</label>
			<input id="Ovalue" name="Ovalue" class="required ui-widget-content" minlength="2"  value="{$Option_value}" >
		</p>
		<p>
			<label for="Odefault">{#LN_OPTION_TITEL_4#}</label>
			<input id="Odefault" name="Odefault" class="required ui-widget-content"  value="{$Option_default}"/>
		</p>
		
		</p>
			<button class="submit" type="submit">{#LN_OPTION_SAVE#}</button>
		</p>
	</fieldset>
</form>


