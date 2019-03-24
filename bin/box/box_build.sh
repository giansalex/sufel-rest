#!/usr/bin/env bash
rm -rf dist
cp box2/settings.php src/settings.php
rm -rf vendor
composer install --no-dev --optimize-autoloader
pathBox=$(which box)
if [ -x "$pathBox" ] ; then
    box build
else
    boxFile="box.phar"
    php ./bin/box/box.php $boxFile
    php -d phar.readonly=0 $boxFile build
fi
mkdir -p dist/upload
mv sufel.phar dist/sufel.phar
cp public/.htaccess dist/.htaccess
cp public/upload/.htaccess dist/upload/.htaccess
cp box2/index.php dist/index.php