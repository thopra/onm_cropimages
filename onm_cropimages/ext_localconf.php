<?php

/**
 * Define the slot for FileProcessingService::SIGNAL_PostFileProcess
 * 
 * The signal is emitted everytime after a processed image is rendered. This slot will overwrite the processed file, 
 * if there is any user defined configuration.
 */
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher')->connect(
    'TYPO3\\CMS\\Core\\Resource\\ResourceStorage',
    \TYPO3\CMS\Core\Resource\Service\FileProcessingService::SIGNAL_PostFileProcess,
    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('ONM\\OnmCropimages\\Service\\FileConfigurationService'),
    'postFileProcess'
);




//$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

/*\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher')->connect(
    'TYPO3\\CMS\\Core\\Resource\\ResourceStorage',
    \TYPO3\CMS\Core\Resource\Service\FileProcessingService::SIGNAL_PreFileProcess,
    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('ONM\\OnmCropimages\\Service\\FileConfigurationService'),
    'preFileProcess'
);*/
