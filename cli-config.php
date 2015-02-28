<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

// replace with file to your own project bootstrap
require_once 'packages/core/config/path.php';
require_once 'packages/core/config/const.php';
require_once 'packages/core/global.php';

// replace with mechanism to retrieve EntityManager in your app
$entityManager = Package::$em;

return ConsoleRunner::createHelperSet($entityManager);
