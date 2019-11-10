#!/bin/bash
HOST='localhost'
PORT='8000'

while getopts h:p: option 
do 
 case "${option}" 
 in 
 h) HOST=${OPTARG};; 
 p) PORT=${OPTARG};; 
 esac 
done 

php artisan serve --host=$HOST --port=$PORT &
sleep 1;

if which open > /dev/null
then
  open http://$HOST:$PORT
elif which xdg-open > /dev/null
then
  xdg-open http://$HOST:$PORT
elif which gnome-open > /dev/null
then
  gnome-open http://$HOST:$PORT
elif which open > /dev/null
then
  open http://$HOST:$PORT
elif which cygstart > /dev/null
then
  cygstart http://$HOST:$PORT
elif which start > /dev/null
then
  start http://$HOST:$PORT
fi

## Force php serve to close if ctrl + c
while true; do
  sleep 1
done