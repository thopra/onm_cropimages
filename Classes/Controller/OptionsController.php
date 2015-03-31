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
class OptionsController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

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
	 * relative path to the public directory to be accessed in the backend module view
	 *
	 * @var string
	 */
	protected $extPathPublic = '../typo3conf/ext/onm_cropimages/Resources/Public';

	/**
	 * Initialize the controller and backend module template
	 *
	 */
	protected function init()
	{
		$this->doc = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Template\\DocumentTemplate');
		$this->doc->backPath = $GLOBALS['BACK_PATH'];
		$this->doc->addStyleSheet( 'jcrop', $this->extPathPublic.'/Contrib/Jcrop/css/jquery.Jcrop.min.css' );
		$this->doc->loadJavascriptLib( 'contrib/jquery/jquery-1.8.2.min.js' );
		$this->view->assign( 'docPageStart', $this->doc->startPage('Crop Images') );

		if (GeneralUtility::_GP('id')) {
			$this->request->setArgument('id', GeneralUtility::_GP('id'));
		}
		
		$this->id = $this->request->getArgument('id');

	}

	/**
	 * action new
	 *
	 * @param \ONM\OnmCropimages\Domain\Model\Options $newOptions
	 * @dontvalidate $newOptions
	 * @return void
	 */
	public function newAction(\ONM\OnmCropimages\Domain\Model\Options $newOptions = NULL) {

		$this->init();

		$file = $this->getFile();

		$processedFile = false;
		$processedFilesOriginals = $this->fileConfigurationService->getProcessedFilesForFile($file);
		
		if (is_array($processedFilesOriginals)) {
			foreach ($processedFilesOriginals as $key => $processed) {
				$properties = $processed->getProperties();
				if ( $properties['width'] == $this->request->getArgument('width') && $properties['height'] == $this->request->getArgument('height') ) {
					$processedFile = $processed;
				}
			}
		}

		if ( $newOptions === NULL ) {
			$newOptions = new \ONM\OnmCropimages\Domain\Model\Options();
		}

		$this->view->assign('id', $this->id);
		$this->view->assign('aspectRatio', $this->getAspectRatio($processedFile));
		$this->view->assign('aspectRatioProcessed', $this->getAspectRatio($file));
		$this->view->assign('extPathPublic', $this->extPathPublic);
		$this->view->assign('processedFile', $processedFile);
		$this->view->assign('file', $file);
		$this->view->assign('newOptions', $newOptions);
	}

	/**
	 * action create
	 *
	 * @param \ONM\OnmCropimages\Domain\Model\Options $newOptions
	 * @return void
	 */
	public function createAction(\ONM\OnmCropimages\Domain\Model\Options $newOptions) {

		$this->init();
		$file = $this->getFile();
		$newOptions->setFile($file->getUid());

		$this->optionsRepository->add($newOptions);

		$this->redirect('list', 'ProcessedFile', NULL, array('id' => $this->id));
	}

	/**
	 * action edit
	 *
	 * @param \ONM\OnmCropimages\Domain\Model\Options $options
	 * @dontvalidate $options
	 * @return void
	 */
	public function editAction(\ONM\OnmCropimages\Domain\Model\Options $options) {
		
		$this->init();

		$file = $this->getFile();

		$processedFile = false;
		$processedFilesOriginals = $this->fileConfigurationService->getProcessedFilesForFile($file);
		
		if (is_array($processedFilesOriginals)) {
			foreach ($processedFilesOriginals as $key => $processed) {
				$properties = $processed->getProperties();
				if ( $properties['width'] == $this->request->getArgument('width') && $properties['height'] == $this->request->getArgument('height') ) {
					$processedFile = $processed;
				}
			}
		}

		$this->view->assign('id', $this->id);
		$this->view->assign('aspectRatio', $this->getAspectRatio($processedFile));
		$this->view->assign('aspectRatioProcessed', $this->getAspectRatio($file));
		$this->view->assign('extPathPublic', $this->extPathPublic);
		$this->view->assign('processedFile', $processedFile);
		$this->view->assign('file', $file);
		$this->view->assign('options', $options);

	}

	/**
	 * action update
	 *
	 * @param \ONM\OnmCropimages\Domain\Model\Options $options
	 * @return void
	 */
	public function updateAction(\ONM\OnmCropimages\Domain\Model\Options $options) {

		$this->init();

		$this->optionsRepository->update($options);
		
		$this->redirect('list', 'ProcessedFile', NULL, array('id' => $this->id));
	}

	/**
	 * action delete
	 *
	 * Currently, there is no way for the backend user to delete a previously made configuration for a file.
	 * So, this is currently not in use. Should be added in a future release.
	 *
	 * @param \ONM\OnmCropimages\Domain\Model\Options $options
	 * @return void
	 */
	public function deleteAction(\ONM\OnmCropimages\Domain\Model\Options $options) {
		$this->optionsRepository->remove($options);
		$this->redirect('list');
	}

	/**
	 * Receives the File or Folder object for the current id
	 * Throws an exception, if the id cannot be resolved or the file does not exist
	 *
	 * @return mixed
	 */
	private function getFile()
	{
		$fileFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Resource\\ResourceFactory');

		$file =  $fileFactory->retrieveFileOrFolderObject($this->id);
		if (!$file) {
			throw new Exception("File not found.", 1);
		}

		return $file;
	}

	/**
	 * get orientation of a processed file
	 *
	 */
	private function getAspectRatio($file)
	{
		$properties = $file->getProperties();
		
		return $properties['width'] / $properties['height'];
	}

}
?>