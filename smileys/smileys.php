<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

$GLOBALS['addons'][] = array(
    'tag' => 'smileys',
    'name' => array(
        'en' => 'Smileys',
        'fr' => 'Émoticônes',
    ),
    'desc' => array(
        'en' => 'Convert smileys strings into emoticons. i.e. : ";)" -> "😉".',
        'fr' => 'Convertit des smileys en émojis. ex : ";)" -> "😉".',
    ),
    'url' => 'http://yeuxdelibad.net',
    'version' => '1.0.0',
);

function addon_smileys()
{
    $html="<script src='addons/smileys/smileys.js'></script>";
    return $html;
}
