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

cd $MW_INSTALL_PATH/extensions
cp -r $BASE_PATH $MW_INSTALL_PATH/extensions/PageImporter

echo
echo "require_once \"$MW_INSTALL_PATH/extensions/PageImporter/PageImporter.php\";"
echo
