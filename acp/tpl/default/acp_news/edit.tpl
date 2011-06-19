<form action="index.php?package=acp_news&action=save&id={$News_ID}" method="post">
		<div>
			<p>Titel:</p>
			<textarea id="news_over" name="news_over" style="width: 100%; height: 20px;">{$News_Title}</textarea>
		</div>
	<div>
		<p>Inhalt:</p>
		<textarea cols="80" id="news_text" name="news_text" rows="10">{$News_Text}</textarea>
	</div>	
	{literal} 
	<script type="text/javascript">
				
				CKEDITOR.replace( 'news_text',
					{
						
						fullPage : false,
						enterMode : CKEDITOR.ENTER_DIV,

						filebrowserBrowseUrl : '{/literal}{$FileBrowser}{literal}index.php?editor=ckeditor',			
						filebrowserImageBrowseUrl : '{/literal}{$FileBrowser}{literal}index.php?editor=ckeditor&filter=image',
						filebrowserFlashBrowseUrl : '{/literal}{$FileBrowser}{literal}index.php?editor=ckeditor&filter=flash'

						
					});

			</script>
	{/literal}			
</form>