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

use Contao\Config;
use Contao\Controller;
use Contao\Email;
use Contao\StringUtil;
use Contao\UserModel;

/**
 * The mail controller who send the system notification.
 */
class MailController
{
    /**
     * Send the error message to the user.
     *
     * @param array $notification The notification information.
     *
     * @param array $tokens The simple tokens.
     *
     *
     * @return void
     */
    public function send(array $notification, array $tokens)
    {
        $message = htmlspecialchars(html_entity_decode($notification['messageHtml']));
        if (explode('.', $notification['service'])[1] === 'mailText') {
            $message = htmlspecialchars(html_entity_decode($notification['messageText']));
        }

        $message = StringUtil::parseSimpleTokens($message, $tokens);
        $message = Controller::replaceInsertTags($message);

        $user = UserModel::findByPk($notification['user']);

        $email = new Email();

        Controller::loadLanguageFile('modules');
        $email->subject = 'Contao ' . $GLOBALS['TL_LANG']['MOD']['system_notification'][0] . ' - ' .
                          Config::get('websiteTitle');

        $email->text = $message;

        $email->sendTo($user->email);
    }
}
