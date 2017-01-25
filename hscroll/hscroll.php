<?php

/**
 * Changelog
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
        'fr' => 'Indicateur horizontal de lecture',
    ),

    // the version, showed in admin/addon (required)
    'version' => '1.0.0',
    'compliancy' => '3.7',
    'css' => 'hscroll.css',
    'js' => 'hscroll.js',
    'url' => 'http://www.tiger-222.fr/?d=2016/10/18/14/00/25-scrollbar-horizontale',
);

function a_hscroll()
{
    $html = '<div id="scroll-bar"><div id="scroll-bar-inner"></div></div>';
    return $html;
}
