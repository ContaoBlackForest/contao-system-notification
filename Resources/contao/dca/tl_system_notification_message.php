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
$GLOBALS['TL_DCA']['tl_system_notification_message'] = array
(

    // Config
    'config'      => array
    (
        'dataContainer' => 'Table',
        'ptable'        => 'tl_system_notification',
        'notEditable'   => true,
        'notCreatable'  => true,
        'notSortable'   => true,
        'notCopyable'   => true,
        'sql'           => array
        (
            'keys' => array
            (
                'id'  => 'primary',
                'pid' => 'index'
            )
        )
    ),

    // List
    'list'        => array
    (
        'sorting'           => array
        (
            'mode'                  => 4,
            'fields'                => array('tstamp DESC'),
            'headerFields'          => array('title', 'alert'),
            'panelLayout'           => 'limit',
            'child_record_callback' => array(
                'ContaoBlackForest\SystemNotification\DataContainer\SystemNotificationMessage',
                'listMessage'
            )
        ),
        'label'             => array
        (
            'fields'         => array('message'),
            #'format' => '%s <span style="color:#b3b3b3;padding-left:3px">[%s]</span>',
            'format'         => '%s',
            'group_callback' => array(
                'ContaoBlackForest\SystemNotification\DataContainer\SystemNotificationMessage',
                'groupName'
            )
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
            'delete' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['tl_system_notification_message']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm']
                                . '\'))return false;Backend.getScrollOffset()"',
                #'button_callback' => array('tl_system_notification_message', 'deleteArticle')
            )
        )
    ),

    // Select
    /*'select'      => array
    (
        'buttons_callback' => array
        (
            array('tl_system_notification_message', 'addAliasButton')
        )
    ),*/

    // Palettes
    'palettes'    => array
    (
        '__selector__' => array(),
        'default'      => '{message_legend},message'
    ),

    // Subpalettes
    'subpalettes' => array(),

    // Fields
    'fields'      => array
    (
        'id'      => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid'     => array
        (
            'foreignKey' => 'tl_system_notification.title',
            'sql'        => "int(10) unsigned NOT NULL default '0'",
            'relation'   => array('type' => 'belongsTo', 'load' => 'lazy')
        ),
        'tstamp'  => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'message' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_system_notification_message']['message'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'search'    => true,
            'eval'      => array('rte' => 'tinyMCE', 'tl_class' => 'clr'),
            'sql'       => "text NULL"
        )
    )
);
