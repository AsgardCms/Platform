#!/usr/bin/env bash

# Reset Modules and Themes to their original state after composer has run

rm -rf Modules Themes
git reset HEAD Modules > /dev/null 2>&1
git reset HEAD Themes > /dev/null 2>&1
git checkout -- Modules > /dev/null 2>&1
git checkout -- Themes > /dev/null 2>&1

ls -al Themes
ls -al Themes/AdminLTE
ls -al Themes/Adminlte

composer dump-autoload
