<?php

$extensionPath = t3lib_extMgm::extPath('onm_cropimages');

$load = array(
    'ONM\\OnmCropimages\\Service\\FileConfigurationService' => $extensionPath . 'Classes/Service/FileConfigurationService.php'
);

if (TYPO3_MODE=='BE') {
	$load['ONM\\OnmCropimages\\Backend\\Controller\\OptionsController'] = $extensionPath . 'Classes/Backend/Controller/OptionsController.php';
}

return $load;