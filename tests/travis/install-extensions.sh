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
echo "\$wgShowExceptionDetails = true;" >> LocalSettings.php

echo "" >> LocalSettings.php
if [ "$LOAD_TYPE" = "extension.json" ]; then
	echo "wfLoadExtension('PageImporter');" >> LocalSettings.php
else
	echo "require_once \"$MW_INSTALL_PATH/extensions/PageImporter/PageImporter.php\";" >> LocalSettings.php
fi
echo "" >> LocalSettings.php

cp -r $MW_INSTALL_PATH/extensions/PageImporter/tests/ExampleExtension $MW_INSTALL_PATH/extensions/ExampleExtension
echo "" >> LocalSettings.php
if [ "$LOAD_TYPE" = "extension.json" ]; then
	echo "wfLoadExtension('ExampleExtension');" >> LocalSettings.php
else
	echo "require_once \"$MW_INSTALL_PATH/extensions/ExampleExtension/ExampleExtension.php\";" >> LocalSettings.php
fi
echo "" >> LocalSettings.php
