<?php

namespace ONM\OnmCropimages\ViewHelpers;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FilePathViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {
    /**
     *
     * @param mixed $fileObject
     * @return string 
     */
    public function render($fileObject) {
        if (file_exists(PATH_site.$fileObject->getPublicUrl())) {
            return $fileObject->getPublicUrl();
        }

        return false;
    }
}