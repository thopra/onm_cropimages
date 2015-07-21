<?php
namespace ONM\OnmCropimages\Service;
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
class FileConfigurationService {


    /**
     * processedFileRepository
     *
     * @var ONM\OnmCropimages\Domain\Repository\ProcessedFileRepository
     * @inject
     */
    protected $processedFileRepository;

    /**
     * optionsRepository
     *
     * @var ONM\OnmCropimages\Domain\Repository\OptionsRepository
     * @inject
     */
    protected $optionsRepository;

	/**
	 * Slot for the postFileProcess signal
     *
     * This method overwrites the image, wich has originally been processed by template-based TS input
     * with a new one that is created by the TS configuration created by the user input within the backend module
	 * 
	 * @param $FileProcessingService Class containing the signal
	 * @param $driver 
	 * @param $processedFile
	 * @param $file
	 * @param $context
	 * @param $configuration
	 */
    public function postFileProcess($fileProcessingService, $driver, $processedFile, $file, $context, $configuration)
    {
    	if ($context != \TYPO3\CMS\Core\Resource\ProcessedFile::CONTEXT_IMAGECROPSCALEMASK)  {
    		return;
    	}

        if ($processedFile->getIdentifier() === NULL) {
            return;
        }

    	$fileProperties = $file->getProperties();
    	$storageConfig = $file->getStorage()->getConfiguration();

        $configuration = $this->getRealConfiguration($processedFile, $configuration);

    	$newConfiguration = $this->getConfigurationForFile($processedFile, $configuration);

        if ($configuration == $newConfiguration) {
            return;
        }

		$newProcessedFileObject = $file->process(\TYPO3\CMS\Core\Resource\ProcessedFile::CONTEXT_IMAGECROPSCALEMASK, $newConfiguration);
        $filePath = GeneralUtility::dirname(PATH_site);
        if ($storageConfig['basePath']) {
            $filePath .= '/'.GeneralUtility::dirname($storageConfig['basePath']);
        }

        /* 
            copy to a temporary location to prevent deletion  of the just created processed file,  wich would 
            otherwise lead to an arror when using updateWithLocalFile(), because that actually MOVES the source file
            and does not copy it. That means the processed file of the newly created $newProcessedFileObject will be deleted
            and that will lead to an "image has been deleted" exception.

            maybe there is a more elegant way to work around that, because this will of course affect performance.
        */

        if ( md5_file( $filePath . $newProcessedFileObject->getIdentifier() ) != md5_file( $filePath . $processedFile->getIdentifier() ) ) {
       
            if (!is_dir(PATH_site . 'typo3temp/onm_cropimages')) {
                mkdir(PATH_site . 'typo3temp/onm_cropimages');
            }
            $tmpFile = PATH_site . 'typo3temp/onm_cropimages'.str_replace("/", "_", $newProcessedFileObject->getIdentifier());
            file_put_contents($tmpFile, file_get_contents($filePath . $newProcessedFileObject->getIdentifier()));

            //update processed file
            $processedFile->updateWithLocalFile( $tmpFile );
            
        }
       
            // trying to clean up - still doesn't work that way.
            /*$objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
            $processedFileRepository = $objectManager->get('ONM\\OnmCropimages\\Domain\\Repository\\ProcessedFileRepository');
            $processedFileRepository->remove($newProcessedFileObject);*/

    }

    /**
     * Slot for the preFileProcess signal
     *
     * Currently not in use, still have to figure out why this doesn't work. 
     * But maybe we should use the preFileProcess signal in the future instead for the sake of performance.
     * 
     * @param $FileProcessingService Class containing the signal
     * @param $driver 
     * @param $processedFile
     * @param $file
     * @param $context
     * @param $configuration
     */
    public function preFileProcess($fileProcessingService, $driver, $processedFile, $file, $context, $configuration)
    {
        if ($context != \TYPO3\CMS\Core\Resource\ProcessedFile::CONTEXT_IMAGECROPSCALEMASK)  {
            return;
        }

        if ($processedFile->getIdentifier() === NULL) {
            return;
        }

        $fileProperties = $file->getProperties();
        //$storageConfig = $file->getStorage()->getConfiguration();

        $newConfiguration = $this->getConfigurationForFile($processedFile, $configuration);

        if ($configuration == $newConfiguration) {
            return;
        } 

        // 

        $processedFile = $file->process(\TYPO3\CMS\Core\Resource\ProcessedFile::CONTEXT_IMAGECROPSCALEMASK, $newConfiguration);
        $GLOBALS['testprocessedfile'] = $processedFile;
    }

