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

$container['system-notification.configuration'] = function () {
    return new ContaoBlackForest\SystemNotification\DependencyInjection\ConfigurationService();
};

$container->extend(
    'system-notification.configuration',
    function ($configurationService) {
        $configurationService->setConfig(
            'standard',
            array(
                'mailHtml',
                'ContaoBlackForest\SystemNotification\Controller\MailController'
            )
        );

        $configurationService->setConfig(
            'standard',
            array(
                'mailText',
                'ContaoBlackForest\SystemNotification\Controller\MailController'
            )
        );

        return $configurationService;
    }
);
