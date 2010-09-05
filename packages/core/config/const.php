<?php
/*
 * This file is part of Litotex | Open Source Browsergame Engine.
 *
 * Litotex is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Litotex is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Litotex.  If not, see <http://www.gnu.org/licenses/>.
 */
define('LITO_ROOT', '/home/jonas/Dokumente/PHP/Litotex8/');
define('LITO_PLUGIN_ROOT', LITO_ROOT . 'plugins/');
define('LITO_URL', 'http://localhost/Litotex8/');
define('DATABASE_CONFIG_FILE', LITO_ROOT . 'packages/core/config/database.conf.php');
define('MODULES_DIRECTORY', LITO_ROOT . 'packages/');
define('TPL_DIR', 'tpl/');
define('IMG_DIR', 'img/');
define('JS_DIR', 'js/');
define('CSS_DIR', 'css/');
define('LANG_DIR', 'lang/');
define('TPL_DIRNAME', LITO_URL . TPL_DIR);
define('TEMPLATE_DIRECTORY', LITO_ROOT . TPL_DIR);
define('TEMPLATE_COMPILATION', LITO_ROOT . 'tpl_c');
define('HOOK_CACHE', LITO_ROOT . 'packages/core/cache/hook_cache.php');
define('PACKAGE_CACHE', LITO_ROOT . 'packages/core/cache/dependency_cache.php');
define('TPLMOD_CACHE', LITO_ROOT . 'packages/core/cache/tpl_modification_cache.php');
define('DB_PREFX', 'lttx1_');
define('RESSOURCE_UPDATE_INTERVAL', 1);