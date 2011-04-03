<?php
require_once __DIR__ . '/../../Ferth/Autoloader.php';

$autoloader = new Ferth\Autoloader(__DIR__ . '/../..');
spl_autoload_register(array($autoloader, 'load'));

?>
