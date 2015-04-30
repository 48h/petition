<?php
require __DIR__  . '/src/SplClassLoader.php';

$oClassLoader = new \SplClassLoader('Petition', __DIR__ . '/src');
$oClassLoader->register();
