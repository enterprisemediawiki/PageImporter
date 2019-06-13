#! /bin/bash
set -ex

BASE_PATH=$(pwd)
cd ..
MW_INSTALL_PATH=$(pwd)/mw

cd $MW_INSTALL_PATH/extensions/PageImporter

php importPages.php

TEMPLATE_VALUE=$(php $MW_INSTALL_PATH/maintenance/getText.php "Template:Test")
FILE_VALUE=$(cat $MW_INSTALL_PATH/extensions/ExampleExtension/ImportFiles/Template/Test.mediawiki)

if [ "$TEMPLATE_VALUE" = "$FILE_VALUE" ]; then
	true
else
	false
fi
