#!/usr/bin/env bash

travis_retry composer self-update
travis_retry composer update ${COMPOSER_FLAGS} --no-interaction --prefer-dist
