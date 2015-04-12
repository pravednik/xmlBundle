<?php

if(!file_exists(dirname(__DIR__) . '/composer.lock'))
{
    die(
        'Dependencies must be installed using composer: php composer.phar install --dev'
        . PHP_EOL
        . 'See http://getcomposer.org for help with installing composer'
        . PHP_EOL
    );
}

$autoloader = require_once(dirname(__DIR__) . '/vendor/autoload.php');
