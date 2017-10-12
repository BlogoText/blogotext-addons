<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

/**
 * Changelog
 *
 * 1.0.0 2017-08-17 thuban
 */

$declaration = array(
    'tag' => 'use_firefox',
    'name' => array(
        'en' => 'Use Firefox',
        'fr' => 'Utilisez Firefox',
    ),
    'desc' => array(
        'en' => 'Display an modal if firefox is not used.',
        'fr' => 'Affiche un avertissement si Firefox n\'est pas utilisé.',
    ),
    'settings' => array(
        'message' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Message',
                'fr' => 'Message'
            ),
            'desc' => array(
                'en' => 'Message to display',
                'fr' => 'Message à afficher',
            ),
            'value' => '⚠ Your browser doesn\'t respect your privacy, you might want to try Firefox.',
        ),
        'dlmessage' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Download message',
                'fr' => 'Message téléchargement'
            ),
            'desc' => array(
                'en' => 'Message to display for download link',
                'fr' => 'Message à afficher pour le lien de téléchargement',
            ),
            'value' => '⬇️ Click to download Firefox now and thank me later 😉',
        ),

    ),


    'version' => '1.0.0',
    'compliancy' => '3.7',
    'css' => 'use_firefox.min.css',
    'js' => 'use_firefox.min.js',
);
