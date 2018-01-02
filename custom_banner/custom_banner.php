<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

/**
 * Changelog
 *
 * 0.1.0 2018-01-02 thuban
 */

$declaration = array(
    'tag' => 'custom_banner',
    'name' => array(
        'en' => 'Banner image',
        'fr' => 'Image bannière',
    ),
    'desc' => array(
        'en' => 'Change default theme image',
        'fr' => 'Modifie l\'image d\'en-tête du thème par défaut.',
    ),
    'version' => '1.0.0',
    'compliancy' => '3.7',
    'css' => 'style.php',
    'settings' => array(
        'url' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Image location',
                'fr' => 'Emplacement de l\'image'
            ),
            'desc' => array(
                'en' => 'Must be a valid URL...',
                'fr' => 'Doit être une URL valide...',
            ),
            'value' => 'https://source.unsplash.com/daily' // default value
        ),
    ),
    'hook-push' => array(
            'conversion_theme_addons_end' => array(
                    'callback' => 'a_custom_banner',
                    'priority' => 100
                )
        ),

);


function a_custom_banner($datas)
{
    if (!$datas || !is_array($datas)) {
        return $datas;
    }
    $url = addon_get_setting('custom_banner', 'url');
    $change = '
    <style>
    #head-main-wrapper > header {background: transparent url(\''.$url.'\') no-repeat scroll center center / cover;}"
    </style>
    ';

    $pos = strpos($datas['1'], '</head>');
    $datas['1'] = substr_replace($datas['1'], $change, $pos, 0);
    return $datas;
}
