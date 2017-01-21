<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

$declaration = array(
    'tag' => 'highlight',

    'name' => array(
        'en' => 'Code coloration',
        'fr' => 'Code coloration',
    ),

    // the desc, showed in admin/addon (required)
    'desc' => array(
        'en' => 'Code coloration with highlight.js',
        'fr' => 'Du code en couleur avec highlight.js',
    ),

    // the version, showed in admin/addon (required)
    'version' => '0.1.0',
    'compliancy' => '3.7',

    'css' => 'highlight.css',
    'js' => array('highlight.min.js', 'start_highlight.js'),
    'url' => 'https://highlightjs.org/',
);
