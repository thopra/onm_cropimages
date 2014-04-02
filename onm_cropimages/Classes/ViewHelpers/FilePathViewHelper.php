<?php

namespace ONM\OnmCropimages\ViewHelpers;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FilePathViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    /**
     *
     * @param $fileObject
     * @return string 
     */
    public function render($fileObject) {
       
        $storageConf = $fileObject->getStorage()->getConfiguration();
        $baseDir = GeneralUtility::dirname($storageConf['basePath']);

        return $baseDir.$fileObject->getIdentifier();
    }
}