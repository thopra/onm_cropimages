<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_onmcropimages_domain_model_options'] = array(
	'ctrl' => $TCA['tx_onmcropimages_domain_model_options']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, width, height, crop_x, crop_y, crop_width, crop_height, file',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, width, height, crop_x, crop_y, crop_width, crop_height, file,--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_onmcropimages_domain_model_options',
				'foreign_table_where' => 'AND tx_onmcropimages_domain_model_options.pid=###CURRENT_PID### AND tx_onmcropimages_domain_model_options.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'width' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:onm_cropimages/Resources/Private/Language/locallang_db.xlf:tx_onmcropimages_domain_model_options.width',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			),
		),
		'height' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:onm_cropimages/Resources/Private/Language/locallang_db.xlf:tx_onmcropimages_domain_model_options.height',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'crop_x' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:onm_cropimages/Resources/Private/Language/locallang_db.xlf:tx_onmcropimages_domain_model_options.crop_x',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int,required'
			),
		),
		'crop_y' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:onm_cropimages/Resources/Private/Language/locallang_db.xlf:tx_onmcropimages_domain_model_options.crop_y',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			),
		),
		'crop_width' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:onm_cropimages/Resources/Private/Language/locallang_db.xlf:tx_onmcropimages_domain_model_options.crop_width',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'crop_height' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:onm_cropimages/Resources/Private/Language/locallang_db.xlf:tx_onmcropimages_domain_model_options.crop_height',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'file' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:onm_cropimages/Resources/Private/Language/locallang_db.xlf:tx_onmcropimages_domain_model_options.file',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_core_resource_file',
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
	),
);

?>