    /**
     * get user-created configuration for a processed file
     * 
     * @param TYPO3\CMS\Core\Resource\ProcessedFile $processedFileObj 
     * @param array $defaultConfig (optional) the default configuration. user-config will be merged into this array
     * 
     */
    public function getConfigurationForFile($processedFileObj, $defaultConfig = array())
    {
    	$config = $this->getOptionsForFileAndConfig($processedFileObj->getOriginalFile()->getUid(), $defaultConfig);
    	return array_merge($defaultConfig, $config);
    }

    /**
     * get processed files
     * 
     * @param \TYPO3\CMS\Core\Resource\File $file
     * @return array
     */
    public function getProcessedFilesForFile(\TYPO3\CMS\Core\Resource\File $file)
    {
        //$processedFileRepository = GeneralUtility::makeInstance('ONM\\OnmCropimages\\Domain\\Repository\\ProcessedFileRepository');
        return $this->processedFileRepository->findByOriginal($file->getUid());
    }

    /**
     * gets all available option records for a certain file
     * 
     * @param integer $uid
     * @param array $conf
     */
    public function getOptionsForFileAndConfig($uid, $conf)
    {
        $objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $optionsRepository = $objectManager->get('ONM\\OnmCropimages\\Domain\\Repository\\OptionsRepository');
        $optionsRows = $optionsRepository->findByFile((int)$uid);
            
        $config = array();
        foreach ( $optionsRows as $options ) {
            if ((int)$options->getWidth() == (int)$conf['width'] && (int)$options->getHeight() == (int)$conf['height']) {
                $config['width'] = $options->getTSValueWidth();
                $config['height'] = $options->getTSValueHeight();
            }
        }

        return $config;
    }

    /**
     * Checks if the given configuration is valid for the processed file.
     * If the processed file does not fit the boundaries of the given configuration, 
     * the configuration is updated to match the real width and height of the file.
     * This is crucial, because the configuration in the backend module is based on the rendered file, 
     * not the TS Config that led to this file being generated.
     *
     * @param TYPO3\CMS\Core\Resource\ProcessedFile $processedFileObj 
     * @param array $configuration array
     * @return array
     */
    protected function getRealConfiguration($processedFile, $configuration)
    {
        

        $storageConfig = $processedFile->getStorage()->getConfiguration();
        $filePath = GeneralUtility::dirname(PATH_site);
        if ($storageConfig['basePath']) {
            $filePath .= '/'.GeneralUtility::dirname($storageConfig['basePath']);
        }

        // if images are scaled up, we dont have to do anything
        if ( (int)$GLOBALS['TYPO3_CONF_VARS']['GFX']['im_noScaleUp'] != 1 || !file_exists($filePath . $processedFile->getIdentifier()) ) {
            return $configuration;
        }

        list($width, $height) = getimagesize( $filePath . $processedFile->getIdentifier() );/*

        if($_GET['debug']) { 
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump( substr($configuration['width'], strpos((int)$configuration['width'], $configuration['width']) + strlen(((int)$configuration['width'])-1) ) );
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump( strlen(((int)$configuration['width'])-1) );
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump( $configuration );
        }*/

        if ($configuration['width'] && $width < (int)$configuration['width']) {
            $configuration['width'] = $width . substr($configuration['width'], strpos((int)$configuration['width'], $configuration['width']) + strlen(((int)$configuration['width'])-1) );
        }

        if ($configuration['height'] && $height < (int)$configuration['height']) {
            $configuration['height'] = $height . substr($configuration['height'], strpos((int)$configuration['height'], $configuration['height']) + strlen(((int)$configuration['height'])-1) );
        }

        /*if($_GET['debug']) { 
            \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump( $configuration );
            exit; 
        }*/

        return $configuration;
    }
    
}

