#!/usr/bin/env bash

# Reset Modules and Themes to their original state after composer has run

rm -rf Modules Themes
git reset HEAD Modules
git reset HEAD Themes
git checkout -- Modules
git checkout -- Themes
