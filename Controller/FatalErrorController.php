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

namespace ContaoBlackForest\SystemNotification\Controller;

use Contao\Database;
use Contao\Environment;

/**
 * The fatal error controller.
 */
class FatalErrorController extends BaseErrorController
{
    /**
     * FatalErrorController constructor.
     */
    public function __construct()
    {
        register_shutdown_function(array($this, 'handle'));
    }

    /**
     * Handle the fatal error.
     */
    public function handle()
    {
        $error = error_get_last();
        if ($error['type'] !== E_ERROR) {
            return;
        }

        $database = Database::getInstance();

        $time         = time();
        $notification = $database->prepare(
            "SELECT * FROM tl_system_notification 
                WHERE (start='' OR start<='$time')
                    AND (stop='' OR stop>'$time')
                    AND alert='fatalError'
                    AND published=?
            "
        )->execute(1);

        if ($notification->count() < 1) {
            return;
        }

        $request = Environment::get('requestUri');
        if ($request[0] === '/') {
            $request = substr($request, 1);
        }

        $referer = Environment::get('httpReferer');

        $url = $referer . $request;
        if (TL_MODE === 'FE') {
            global $objPage;

            $url = $objPage->getAbsoluteUrl();
        }

        $errorMessage = '<strong>URL:</strong> <a target="_blank" href="' . $url . '">' . $url . '</a>';
        $errorMessage .= '<p></p>';
        $errorMessage .= sprintf(
            '<strong>%s</strong>: %s in <strong>%s</strong> on line <strong>%s</strong>',
            'Fatal error',
            $error['message'],
            str_replace(TL_ROOT . DIRECTORY_SEPARATOR, '', $error['file']),
            $error['line']
        );

        global $container;

        $configurationService = $container['system-notification.configuration'];
        $configuration        = $configurationService->getConfig();

        while ($notification->next()) {
            $database->prepare(
                'INSERT INTO tl_system_notification_message (pid, tstamp, message) VALUES (?, ?, ?)'
            )->execute($notification->id, time(), $errorMessage);

            $notificationService = explode('.', $notification->service);

            foreach ($configuration[$notificationService[0]] as $service) {
                if ($service[0] !== $notificationService[1]) {
                    continue;
                }

                $notificationClass = new $service[1]();
                $notificationClass->send(
                    $notification->row(),
                    $this->parseSimpleTokens($notification->row(), $error, $errorMessage)
                );
            }
        }
    }
}
