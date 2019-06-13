#!/bin/bash
#
# Adapted from SMW

set -ex

BASE_PATH=$(pwd)
MW_INSTALL_PATH=$BASE_PATH/../mw

cd $MW_INSTALL_PATH/extensions
ln -s $BASE_PATH $MW_INSTALL_PATH/extensions/PageImporter

echo
echo "require_once \"$MW_INSTALL_PATH/extensions/PageImporter/PageImporter.php\";"
echo
