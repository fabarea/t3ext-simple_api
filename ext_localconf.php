<?php

defined('TYPO3_MODE') || die();

(static function (string $_EXTKEY) {
    // Register API provider
    $settings = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS'][$_EXTKEY];
    $eIDName = trim($settings['eIDName']);
    if (!empty($eIDName)) {
        $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include'][$eIDName] = \Causal\SimpleApi\Controller\EidController::class . '::start';
    }

    /*****************************************************
     * API Caching
     *****************************************************/

    if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$_EXTKEY])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$_EXTKEY] = [];
    }
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$_EXTKEY]['backend'] = \Causal\SimpleApi\Cache\Backend\Typo3DatabaseNoFlushBackend::class;

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\Causal\SimpleApi\Task\AsynchronousClearCacheTask::class] = [
        'extension' => $_EXTKEY,
        'title' => 'Clear Simple API cache asynchronously',
        'description' => 'This task will process asynchronous cache clearing operations. This is only useful if you have a load-balanced setup with files being copied with some delay.',
    ];
})('simple_api');
