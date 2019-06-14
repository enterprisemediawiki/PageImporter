<?php
/**
 * This script updates the extensions managed by the it
 *
 * Usage:
 *  no parameters
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
 * @ingroup Maintenance
 */

// @todo: does this always work if extensions are not in $IP/extensions ??
// this was what was done by SMW
$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/../..';
require_once $basePath . '/maintenance/Maintenance.php';

class PageImporterImportPages extends Maintenance { // phpcs:ignore MediaWiki.Files.ClassMatchesFilename.NotMatch

	public function __construct() {
		parent::__construct();

		$this->mDescription = "Imports pages defined by this and other extensions.";

		// addOption ($name, $description, $required=false, $withArg=false, $shortName=false)
		$this->addOption( 'limit-to-groups', 'Specify which groups of pages to import' );
		$this->addOption( 'dry-run', 'See what would be changed without making changes',
			false, false );
		$this->addOption( 'export', 'Export pages to files rather than import',
			false, false );
	}

	public function execute() {
		$groupsString = $this->getOption( 'limit-to-groups' );
		if ( $groupsString ) {
			$limitToGroups = explode( ',', $groupsString );
			foreach ( $limitToGroups as $i => $g ) {
				$limitToGroups[$i] = trim( $g );
			}
		} else {
			$limitToGroups = false;
		}

		$pageImporter = new PageImporter();

		// export pages
		if ( $this->getOption( 'export' ) ) {
			$pageImporter->exportPagesToFiles( $this, $limitToGroups );
			$this->output( "\n## Finished exporting pages.\n" );
		}
		// import pages
 else {
			$pageImporter->import( $this, $limitToGroups );
			$this->output( "\n## Finished importing pages.\n" );
	}
	}

	/**
	 * just a wrapper on output() because output() is protected and the
	 * PageImporter class needs to call it directly.
	 *
	 * @param string $output Text to print
	 */
	public function showOutput( $output ) {
		$this->output( $output );
	}

}

$maintClass = "PageImporterImportPages";
require_once RUN_MAINTENANCE_IF_MAIN;
