<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

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
    'tag' => 'latex',
    'name' => array(
        'en' => 'Write LaTeX code with KaTeX',
        'fr' => 'Ecrivez du LaTeX avec KaTeX',
    ),
    'desc' => array(
        'en' => 'Write LaTeX code between \'$\' or \'$$\' : $$\pi=3.14$$',
        'fr' => 'Vous pouvez écrire du LaTeX entre les symboles \'$\' ou \'$$\' : $$\pi=3,14$$',
    ),
    'url' => 'https://khan.github.io/KaTeX/',
    'version' => '2.0.0',
    'compliancy' => '3.7',
    'css' => 'katex.min.css',
    'js' => array('katex.min.js', 'auto-render.min.js', 'katex-config.js'),
);
