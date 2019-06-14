<?php
# Not a valid entry point, skip unless MEDIAWIKI is defined
if ( ! defined( 'MEDIAWIKI' ) ) {
	die( 'MeetingMinutes extension' );
}

$GLOBALS['wgExtensionCredits']['other'][] = [
	'path'           => __FILE__,
	'name'           => 'Example Extension',
	'url'            => 'http://github.com/enterprisemediawiki',
	'author'         => 'James Montalvo',
	'descriptionmsg' => 'example-extension-desc',
	'version'        => '0.0.0'
];

$GLOBALS['wgMessagesDirs']['ExampleExtension'] = __DIR__ . '/i18n';

// Dependency: Extension:PageImporter.
PageImporter::registerPageList(
	"ExampleExtension",
	__DIR__ . "/ImportFiles/pages.php",
	__DIR__ . "/ImportFiles",
	"Updated with content from Extension:ExampleExtension version 0.0.0"
);
