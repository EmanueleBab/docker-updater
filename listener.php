<?php
require_once __DIR__ . '/vendor/autoload.php';
$env = new Env\Dotenv('.env');
$imageName = $env->get('IMAGE_NAME');
$imageDir =  $env->get('IMAGE_DIR');
$updateCoolDown = $env->get('UPDATE_COOLDOWN');
update();
loop();
function checkForUpdates()
{
    global $imageDir;
    return exec('cd ' . $imageDir . ' && git fetch');
}


function update()
{
    global $imageName, $imageDir;
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

    echo(exec('docker rm ' . $imageName));
    echo(exec('cd ' . $imageDir . ' && git pull'));
    echo(exec('docker build --tag ' . $imageName . ' ' . $imageDir));
    echo(exec('docker run --name ' . $imageName . ' ' . $imageName));
}



function loop()
{
    global $updateCoolDown;
    while (true) {
        if (checkForUpdates()) {
            update();
        }
        else{
        }
        sleep($updateCoolDown);
    }
}
