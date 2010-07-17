{include file=$HEADER}

          <div class="inhalt_box1">
{php} package::$packages->callHook('showNewsBlock', array(2)) {/php}
         </div>
{include file=$FOOTER}