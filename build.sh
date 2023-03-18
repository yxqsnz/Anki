#!/usr/bin/env bash
set -x
rm -rf build
mkdir -p build
cp -r {plugin.yml,README.md,src,resources} build
rm -rf build/src/Build.php

php --define phar.readonly=0 ./src/Build.php 