<?php
require_once __DIR__ . '/vendor/autoload.php';

$env = new Env\Dotenv('.env');

$value = $env->get('REPOSITORY');
$imageName = $env->get('IMAGE_NAME');
$imageDir =  $env->get('IMAGE_DIR');
$imageRunning = exec('docker ps | grep ' . $imageName);
$imageExisting = exec('docker images | grep ' . $imageName);
if (!$imageExisting) {
} else {
    if (!$imageRunning) {
    } else {
        exec('docker stop ' . $imageName . ' -t 0');
    }
    exec('docker rmi -f ' . $imageName);
}

exec('docker rm ' . $imageName);
exec('cd '.$imageDir.' && git pull');
exec('docker build --tag ' . $imageName . ' ' . $imageDir);
exec('docker run --name '.$imageName. ' '.$imageName);
