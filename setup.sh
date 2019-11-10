#!/bin/bash

function checkPrerequisits() {
  requireInstall=1
  if which node !> /dev/null; then
    echo "Please, install node before execute this script." & requireInstall=0
  fi

  if which composer !> /dev/null; then
    echo "Please, install composer before execute this script." & requireInstall=0
  fi

  if [[ "$(php --version | tail -r | tail -n 1 | cut -d " " -f 2 | cut -c 1,1)" != "7" ]] ;then
    echo "PHP 7 is requiered to execute this script." & requireInstall=0
  fi

  return ${requireInstall}
}

if checkPrerequisits; then
  exit;
fi

composer install
npm install
npm run dev