Page Importer
=============

[![Build Status](https://travis-ci.org/enterprisemediawiki/PageImporter.svg?branch=master)](https://travis-ci.org/enterprisemediawiki/PageImporter)

Extension to provide the ability to have extension-defined pages. Extensions can define directories of files which map to wiki pages, and will be synced with the files anytime `php extensions/PageImporter/importPages.php` is run. Additionally, the current state of the wiki's pages (that are tracked by an extension) can be exported to the extension by doing `php extensions/PageImporter/importPages.php --export`.

## Loading in MediaWiki

PageImporter should be loaded using `wfLoadExtension`:

```php
wfLoadExtension('PageImporter');
```

It is still possible to load PageImporter with the legacy method, but this is deprecated:

```php
require_once "$IP/extensions/PageImporter/PageImporter.php";
```

Either way loads Page Importer into MediaWiki, however *PageImporter* does basically nothing on its own. It is expected to be used by other extensions. See below.

## Usage by extensions

Extensions can register pages to be imported by registering a hook handler to their `extension.json`:

```json
"Hooks": {
	"PageImporterRegisterPageLists": "MyExtension::onPageImporterRegisterPageLists"
}
```

Then create a hook handling function:

```php
public static function onPageImporterRegisterPageLists( array &$pageLists ) {

	// The array key (here 'MyExtension') should be a unique name, generally
	// your extension's name
	$pageLists['MyExtension'] = [

		// list of pages to create and the corresponding files to use as content
		"pages" => [
			"Template:Meeting" => "Template/Meeting.mediawiki",
			"Category:Meeting" => "Category/Meeting.mediawiki",
			"Form:Meeting" => "Form/Meeting.mediawiki",
			"Property:Related article" => "Property/Related article.mediawiki",
		],

		// the directory where all paths in your list of pages are based from
		"root" => __DIR__ . '/pages',

		// edit summary used when PageImporter edits pages
		"comment" => "Updated with content from Extension:MyExtension version 1.0.0"
	];

}
```

Note that here the `pages` key has an array of pages. Alternatively, `pages` can be a string representing the path to a file containing a list of pages. For example:

```php
$pageLists['MyExtension'] = [
	// ...
	'pages' => __DIR__ . '/pages.php',
	// ...
];
```

However, the method of having a separate file is not preferred because it prevents other extensions from being able to alter which pages are imported/exported (see section below). As such, this method may be removed in a future version.

## Altering other extension's pages

Sometimes pages from one extension may conflict with another. One possible method to avoid this is for an extension to detect if another extension is present and unset the page from that extension. This can be done with another hook, `PageImporterBeforeImportOrExport`.

```json
"Hooks": {
	"PageImporterBeforeImportOrExport": "MyExtension::onPageImporterBeforeImportOrExport"
}
```

```php
public static function onPageImporterBeforeImportOrExport( array &$pageLists ) {

	// Remove the "Property:Related article" page from another extension
	if ( isset( $pageLists['SemanticMeetingMinutes'] ) ) {
		unset( $pageLists['SemanticMeetingMinutes']['pages']['Property:Related article'] );
	}
}
```

## Deprecated method of registering pages

```php
PageImporter::registerPageList(
	"MyExtension", // a unique name, generally your extension's name
	__DIR__ . "/pages.php", // a php file that maps MediaWiki wikitext files to wiki pages
	__DIR__ . "/pages", // the directory where all paths in your pages.php file are based from
	"Updated with content from Extension:MyExtension version 1.0.0" // edit summary
);
```

## Pages file format

The PHP file should be in the following format. Essentially you need to map page names with file paths. The file paths are based from the directory mentioned above.

```php
{
<?php
$pages = array(
	"Template:Meeting" => "Template/Meeting.mediawiki",
	"Template:Meeting minutes" => "Template/Meeting minutes.mediawiki",

	"Form:Meeting" => "Form/Meeting.mediawiki",
	"Form:Meeting Minutes" => "Form/Meeting Minutes.mediawiki",

	"Category:Meeting" => "Category/Meeting.mediawiki",
	"Category:Meeting Minutes" => "Category/Meeting Minutes.mediawiki",

	"Property:Related article" => "Property/Related article.mediawiki",
	"Property:Synopsis" => "Property/Synopsis.mediawiki",
);
}
```

## Directory structure

Whether putting pages in a separate files or not, the directory structure of the examples in this README looks like:

```
MyExtension
	MyExtension.php
	pages.php
	pages
		Template
			Meeting.mediawiki
			Meeting Minutes.mediawiki
		Form
			Meeting.mediawiki
			Meeting Minutes.mediawiki
		Category
			Meeting.mediawiki
			Meeting Minutes.mediawiki
		Property
			Related article.mediawiki
			Synopsis.mediawiki
```
