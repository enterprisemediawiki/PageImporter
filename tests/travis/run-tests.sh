#! /bin/bash
set -ex

BASE_PATH=$(pwd)
cd ..
MW_INSTALL_PATH=$(pwd)/mw

cd $MW_INSTALL_PATH/extensions/PageImporter

php importPages.php


RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color


fn_compare_file_with_page () {
	PAGE_NAME="$1"
	FILE_NAME="$2"

	PAGE_VALUE=$(php $MW_INSTALL_PATH/maintenance/getText.php "$PAGE_NAME")
	FILE_VALUE=$(cat $MW_INSTALL_PATH/extensions/ExampleExtension/ImportFiles/$FILE_NAME)

	if [ "$PAGE_VALUE" = "$FILE_VALUE" ]; then
		echo -e "$GREEN$PAGE_NAME matches $FILE_NAME$NC"
	else
		echo -e "$RED$PAGE_NAME does not match $FILE_NAME$NC" && false
	fi
}

fn_compare_file_with_page "Template:Test" "Template/Test.mediawiki"
fn_compare_file_with_page "Category:Test_Category" "Category/Test_Category.mediawiki"

# Edit pages
echo "New template text" | php $MW_INSTALL_PATH/maintenance/edit.php "Template:Test"
echo "New category text" | php $MW_INSTALL_PATH/maintenance/edit.php "Category:Test_Category"

# Push the changes from the wiki back to the extension's files
php importPages.php --export

fn_compare_file_with_page "Template:Test" "Template/Test.mediawiki"
fn_compare_file_with_page "Category:Test_Category" "Category/Test_Category.mediawiki"
