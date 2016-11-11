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

use Contao\Controller;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\Versions;

/**
 * The data container system notification.
 */
class SystemNotification
{
    /**
     * Return the alert options.
     *
     * @return array The alert option.
     */
    public function alertOptions()
    {
        return array(
            'fatalError' => $GLOBALS['TL_LANG']['tl_system_notification']['fatalError']
        );
    }

    /**
     * Return the service options from the configuration service.
     *
     * @return array The options.
     */
    public function serviceOptions()
    {
        global $container;

        $configurationService = $container['system-notification.configuration'];

        $configuration = $configurationService->getConfig();
        if (!is_array($configuration)
            || count($configuration) === 0
        ) {
            return array();
        }

        $options = array();
        foreach ($configuration as $group => $items) {
            if (!is_array($items)
                || count($items) === 0
            ) {
                continue;
            }

            $groupName = $group;
            if (array_key_exists('tl_system_notification', $GLOBALS['TL_LANG'])
                && array_key_exists($groupName, $GLOBALS['TL_LANG']['tl_system_notification'])
            ) {
                $groupName = $GLOBALS['TL_LANG']['tl_system_notification'][$group];
            }

            foreach ($items as $item) {
                $itemName = $itemValue = $item[0];

                if (array_key_exists('tl_system_notification', $GLOBALS['TL_LANG'])
                    && array_key_exists($itemName, $GLOBALS['TL_LANG']['tl_system_notification'])
                ) {
                    $itemName = $GLOBALS['TL_LANG']['tl_system_notification'][$itemName];
                }

                $options[$groupName][$group . '.' . $itemValue] = $itemName;
            }
        }

        return $options;
    }

    /**
     * Generate the toggle icon.
     *
     * @param array  $row        The row information.
     *
     * @param string $href       The href.
     *
     * @param string $label      The label.
     *
     * @param string $title      The title.
     *
     * @param string $icon       The icon.
     *
     * @param string $attributes The attributes.
     *
     *
     * @return string The toogle icon.
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen(Input::get('tid'))) {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            Controller::redirect(Controller::getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        /*if (!$this->User->hasAccess('tl_article::published', 'alexf'))
        {
            return '';
        }*/

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . ($row['published'] ? '' : 1);

        if (!$row['published']) {
            $icon = 'invisible.gif';
        }

        /*$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
            ->limit(1)
            ->execute($row['pid']);*/

        /*if (!$this->User->isAllowed(BackendUser::CAN_EDIT_ARTICLES, $objPage->row()))
        {
            return Image::getHtml($icon) . ' ';
        }*/

        return '<a href="' . Controller::addToUrl($href) .
               '" title="' . specialchars($title) . '"' . $attributes . '>' .
               Image::getHtml($icon, $label, 'data-state="' . ($row['published'] ? 1 : 0) . '"') . '</a> ';
    }

    /**
     * Toggle the visible state for the toggle icon.
     *
     * @param integer            $id            The element id.
     *
     * @param integer            $isVisible     The visible state.
     *
     * @param DataContainer|null $dataContainer The data container.
     *
     *
     * @return void
     */
    public function toggleVisibility($id, $isVisible, DataContainer $dataContainer = null)
    {
        Input::setGet('id', $id);
        Input::setGet('act', 'toggle');

        if ($dataContainer) {
            $dataContainer->id = $id;
        }

        #$this->checkPermission();

        /*if (!$this->User->hasAccess('tl_article::published', 'alexf'))
        {
            $this->log('Not enough permissions to publish/unpublish article ID "'.$intId.'"', __METHOD__, TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }*/

        $versions = new Versions('tl_system_notification', $id);
        $versions->initialize();

        /*if (is_array($GLOBALS['TL_DCA']['tl_system_notification']['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_system_notification']['fields']['published']['save_callback'] as $callback)
            {
                if (is_array($callback)) {
                    $this->import($callback[0]);
                    $blnVisible = $this->{$callback[0]}->{$callback[1]}($blnVisible, ($dc ?: $this));
                } elseif (is_callable($callback)) {
                    $blnVisible = $callback($blnVisible, ($dc ?: $this));
                }
            }
        }*/

        // Update the database
        $database = Database::getInstance();

        $database->prepare(
            "UPDATE tl_system_notification SET tstamp=" . time() . ", published='" . ($isVisible ? '1' : '') .
            "' WHERE id=?"
        )->execute($id);

        $versions->create();
    }

}
