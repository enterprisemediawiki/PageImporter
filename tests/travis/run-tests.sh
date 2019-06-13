#! /bin/bash
set -ex

BASE_PATH=$(pwd)
cd ..
MW_INSTALL_PATH=$(pwd)/mw

cd $MW_INSTALL_PATH/extensions/PageImporter

php importPages.php
