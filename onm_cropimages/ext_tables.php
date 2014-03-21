<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Crop Images');

/**
 * Register the clickmenu in filelist and the backend module itself
 * --
 */

if (TYPO3_MODE=='BE') {

	// Clickmenu (Filelist module)
    $GLOBALS['TBE_MODULES_EXT']['xMOD_alt_clickmenu']['extendCMclasses'][] = array(
        'name' => 'ONM\\OnmCropimages\\Backend\\Clickmenu',
        'path' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Backend/Clickmenu.php'
    );

    // .. and we require our own backend module
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'ONM.'.$_EXTKEY,
		'file',	 // Make module a submodule of 'file'
		'cropimages',	// Submodule key
		'',						// Position
		array(
			'ProcessedFile' => 'list,listFolder',
			'Options' => 'edit,update,new,show,create'
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml',
		)
	);

	
	// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule('file', 'cropimages', '', \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'mod1/');


}


/**
 * We don't want the records to be visible to a user in the LISt module (at least, for now).
 * Access should only be possible through the backend mpodule. That means we have to hide this table in the TCA. That might change in the future.
 * --
*/

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_onmcropimages_domain_model_options', 'EXT:onm_cropimages/Resources/Private/Language/locallang_csh_tx_onmcropimages_domain_model_options.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_onmcropimages_domain_model_options');
$TCA['tx_onmcropimages_domain_model_options'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:onm_cropimages/Resources/Private/Language/locallang_db.xlf:tx_onmcropimages_domain_model_options',
		'hideTable' => true, //this hides the table for backend users
		'label' => 'width',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'width,height,crop_x,crop_y,crop_width,crop_height,file,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Options.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_onmcropimages_domain_model_options.gif'
	),
);


//Bug in Extbase: checks access of page with _GP('id'), wich is converted to an integer. the file tree uses this parameter aswell
// Workaround: overwrite the ModuleRunner via XCLASS
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Extbase\\Core\\ModuleRunner'] = array(
    'className' => 'ONM\\OnmCropimages\\Xclass\\ModuleRunner',
);



?>