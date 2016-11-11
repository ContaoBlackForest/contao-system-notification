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

namespace ContaoBlackForest\SystemNotification\DependencyInjection;

/**
 * The service for configure system notification.
 */
class ConfigurationService
{
    /**
     * The configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * Return the configuration.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set configuration in this service.
     *
     * @param string $group         The group name.
     *
     * @param array  $configuration The configuration array.
     *                              array(
     *                              'name_for_notification' => 'class_handle_notification'
     *                              )
     *
     *
     * @return void
     */
    public function setConfig($group, array $configuration)
    {
        $this->config[$group][] = $configuration;
    }
}
