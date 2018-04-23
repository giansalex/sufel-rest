#!/usr/bin/env sh
./bin/box/box_build.sh
ZIP_ASSET=./sufel.phar.zip
php ./bin/box/zip.php $ZIP_ASSET $PWD/dist
./bin/box/upload-asset.sh github_api_token=$GITHUB_TOKEN owner=giansalex repo=sufel-rest tag=$TRAVIS_TAG filename=$ZIP_ASSET