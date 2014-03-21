<?php
namespace ONM\OnmCropimages\Controller;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
class ProcessedFileController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * optionsRepository
	 *
	 * @var \ONM\OnmCropimages\Domain\Repository\OptionsRepository
	 * @inject
	 */
	protected $optionsRepository;

	/**
	 * FileConfigurationService
	 *
	 * @var ONM\OnmCropimages\Service\FileConfigurationService
	 * @inject
	 */
	protected $fileConfigurationService;

	/**
	 * Identifier of the corresponding file, containing the storage id and the file path (ie: 1:/fileadmin/test.jpg)
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Initialize the controller and backend module template
	 *
	 */
	protected function init()
	{
		$this->doc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Template\\DocumentTemplate');
		$this->doc->backPath = $GLOBALS['BACK_PATH'];
		$this->view->assign( 'docPageStart', $this->doc->startPage() );

		if (GeneralUtility::_GP('id')) {
			$this->request->setArgument('id', GeneralUtility::_GP('id'));
		}

		if (!$this->request->hasArgument('id')) {
			$this->id = false;
		} else {
			$this->id = $this->request->getArgument('id');
		}
	}

	/**
	 * action list
	 * lists all processed images for the current id 
	 * (or, if the current id is resolved to a folder, redirects to listFolderAction)
	 *
	 * @return void
	 */
	public function listAction() {
		
		$this->init();

		if(!$this->id) {
			$this->view->assign('intro', true);
			return;
		}
		$fileFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');

		$file =  $fileFactory->retrieveFileOrFolderObject($this->id);
		if ($file instanceof \TYPO3\CMS\Core\Resource\Folder) {
			$this->redirect('listFolder');
		}

		if (!($file instanceof \TYPO3\CMS\Core\Resource\File)) {
			$this->view->assign('intro', true);
			return;
		}

		$processedFiles = $this->getProcessedFilesForFile($file);

		$this->view->assign('file', $file);
		$this->view->assign('processedFiles', $processedFiles);
	}

	/**
	 * action listFolder
	 * lists all images within a folder (if the current id is a Foler object) and shows a preview of their processed file dimensions
	 *
	 * @return void
	 */
	public function listFolderAction() {

		$this->init();

		$fileFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');
		$folderObject = $fileFactory->getFolderObjectFromCombinedIdentifier($this->id);

		if ($folderObject && ($folderObject->getStorage()->getUid() == 0 || trim($folderObject->getStorage()->getProcessingFolder()->getIdentifier(), '/') === trim($folderObject->getIdentifier(), '/'))) {
			$storage = $fileFactory->getStorageObjectFromCombinedIdentifier($combinedIdentifier);
			$folderObject = $storage->getRootLevelFolder();
		}

		$files = array();
		foreach ( $folderObject->getFiles() as $key => $file ) {
			$files[$key] = array(
				'fileObject' => $file,
				'processedFiles' => $this->getProcessedFilesForFile($file)
			);
		}


		$this->view->assign('files', $files);
		$this->view->assign('folder', array(
			'name' => $folderObject->getName(),
			'identifier' => $folderObject->getIdentifier(),
		));
	}

	/**
	 * Receives all processed files for a certain File object 
	 *
	 * @var TYPO3\CMS\Core\Resource\File
	 * @return array
	 */
	protected function getProcessedFilesForFile($file)
	{
		$processedFiles = array();
		$processedFilesOriginals = $this->fileConfigurationService->getProcessedFilesForFile($file);
		$alreadyConfigured = $this->optionsRepository->findByFile($file->getUid());

		if (is_array($processedFilesOriginals)) {
			foreach ($processedFilesOriginals as $key => $processedFile) {
					
				$properties = $processedFile->getProperties();
				$arKey = (int)$properties['width'].'-'.(int)$properties['height'];

				$ratio = round( ((int)$properties['width'] / (int)$properties['height']), 2);
				$orgRatio = round( ($file->getProperty('width') / $file->getProperty('height')), 2);

				// do not list images with the same aspect ratio as the original file or missing processed files
				$storageConf = $processedFile->getStorage()->getConfiguration();
        		$baseDir = GeneralUtility::dirname($storageConf['basePath']);
				if ( $orgRatio != $ratio && file_exists(PATH_site.$baseDir.$processedFile->getIdentifier()) ) {

					$processedFiles[$arKey] = array(
						'processed' => $processedFile,
						'previewWidth' => ((int)$properties['width']/(int)$properties['height'])*20,
						'previewHeight' => 20,
						'ratio' => $ratio
					);

					foreach ($alreadyConfigured as $options) {
						
						if ( $options->getWidth() == $properties['width'] && $options->getHeight() == $properties['height'] ) {
							$processedFiles[$arKey]['options'] = $options;
						}
					}

				}
			}
		}

		return $processedFiles;
	}
}
?>