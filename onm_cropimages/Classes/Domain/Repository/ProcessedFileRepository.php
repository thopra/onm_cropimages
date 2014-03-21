<?php

namespace ONM\OnmCropimages\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Thomas Prangenberg <tpb@onm.de>, ONM
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package onm_cropimages
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class ProcessedFileRepository extends \TYPO3\CMS\Core\Resource\ProcessedFileRepository {


	/**
	 * findByOriginal
	 * find processed files by its originals uid
	 *
	 * @param integer $uid
	 * @param boolean $cropped only cropped images
	 * @return array
	 */
	public function findByOriginal($uid, $cropped = true)
	{
		$taskType = \TYPO3\CMS\Core\Resource\ProcessedFile::CONTEXT_IMAGECROPSCALEMASK;
		$processedFiles = array();
		$databaseRows = $this->databaseConnection->exec_SELECTgetRows(
			'*',
			$this->table,
			'original=' . intval($uid) 	.
				' AND task_type=' . $this->databaseConnection->fullQuoteStr($taskType, $this->table)
		);

		if (is_array($databaseRows)) {
			foreach ($databaseRows as $key => $row) {
				$processedFiles[$key] = $this->createDomainObject($row);

				if ( $cropped ) {
					$properties = $processedFiles[$key]->getProperties();
					$configuration = unserialize($properties['configuration']);
					if ( strpos($configuration['width'], 'c') === FALSE 
						&& strpos($configuration['height'], 'c') === FALSE 
						&& strpos($configuration['maxWidth'], 'c') === FALSE 
						&& strpos($configuration['maxHeight'], 'c') === FALSE 
					) {
						unset($processedFiles[$key]);
					}
				}

			}
		} 
		return $processedFiles;
	}	

}