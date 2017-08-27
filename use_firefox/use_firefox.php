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
    'css' => 'use_firefox.css',
    'js' => 'use_firefox.js',
);

function a_use_firefox()
{
    $msg = addon_get_setting('use_firefox', 'message');
    $dlmsg = addon_get_setting('use_firefox', 'dlmessage');
    $html = 'lala';

    $html = '<div id="use_ffx_modal" class="use_ffx_modal">
    <div class="use_ffx_modal-content">
        <div class="use_ffx_modal-header">
            <span class="use_ffx_close">&times;</span>
            <h2>'.$msg.'</h2>
        </div>
        <div class="use_ffx_modal-body">
            <p><a target="_blank" href="https://www.mozilla.org/en-US/firefox/new/" title="get firefox">'.$dlmsg.'</a></p>
        </div>
    </div>
</div>';
    return $html;
}
