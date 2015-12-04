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

	public function __construct ( $pagesFile, $pagesFileRoot ) {
		$this->pagesFile = $pagesFile;
		$this->pagesFileRoot = $pagesFileRoot;
		$this->dryRun = false;
	}

	// like:
	// "Updated with content from Extension:MyExtension version " . MY_EXTENSION_VERSION
	public function import ( $editSummary ) {

		$output = '';

		$pages = json_decode( file_get_contents( $this->pagesFile ) );

		foreach( $pages as $pageTitleText => $filePath ) {

			$wikiPage = WikiPage::factory( Title::newFromText( $pageTitleText ) );
			$wikiPageContent = $wikiPage->getContent();
			if ( $wikiPageContent ) {
				$wikiPageText = $wikiPageContent->getNativeData();
			}
			else {
				$wikiPageText = '';
			}

			$filePageContent = file_get_contents( $this->pagesFileRoot . "/$filePath" );

			if ( trim( $filePageContent ) !== trim( $wikiPageText )  ) {

				if ( $this->dryRun ) {
					$output .= "$pageTitleText would be changed.\n";
					// @todo: show diff?
				}
				else {
					$output .= "$pageTitleText changed.\n";
					$wikiPage->doEditContent(
						new WikitextContent( $filePageContent ),
						$editSummary
					);
				}
			}
			else {
				$output .= "No change for $pageTitleText\n";
			}
		}

	}


	public function exportPagesToFiles () {

		$output = '';

		$pages = json_decode( file_get_contents( $this->pagesFile ) );

		foreach( $pages as $pageTitleText => $filePath ) {

			$wikiPage = WikiPage::factory( Title::newFromText( $pageTitleText ) );
			$wikiPageContent = $wikiPage->getContent();
			if ( $wikiPageContent ) {
				$wikiPageText = $wikiPageContent->getNativeData();
			}
			else {
				$wikiPageText = '';
			}

			$filePageContent = file_get_contents( $this->pagesFileRoot . "/$filePath" );

			if ( trim( $filePageContent ) !== trim( $wikiPageText )  ) {

				if ( $this->dryRun ) {
					$output .= "$pageTitleText would be changed.\n";
					// @todo: show diff?
				}
				else {
					$output .= "$pageTitleText changed.\n";
					file_put_contents( $this->pagesFileRoot . "/$filePath" , $wikiPageText );
				}
			}
			else {
				$output .= "No change for $pageTitleText\n";
			}
		}

	}

}
