<?php
/**
 * Extension to provide the ability to have extension-defined pages
 *
 * Documentation: https://github.com/enterprisemediawiki/PageImporter
 * Support:       https://github.com/enterprisemediawiki/PageImporter
 * Source code:   https://github.com/enterprisemediawiki/PageImporter
 *
 * @file PageImporter.php
 * @addtogroup Extensions
 * @author James Montalvo
 * @copyright © 2014 by James Montalvo
 * @license GPL-3.0-or-later
 */

# Not a valid entry point, skip unless MEDIAWIKI is defined
if ( ! defined( 'MEDIAWIKI' ) ) {
	die( 'PageImporter extension' );
}

$GLOBALS['wgExtensionCredits']['other'][] = [
	'path'           => __FILE__,
	'name'           => 'Page Importer',
	'url'            => 'http://github.com/enterprisemediawiki/PageImporter',
	'author'         => 'James Montalvo',
	'descriptionmsg' => 'pageimporter-desc',
	'version'        => '0.1.0'
];

$GLOBALS['wgMessagesDirs']['PageImporter'] = __DIR__ . '/i18n';

// Autoload setup class (location of parser function definitions)
// $GLOBALS['wgAutoloadClasses']['PageImporter'] = __DIR__ . '/PageImporter.class.php';

// PageImporter needs to be loaded immediately so other extensions can use the
// PageImporter::registerPageList() static method
require_once __DIR__ . '/PageImporter.class.php';
