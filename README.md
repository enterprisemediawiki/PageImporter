Page Importer
=============

Extension to provide the ability to have extension-defined pages. Extensions can define directories of files which map to wiki pages, and will be synced with the files anytime `php extensions/PageImporter/importPages.php` is run. Additionally, the current state of the wiki's pages (that are tracked by an extension) can be exported to the extension by doing `php extensions/PageImporter/importPages.php --export`.

Extensions can

```php
PageImporter::registerPageList(
	"MyExtension", // a unique name, generally your extension's name
	__DIR__ . "/pages.json", // a JSON file that maps MediaWiki wikitext files to wiki pages
	__DIR__ . "/pages", // the directory where all paths in your JSON file are based from
	"Updated with content from Extension:MyExtension version " . MY_EXTENSION_VERSION_NUMBER // edit summary
);
```

The JSON file should be in the following format. Essentially you need to map page names with file paths. The file paths are based from the directory mentioned above.

```json
{
	"Template:Meeting": "Template/Meeting.mediawiki",
	"Template:Meeting Minutes": "Template/Meeting Minutes.mediawiki",

	"Form:Meeting": "Form/Meeting.mediawiki",
	"Form:Meeting Minutes": "Form/Meeting Minutes.mediawiki",

	"Category:Meeting": "Category/Meeting.mediawiki",
	"Category:Meeting Minutes": "Category/Meeting Minutes.mediawiki",

	"Property:Start time": "Property/Start time.mediawiki",
	"Property:Synopsis": "Property/Synopsis.mediawiki"
}
```

The directory structure in this example looks like:

```
MyExtension
	MyExtension.php
	pages.json
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
			Start time.mediawiki
			Synopsis.mediawiki
```
