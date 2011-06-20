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
    $("#CatEditForm").validate({
  		rules: {
			OvalueTitle: {
				required: true,
				minlength: 1
			},
			OvalueDesc: {
				required: true,
				minlength: 1
			},
				rules:"required"
			},
			messages: {
			OvalueTitle: {
				required: "Dieses Feld darf nicht leer sein",
				minlength: "Mindestes 1 Zeichen notwendig"
			},
			OvalueDesc: {
				required: "Dieses Feld darf nicht leer sein",
				minlength: "Mindestes 1 Zeichen notwendig"
			}
			}
	
  });
  
  });
  </script>
{/literal} 
<div class="edit_cats">


<form class="cmxform" id="CatEditForm" method="post" action="index.php?package=acp_news&amp;action=categories_save&id={$edit_id}">
	<fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">{#LN_NEWS_EDIT#}</legend>
		<p>
			<label for="package">{#LN_NEWS_TITEL_3#}</label>
			<input id="OvalueTitle" name="OvalueTitle" class="required ui-widget-content" minlength="2"  value="{$cat_titel}" >
		</p>
		<p>
			<label for="Okey">{#LN_NEWS_TITEL_6#}</label>
			<input id="OvalueDesc" name="OvalueDesc" class="required ui-widget-content" minlength="2"  value="{$cat_description}" >			
			
		</p>
		</p>
			<button class="submit" type="submit">{#LN_NEWS_SAVE#}</button>
		</p>
	</fieldset>
</form>