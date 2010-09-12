{include file=$HEADER}

          <div class="inhalt_box1">
{php} package::$packages->callHook('test', array()) {/php}
         </div>
{include file=$FOOTER}