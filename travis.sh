#!/usr/bin/env bash

# Reset Modules and Themes to their original state after composer has run

rm -rf Modules Themes
git reset HEAD Modules &> /dev/null
git reset HEAD Themes &> /dev/null
git checkout -- Modules &> /dev/null
git checkout -- Themes &> /dev/null
