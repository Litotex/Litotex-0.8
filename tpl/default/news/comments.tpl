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
    $("#commentform").validate({
  		rules: {
			author: {
				required: true,
				minlength: 5
			},
			author_mail: {
				required: true,
				email: true
			},
				rules:"required"
			},
		messages: {
			author: {
				required: "Bitte trage hier einen Usernamen ein",
				minlength: "Der Username muss mindestes 5 Zeichen haben"
			},
			author_mail: "Bitte trage eine richtige Mailadresse ein"
			}
  });

  });
  </script>
{/literal} 

<h2>{$newsItem->getTitle()}</h2>
<div class="inhalt_box1">
<p>{$newsItem->getText()}</p>
<p align="right">{#LN_NEWS_FROM_AUTHOR#} {$newsItem->getAuthorName()} {#LN_NEWS_FROM_AUTHOR_DATE#} {$newsItem->getFormatedDate()}</p>
<h2>{#LN_NEWS_COMMENT_TITLE#}</h2>
<ul class="NewsCommentList">
	{foreach from=$comments item=comment}
		<li class="NewsComment">
			<div class="author">
				<div class="avatar">
					<img alt='image' src='{$comment->getImageURL()}' height='60' width='60' />			
				</div>
				<div class="name">
					{$comment->getAuthorName()}
				</div>
			</div>				
			<div class="messageBox">
				<div class="date">{$comment->getFormatedDate()}</div>
				<div class="content">			
						<p>{$comment->getTitle()}</p>
						<p>{$comment->getText()}</p>
				</div>			
			</div>
		</li>
	{/foreach}
</ul>

</div>
<form class="commentform" id="commentform" method="post" action="index.php?package=news&action=comment_submit&id={$news_id}">
{if $comment_guest == 1}
	<p>
	<textarea id="author" class="required ui-widget-content" style="width: 50%; height: 20px;" name="author"></textarea>
	<label for="author"><small>{#LN_NEWS_COMMENT_AUTHOR_NAME#}</small></label><br>
	</p>
	<p>
	<textarea id="author_mail" style="width: 50%; height: 20px;" name="author_mail"></textarea>
	<label for="author_mail"><small>{#LN_NEWS_COMMENT_AUTHOR_MAIL#}</small></label><br>
	</p>
	<p>
	<textarea id="author_web" style="width: 50%; height: 20px;" name="author_web"></textarea>
	<label for="author_web"><small>{#LN_NEWS_COMMENT_AUTHOR_WEB#}</small></label>
	</p>
{/if}

<textarea id="new_comment" name="new_comment" class="ui-autocomplete-input" style="width:95%; height: 120px;" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"></textarea>

<input id="button" type="submit" value="{#LN_NEWS_COMMENT_SEND#}" /></p>
</form>
{include file=$FOOTER}