<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
#
# 2016 Mickaël S. <contact@tiger-222.fr>
# *** LICENSE ***

/**
 * Changelog
 *
 * 1.0.0 2017-01-24 RemRem
 *  - upd version for BT 3.7
 *  - upd addons declaration (config > settings)
 *
 * 0.1.0
 *  2016-11-28 RemRem, maybe need more work
 *  - upd addon to be BT#160 compliant
 *  - fix #12
 *  - upd current version to 0.X (dev version)
 */

$declaration = array(
    'tag' => 'relatedposts',
    'version' => '1.0.0',
    'compliancy' => '3.7',
    'url' => 'http://www.tiger-222.fr/',

    'name' => array(
        'en' => 'Related posts',
        'fr' => 'Articles en relation',
    ),
    'desc' => array(
        'en' => 'Show a list of posts in relation of the current displayed one.',
        'fr' => 'Afficher une liste d\'articles en relation avec celui en cours de lecture.',
    ),

    'settings' => array(
        'nb_posts' => array(
            'type' => 'int',
            'label' => array(
                'en' => 'Number of posts to list',
                'fr' => 'Nombre d\'articles à lister'
            ),
            'value' => 5,
            'value_min' => 1,
            'value_max' => 10,
        ),
        'sentence' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Sentence printed before the post list',
                'fr' => 'Phrase d\'accroche affichée avant la liste des articles'
            ),
            'value' => 'Découvrez d\'autres articles de la même trempe que %s :',
        ),
    ),

    'css' => 'style.css',
);
