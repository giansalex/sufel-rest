#!/usr/bin/env sh
./box_build.sh
ZIP_ASSET=sufel.phar.zip
php zip.php $ZIP_ASSET $PWD/dist
USER=giansalex
REPO=sufel-rest
GH_ASSET="https://uploads.github.com/repos/$USER/$REPO/releases/$TRAVIS_TAG/assets?name=$ZIP_ASSET"
curl --data-binary @"./$ZIP_ASSET" -H "Authorization: token $GITHUB_TOKEN" -H "Content-Type: application/zip" $GH_ASSET