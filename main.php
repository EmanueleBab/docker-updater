<?php
/**
 * DOCKER UPDATER
 * why PHP? idk
 * 
 * 
 * GENERAL INFO
 * 
 * the program has been written and tested on Arch Linux(amd64) without any distribution specific commands
 * [cmd line stuff]
 * {command1} | {command2} the | gets the output of command1 and uses it as parameter for command2 (pipes)
 * {command1} && {command2}  it executes command2 only if command 1 doesn't give any error, in this program it is used to correctly execute commands in different dirs
 * {command1} & runs the program in background
 * {command1} > /dev/null 2>&1 & ensures that php doesn't wait for any output from the command (used in docker run)  
 */


//composer stuff
require_once __DIR__ . '/vendor/autoload.php';

// https://github.com/vlucas/phpdotenv php dotenv library to parse .env files
$env = new Env\Dotenv('.env');

//.env vars loading in memory
$imageName = $env->get('IMAGE_NAME');
$dockerfileDir =  $env->get('DOCKERFILE_DIR');
$updateCoolDown = $env->get('UPDATE_COOLDOWN');

/**
 * check for updates function
 * this function works by fetching deltas from the remote repository and then checking his status with 'git status uno' that returns the local branch status,
 *  the result of the 'status' is then grepped to make it return null(or false) or @string if it contains 'behind' 
 * 
 * shell_exec is used instead of exec to make sure all the lines returned from the command are fetched
 */
function checkForUpdates()
{
    global $dockerfileDir;
    return shell_exec('cd ' . $dockerfileDir . " && git fetch && git status -uno | grep 'behind'"); 
}


/**
 * update function
 * 
 * this function checks if there is any docker image/ process running and makes shure to stop and delete them to ensure a clean rebuild
 * it then pulls the new docker container in its directory and then builds and starts the container
 */
function update()
{
    global $imageName, $dockerfileDir;
    $imageRunning = exec('docker ps | grep ' . $imageName);
    $imageExisting = exec('docker images | grep ' . $imageName);
    if (!$imageExisting) {
    } else {
        if (!$imageRunning) {
        } else {
            echo('stopping docker...');
            exec('docker stop ' . $imageName . ' -t 0');
            exec('docker rm ' . $imageName);
        }
        exec('docker rmi -f ' . $imageName);

    }
    echo('pulling from remote...');
    echo(exec('cd ' . $dockerfileDir . ' && git pull '));
    echo(exec('docker build --tag ' . $imageName . ' ' . $dockerfileDir.''));
    exec("bash -c 'docker run --name " . $imageName . ' ' . $imageName. " > /dev/null 2>&1 &' ");
}


/**
 * this is the main loop of the application, it ensures that every [.env UPDATE_COOLDOWN] seconds the update command is run in the case there is a remote update
 */
function loop()
{
    global $updateCoolDown;
    while (true) {
        sleep($updateCoolDown);
        echo('checking...');
        if (checkForUpdates()) {
            update();
        }
        else{
        }
    }
}

/**application starting point  */
update();
loop();
