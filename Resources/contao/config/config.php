<?php

/**
 * Copyright © ContaoBlackForest
 *
 * @package   contao-system-notification
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2014-2016 ContaoBlackForest
 */

/**
 * Add the backend module.
 */
$systemModules = array();

foreach ($GLOBALS['BE_MOD']['system'] as $name => $configuration) {
    $systemModules[$name] = $configuration;

    if ($name === 'log') {
        $systemModules['system_notification']['tables'] =
            array('tl_system_notification', 'tl_system_notification_message');
        $systemModules['system_notification']['icon'] ='assets/system-notification/images/backend/error-icon.png';
    }
}

$GLOBALS['BE_MOD']['system'] = $systemModules;


/**
 * Activate the fatal error controller.
 */
new ContaoBlackForest\SystemNotification\Controller\FatalErrorController();
