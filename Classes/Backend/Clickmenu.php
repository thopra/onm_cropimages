<?php

namespace ONM\OnmCropimages\Backend;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This class adds the "crop image" option in the contextmenu within the filelist module
 *
 * @todo only add this, if the clicked item is actually an image - having it appear on any document is not that nice.
 */

class Clickmenu {

    /**
     * Adding options to the context menu
     *
     * @param    object       Instance of the backend object
     * @param    array        Array with the currently collected menu items to show.
     * @param    string       Table name of clicked item.
     * @param    integer      UID of clicked item.
     * @return   array        Modified $menuItems array
     */
    public function main(&$backRef, $menuItems, $combinedIdentifier, $uid) {

        $localItems = array();
        $GLOBALS['LANG']->includeLLFile('EXT:onm_cropimages/Resources/Private/Language/locallang_mod.xml');
        if (!$backRef->cmLevel) {
            // Make 1st level clickmenu:
            if (!$backRef->isDBmenu) {

                $combinedIdentifier = rawurldecode($combinedIdentifier);
                $fileObject = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()
                        ->retrieveFileOrFolderObject($combinedIdentifier);
                if ($fileObject) {
                    $folder = FALSE;
                    $identifier = $fileObject->getCombinedIdentifier();
                    if ($fileObject instanceof \TYPO3\CMS\Core\Resource\Folder) {
                        $folder = TRUE;
                    } else {
                        $icon = \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIconForFile($fileObject->getExtension(), array(
                            'class' => 'absmiddle',
                            'title' => htmlspecialchars($fileObject->getName() . ' (' . GeneralUtility::formatSize($fileObject->getSize()) . ')')
                        ));
                    }

                    if (!$folder) {
                        $url = $backRef->backPath . 'mod.php?M=file_OnmCropimagesCropimages&id='. rawurlencode($combinedIdentifier);
                        $localItems[] =  $backRef->linkItem($GLOBALS['LANG']->getLL('contextmenu_crop'), $backRef->excludeIcon($icon), $backRef->urlRefForCM($url), 1);

                    }
               
                }

            }   
        }

        return array_merge($menuItems, $localItems);
    }

}

