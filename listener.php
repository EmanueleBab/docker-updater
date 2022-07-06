<?php
require_once __DIR__ . '/vendor/autoload.php';

$env = new Env\Dotenv('.env');

$value = $env->get('REPOSITORY');
$c = exec('./pull.sh');  
echo($value);