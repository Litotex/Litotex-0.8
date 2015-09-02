# Litotex-Developer Preview Version

This version is for developers who know what they are doing
We don't assume liability of any kind
For help, support or just talking, you can

* visit our website at [litotex.net](http://litotex.net/) (currently only german)
* visit our [github](https://github.com/Litotex) repository
* or join our IRC-Cannel at [Freenode](irc://freenode.net/#litotex)

Your Litotex Team

## Installation
* Place your files properly (in most cases your webspace)
* Copy the config files to their right place and adjust them

> packages/core/config/const.php.dist -> packages/core/config/const.php
> packages/core/config/database.php.dist -> packages/core/config/database.php
> packages/core/config/path.php.dist -> packages/core/config/path.php
> acp/packages/core/config/const.php.dist -> acp/packages/core/config/const.php

You have to set *LITO_ROOT* and *LITO_URL* in this file
You can use this on a Linux-Shell

```bash
cp packages/core/config/const.php.dist packages/core/config/const.php
cp packages/core/config/database.conf.php.dist packages/core/config/database.conf.php
cp packages/core/config/path.php.dist packages/core/config/path.php
cp acp/packages/core/config/const.php.dist acp/packages/core/config/const.php
```

* Set write permissions (0777) to the following folders and create them if needed:

> packages/core/cache/
> files/cache/
> files/packages/cache/
> tpl_c/
> acp/tpl_c/
> log/

Or use this on a Shell:

```bash
mkdir packages/core/cache/ files/cache/ files/packages/cache/ tpl_c/ acp/tpl_c/ log/
chmod 777 packages/core/cache/ files/cache/ files/packages/cache/ tpl_c/ acp/tpl_c/ log/
```

* Create a database (and adjust the settings in the config file) and import all data from `dbUpdates/litotex.sql`

```bash
mysql -D litotexDB -u litotexUser -p < dbUpdate/litotex.sql
```
Admin Login:
    Username:admin
    Password:admin

User Login:
    Username:tester
    Password:tester
