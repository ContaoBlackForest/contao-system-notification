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
 * Table tl_system_notification
 */
$GLOBALS['TL_DCA']['tl_system_notification'] = array
(
    // Config
    'config'      => array
    (
        'dataContainer'    => 'Table',
        'ctable'           => array('tl_system_notification_message'),
        'switchToEdit'     => true,
        'enableVersioning' => true,
        'sql'              => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
    ),

    // List
    'list'        => array
    (
        'sorting'           => array
        (
            'mode'        => 1,
            'fields'      => array('title'),
            'flag'        => 1,
            'panelLayout' => 'filter;search,limit'
        ),
        'label'             => array
        (
            'fields' => array('title'),
            'format' => '%s'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations'        => array
        (
            'edit'       => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_system_notification']['edit'],
                'href'  => 'table=tl_system_notification_message',
                'icon'  => 'edit.gif'
            ),
            'editheader' => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_system_notification']['editHeader'],
                'href'  => 'act=edit',
                'icon'  => 'header.gif',
                #'button_callback'     => array('tl_system_notification', 'editHeader')
            ),
            'copy'       => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_system_notification']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif',
            ),
            'delete'     => array
            (
                'label'      => &$GLOBALS['TL_LANG']['tl_system_notification']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' =>
                    'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))' .
                    'return false;Backend.getScrollOffset()"',
            ),
            'toggle'     => array
            (
                'label'           => &$GLOBALS['TL_LANG']['tl_system_notification']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => array(
                    'ContaoBlackForest\SystemNotification\DataContainer\SystemNotification',
                    'toggleIcon'
                )
            ),
            'show'       => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_system_notification']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes'    => array
    (
        '__selector__' => array('published', 'service'),
        'default'      => '{title_legend},title;{config_legend},alert,service;{publish_legend},published'
    ),

    // Subpalettes
    'subpalettes' => array(
        'published'                 => 'start,stop',
        'service_standard.mailHtml' => 'user,messageHtml',
        'service_standard.mailText' => 'user,messageText'
    ),

    // Fields
    'fields'      => array
    (
        'id'          => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp'      => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'title'       => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_system_notification']['title'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('mandatory' => true, 'maxlength' => 255),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'alert'       => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_system_notification']['alert'],
            'exclude'          => true,
            'search'           => true,
            'inputType'        => 'select',
            'flag'             => 11,
            'eval'             => array(
                'doNotCopy'          => true,
                'mandatory'          => true,
                'chosen'             => true,
                'includeBlankOption' => true,
                'tl_class'           => 'w50'
            ),
            'options_callback' =>
                array('ContaoBlackForest\SystemNotification\DataContainer\SystemNotification', 'alertOptions'),
            'sql'              => "varchar(64) NOT NULL default ''"
        ),
        'service'     => array
        (
            'label'            => &$GLOBALS['TL_LANG']['tl_system_notification']['service'],
            'exclude'          => true,
            'search'           => true,
            'inputType'        => 'select',
            'flag'             => 11,
            'eval'             => array(
                'doNotCopy'          => true,
                'mandatory'          => true,
                'chosen'             => true,
                'includeBlankOption' => true,
                'submitOnChange'     => true,
                'tl_class'           => 'w50 clr'
            ),
            'options_callback' =>
                array('ContaoBlackForest\SystemNotification\DataContainer\SystemNotification', 'serviceOptions'),
            'sql'              => "varchar(64) NOT NULL default ''"
        ),
        'user'        => array
        (
            'label'      => &$GLOBALS['TL_LANG']['tl_system_notification']['user'],
            'default'    => BackendUser::getInstance()->id,
            'exclude'    => true,
            'search'     => true,
            'inputType'  => 'select',
            'foreignKey' => 'tl_user.name',
            'eval'       => array(
                'doNotCopy'          => true,
                'mandatory'          => true,
                'chosen'             => true,
                'includeBlankOption' => true,
                'tl_class'           => 'w50'
            ),
            'sql'        => "int(10) unsigned NOT NULL default '0'",
            'relation'   => array('type' => 'hasOne', 'load' => 'eager')
        ),
        'messageHtml' => array
        (
            'label'       => &$GLOBALS['TL_LANG']['tl_system_notification']['messageHtml'],
            'exclude'     => true,
            'inputType'   => 'textarea',
            'explanation' => 'systemNotificationSimpleTokens',
            'search'      => true,
            'eval'        => array('rte' => 'tinyMCE', 'tl_class' => 'clr', 'helpwizard' => true),
            'sql'         => "text NULL"
        ),
        'messageText' => array
        (
            'label'       => &$GLOBALS['TL_LANG']['tl_system_notification']['messageText'],
            'exclude'     => true,
            'inputType'   => 'textarea',
            'explanation' => 'systemNotificationSimpleTokens',
            'search'      => true,
            'eval'        => array('tl_class' => 'clr', 'helpwizard' => true),
            'sql'         => "text NULL"
        ),
        'published'   => array
        (
            'exclude'   => true,
            'label'     => &$GLOBALS['TL_LANG']['tl_system_notification']['published'],
            'inputType' => 'checkbox',
            'eval'      => array('submitOnChange' => true, 'doNotCopy' => true),
            'sql'       => "char(1) NOT NULL default ''"
        ),
        'start'       => array
        (
            'exclude'   => true,
            'label'     => &$GLOBALS['TL_LANG']['tl_system_notification']['start'],
            'inputType' => 'text',
            'eval'      => array('rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
            'sql'       => "varchar(10) NOT NULL default ''"
        ),
        'stop'        => array
        (
            'exclude'   => true,
            'label'     => &$GLOBALS['TL_LANG']['tl_system_notification']['stop'],
            'inputType' => 'text',
            'eval'      => array('rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
            'sql'       => "varchar(10) NOT NULL default ''"
        )
    )
);
