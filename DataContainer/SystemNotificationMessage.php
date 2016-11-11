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

namespace ContaoBlackForest\SystemNotification\DataContainer;

use Contao\Config;

/**
 * The data container system notification message.
 */
class SystemNotificationMessage
{
    /**
     * Return the row item for list view.
     *
     * @param array $row The row information.
     *
     *
     * @return string The item.
     */
    public function listMessage($row)
    {
        return '<div class="tl_content_left">' . $row['message'] . '</div>';
    }

    /**
     * Return the group name as date format.
     *
     * @param string $group The group name.
     *
     *
     * @return string The group name.
     */
    public function groupName($group)
    {
        $dateTime = new \DateTime();
        $dateTime->setTimestamp($group);

        return $dateTime->format(Config::get('dateFormat'));
    }
}
