#!/bin/bash
#
# Adapted from SMW

set -ex

BASE_PATH=$(pwd)
cd ..
MW_INSTALL_PATH=$(pwd)/mw

echo "base path:"
ls $BASE_PATH

echo "mw install path:"
ls $MW_INSTALL_PATH

cp -r $BASE_PATH $MW_INSTALL_PATH/extensions/PageImporter

cd $MW_INSTALL_PATH
echo "" >> LocalSettings.php
echo "require_once \"$MW_INSTALL_PATH/extensions/PageImporter/PageImporter.php\";" >> LocalSettings.php
echo "" >> LocalSettings.php

cp -r $MW_INSTALL_PATH/extensions/PageImporter/tests/ExampleExtension $MW_INSTALL_PATH/extensions/ExampleExtension
echo "" >> LocalSettings.php
echo "require_once \"$MW_INSTALL_PATH/extensions/ExampleExtension/ExampleExtension.php\";" >> LocalSettings.php
echo "" >> LocalSettings.php
