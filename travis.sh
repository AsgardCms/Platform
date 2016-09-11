#!/usr/bin/env bash

# Loop over every module and theme to reset it to the original changes

cd Modules
for D in `find . -maxdepth 1 -mindepth 1 -type d`
do
    module=`echo "$D" | sed -e "s/^.\///"`
    rm -rf $module
    git reset HEAD $module
    sleep .1
    git checkout -- $module
done

cd ../Themes/
for D in `find . -maxdepth 1 -mindepth 1 -type d`
do
    theme=`echo "$D" | sed -e "s/^.\///"`
    rm -rf $theme
    git reset HEAD $theme
    sleep .1
    git checkout -- $theme
done




