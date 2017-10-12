<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

/**
 * Changelog
 *
 * 1.0.0 2017-01-24 RemRem
 *  - upd version for BT 3.7
 *
 * 0.1.0
 *  2016-11-28 RemRem, maybe need more work
 *  - upd addon to be BT#160 compliant
 *  - fix #12
 *  - upd current version to 0.X (dev version)
 */

$declaration = array(
    'tag' => 'readmore',
    'name' => array(
        'en' => 'Read more',
        'fr' => 'Autres articles',
    ),
    'desc' => array(
        'en' => 'List 3 "read-also like" thumbnails below each post.',
        'fr' => 'Afficher des image d\'autres articles.',
    ),
    'version' => '1.0.0',
    'compliancy' => '3.7',
    'css' => 'style.css',

    'settings' => array(
        'nb_posts' => array(
            'type' => 'int',
            'label' => array(
                'en' => 'Number of posts to list',
                'fr' => 'Nombre d\'articles à lister'
            ),
            'value' => 4,
            'value_min' => 1,
            'value_max' => 8,
        ),
    ),
);
