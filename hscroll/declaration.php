<?php

/**
 * Changelog
 *
 * 2.0.0 2017-07-29 RemRem
 *  - upd for BT 3.8
 *
 * 1.0.0 2017-01-24 RemRem
 *  - upd version for BT 3.7
 */
 
$declaration = array(
    // the tag of your addon (required)
    'tag' => 'hscroll',

    // the name, showed in admin/addon (required)
    'name' => array(
        'en' => 'Horizontal scrollprogress',
        'fr' => 'Indicateur de lecture',
    ),

    // the desc, showed in admin/addon (required)
    'desc' => array(
        'en' => 'Horizontal reading progressbar',
        'fr' => 'Indicateur horizontal de lecture. Pensez au code d\'intégration',
    ),
    'settings' => array(
        'barcolor' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Color',
                'fr' => 'Couleur'
            ),
            'desc' => array(
                'en' => 'Color of scroll-bar',
                'fr' => 'Couleur de la barre de progression',
            ),
            'value' => '#7C00FF',
        ),
    ),
    'version' => '2.0.0',
    'compliancy' => '3.7',
    'css' => 'hscroll.css',
    'js' => 'hscroll.js',
    'url' => 'http://www.tiger-222.fr/?d=2016/10/18/14/00/25-scrollbar-horizontale',
);
