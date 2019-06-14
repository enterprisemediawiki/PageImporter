<?php

class ExampleExtension { // phpcs:ignore MediaWiki.Files.ClassMatchesFilename.NotMatch

	/**
	 * Hook handler for PageImporter::PageImporterRegisterPageLists
	 *
	 * @param array &$pageLists Array of page import definitions supplied by
	 *                            extensions
	 * @return null
	 */
	public static function onPageImporterRegisterPageLists( array &$pageLists ) {
		// The array key (here 'MyExtension') should be a unique name, generally
		// your extension's name
		$pageLists['ExampleExtension'] = [

			// list of pages to create and the corresponding files to use as content
			"pages" => [
				"Template:Test" => "Template/Test.mediawiki",
				"Category:Test Category" => "Category/Test_Category.mediawiki",
			],

			// the directory where all paths in your list of pages are based from
			"root" => __DIR__ . '/ImportFiles',

			// edit summary used when PageImporter edits pages
			"comment" => "Updated with content from Extension:ExampleExtension version 1.0.0"
		];
	}

}
