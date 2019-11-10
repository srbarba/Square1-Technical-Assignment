#!/bin/bash

function checkPrerequisits() {
  requireInstall=1
  if !(node -v > /dev/null); then
    echo "Please, install node before execute this script." & requireInstall=0
  fi

  if !(composer -v > /dev/null); then
    echo "Please, install composer before execute this script." & requireInstall=0
  fi

  if [[ "$(php --version | head -n 1 | cut -d " " -f 2 | cut -c 1,3)" -ge "71" ]] ;then
    echo "PHP 7.1 at least is requiered to execute this script." & requireInstall=0
  fi

  return ${requireInstall}
}

if checkPrerequisits; then
  exit;
fi

composer install
npm install
npm run dev