<form action="index.php?package=login&amp;action=loginsubmit" name="frm_login" method="post">
    <div class="rbroundbox">
        <div class="rbtop">
            <div></div>
        </div>
        <div class="rbcontent">
            <h2>Login </h2>
            {#LN_LOGIN_USERNAME#}<br/><input class="textinput" name="username" type="text" value="" maxlength="255"/><br/>
            {#LN_LOGIN_PASSWORD#}<br/><input class="textinput" name="password" type="password" value="" maxlength="255"/><br/>
            <input type="submit" value="{#LN_LOGIN_LINKNAME#}"/><br/>
            <a href="index.php?package=login&amp;action=forget">{#LN_LOGIN_FORGET_LINKNAME#}</a>
        </div>
        <div class="rbbot">
            <div></div>
        </div>
    </div>
</form>