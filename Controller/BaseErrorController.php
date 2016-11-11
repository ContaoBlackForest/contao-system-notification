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

use Contao\Environment;
use Contao\System;


/**
 * The base error controller.
 */
class BaseErrorController
{
    /**
     * Return the simple tokens.
     *
     * @param array  $notification The notification information.
     *
     * @param array  $error        The error information.
     *
     * @param string $errorMessage The error message.
     *
     *
     * @return array The simple tokens.
     */
    protected function parseSimpleTokens(array $notification, array $error, $errorMessage = '')
    {
        $tokens = array();

        $tokens['error_html']    = $errorMessage;
        $tokens['error_message'] = $error['message'];
        $tokens['error_file']    = $error['file'];
        $tokens['error_line']    = $error['line'];

        $errorType            = array
        (
            E_ERROR             => 'Fatal error',
            E_WARNING           => 'Warning',
            E_PARSE             => 'Parsing error',
            E_NOTICE            => 'Notice',
            E_CORE_ERROR        => 'Core error',
            E_CORE_WARNING      => 'Core warning',
            E_COMPILE_ERROR     => 'Compile error',
            E_COMPILE_WARNING   => 'Compile warning',
            E_USER_ERROR        => 'Fatal error',
            E_USER_WARNING      => 'Warning',
            E_USER_NOTICE       => 'Notice',
            E_STRICT            => 'Runtime notice',
            E_RECOVERABLE_ERROR => 'Recoverable error',
            E_DEPRECATED        => 'Deprecated notice',
            E_USER_DEPRECATED   => 'Deprecated notice'
        );
        $tokens['error_type'] = $errorType[$error['type']];

        System::loadLanguageFile('tl_system_notification');

        $tokens['system_notification_id']    = $notification['id'];
        $tokens['system_notification_title'] = $notification['title'];

        $service                               = explode('.', $notification['service']);
        $tokens['system_notification_service'] =
            $GLOBALS['TL_LANG']['tl_system_notification'][$service[0]] . ' ' .
            $GLOBALS['TL_LANG']['tl_system_notification'][$service[1]];

        $tokens['system_notification_alert_type']   =
            $GLOBALS['TL_LANG']['tl_system_notification'][$notification['alert']];
        $tokens['system_notification_backend_link'] =
            Environment::get('requestScheme') . '://' . Environment::get('host') . '/contao/main.php?' .
            'do=system_notification&table=tl_system_notification_message&id=' . $notification['id'];

        return $tokens;
    }
}
