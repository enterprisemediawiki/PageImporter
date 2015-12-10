<?php
/**
 * Class enabling page import and export
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @author James Montalvo
 */

class PageImporter {

	/**
	 * @var array
	 */
	protected static $pageLists = array();

	/**
	 * Add description
	 *
	 * @param string $varName add description
	 * @return null
	 */
	public function __construct ( $dryRun=false ) {
		$this->dryRun = $dryRun;
	}

	/**
	 * Register a list of pages to be imported
	 *
	 * @param string $groupName an identifying string for a group of pages
	 * @param string $listFile the path to the JSON file defining the pages to be imported
	 * @param string $root the path to the root of the files
	 * @param string $comment comment to be added to each file import
	 * @return null
	 */
	public static function registerPageList ( $groupName, $listFile, $root, $comment ) {
		self::$pageLists[$groupName] = array(
			"listFile" => $listFile,
			"root" => $root,
			"comment" => $comment
		);
	}

	/**
	 * Add description
	 *
	 * @param string $varName add description
	 * @return null
	 */
	public function import ( $outputHandler=false, $limitToGroups=false ) {

		if ( ! $outputHandler ) {
			$outputHandler = $this;
		}

		$groupsToImport = array();
		if ( $limitToGroups ) {
			foreach( $limitToGroups as $group ) {
				$groupsToImport[$group] = self::$pageLists[$group];
			}
		}
		else {
			$groupsToImport = self::$pageLists;
		}

		foreach( $groupsToImport as $groupName => $groupInfo ) {

			$outputHandler->output( "\nStarting import from $groupName.\n\n" );

			$root = $groupInfo["root"];
			$editSummary = $groupInfo["editSummary"];

			$pages = json_decode( file_get_contents( $groupInfo["listFile"] ) );

			foreach( $pages as $pageTitleText => $filePath ) {

				$wikiPage = WikiPage::factory( Title::newFromText( $pageTitleText ) );
				$wikiPageContent = $wikiPage->getContent();
				if ( $wikiPageContent ) {
					$wikiPageText = $wikiPageContent->getNativeData();
				}
				else {
					$wikiPageText = '';
				}

				$filePageContent = file_get_contents( $root . "/$filePath" );

				if ( trim( $filePageContent ) !== trim( $wikiPageText )  ) {

					if ( $this->dryRun ) {
						$outputHandler->output( "$pageTitleText would be changed.\n" );
						// @todo: show diff?
					}
					else {
						$outputHandler->output( "$pageTitleText changed.\n" );
						$wikiPage->doEditContent(
							new WikitextContent( $filePageContent ),
							$editSummary
						);
					}
				}
				else {
					$outputHandler->output( "No change for $pageTitleText\n" );
				}
			}

		}
	}

	/**
	 * Add description
	 *
	 * @param string $varName add description
	 * @return null
	 */
	public function exportPagesToFiles ( $outputHandler=false, $limitToGroups=false ) {

		if ( ! $outputHandler ) {
			$outputHandler = $this;
		}

		$groupsToImport = array();
		if ( $limitToGroups ) {
			foreach( $limitToGroups as $group ) {
				$groupsToImport[$group] = self::$pageLists[$group];
			}
		}
		else {
			$groupsToImport = self::$pageLists;
		}

		foreach( $groupsToImport as $groupName => $groupInfo ) {

			$outputHandler->output( "\nStarting export from $groupName.\n\n" );

			$root = $groupInfo["root"];
			$editSummary = $groupInfo["editSummary"];

			$pages = json_decode( file_get_contents( $groupInfo["listFile"] ) );

			foreach( $pages as $pageTitleText => $filePath ) {

				$wikiPage = WikiPage::factory( Title::newFromText( $pageTitleText ) );
				$wikiPageContent = $wikiPage->getContent();
				if ( $wikiPageContent ) {
					$wikiPageText = $wikiPageContent->getNativeData();
				}
				else {
					$wikiPageText = '';
				}

				$filePageContent = file_get_contents( $root . "/$filePath" );

				if ( trim( $filePageContent ) !== trim( $wikiPageText )  ) {

					if ( $this->dryRun ) {
						$outputHandler->output( "$pageTitleText would be exported.\n" );
						// @todo: show diff?
					}
					else {
						$outputHandler->output( "$pageTitleText exported.\n" );
						file_put_contents( $root . "/$filePath" , $wikiPageText );
					}
				}
				else {
					$outputHandler->output( "No change for $pageTitleText\n" );
				}
			}

		}
	}

	/**
	 * Function used if a maintenance class is not provided
	 *
	 * @param string $varName add description
	 * @return null
	 */
	public function output ( $output ) {
		echo $output;
	}

}
