<?php

/**
 * Changelog
 *
 * 2.0.0 2017-07-29 RemRem
 *  - upd for BT 3.8
 *
 * 1.0.2 2017-04-19
 *   fix error message when articles not available (/blog/?liste)
 *
 * 1.0.1 2017-04-18
 *   fix some issues with libxml
 *
 * 1.0.0 2017-03-24 thuban with help of RemRem
 *   first release
 */
 
$declaration = array(
    // the tag of your addon (required)
    'tag' => 'lazyload',

    // the name, showed in admin/addon (required)
    'name' => array(
        'en' => 'Lazyload images',
        'fr' => 'lazyload - chargement d\'images à la demande',
    ),

    // the desc, showed in admin/addon (required)
    'desc' => array(
        'en' => 'Load images when in viewport',
        'fr' => 'Chargement des images lorsqu\'elles sont dans le viewport',
    ),

    // the version, showed in admin/addon (required)
    'version' => '1.0.2',
    'compliancy' => '3.7',
    'css' => 'lazyload.css',
    'js' => array('echo.js', 'lazyload.js'),
    'url' => 'http://yeuxdelibad.net',

    'hook-push' => array(
            'list_items' => array(
                    'callback' => 'a_lazy_work_on_content',
                    'priority' => 100
                )
        ),
);
