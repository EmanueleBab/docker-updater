## DOCKER UPDATER
## why PHP? idk
  
  
## GENERAL INFO
the program has been written and tested on Arch Linux(amd64) without any distribution specific command
## cmd line stuff
{command1} | {command2} the | gets the output of command1 and uses it as parameter for command2 (pipes)
 <br/>
{command1} && {command2}  it executes command2 only if command 1 doesn't give any error, in this program it is used to correctly execute commands in different dirs
 <br/>
{command1} & runs the program in background
 <br/>
{command1} > /dev/null 2>&1 & ensures that php doesn't wait for any output from the command (used in docker run)  
