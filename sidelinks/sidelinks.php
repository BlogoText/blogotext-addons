<?php

/**
 * Changelog
 *
 * 1.0.0 2017-27-12 thuban
 */
 
$declaration = array(
    // the tag of your addon (required)
    'tag' => 'sidelinks',

    // the name, showed in admin/addon (required)
    'name' => array(
        'en' => 'Side links editor',
        'fr' => 'Éditeur de liens de la barre latérale',
    ),

    // the desc, showed in admin/addon (required)
    'desc' => array(
        'en' => 'Edit links in sidebar',
        'fr' => 'Éditeur des liens présents dans la barre latérale',
    ),
    'settings' => array(
        'links' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Links',
                'fr' => 'Liens'
            ),
            'desc' => array(
                'en' => 'List of links, using BBcode. Ex : [Blogotext|https://blogotext.org] [Yeuxdelibad|https://yeuxdelibad.net]',
                'fr' => 'Liste des liens, écrits en BBcode. Ex : [Blogotext|https://blogotext.org]  [Yeuxdelibad|https://yeuxdelibad.net]',
            ),
            'value' => '[Tous les articles|?liste] [Article au hasard|?random] [Liens|?mode=links] [Blogotext|https://blogotext.org]',
        ),
    ),


    // the version, showed in admin/addon (required)
    'version' => '1.0.0',
    'compliancy' => '3.7',
    'url' => 'https://blogotext.org',
);

function a_sidelinks()
{
    // regex stolen from inc/conv.php
    // Maybe we courl use markup() directly, but it add <p></p> tags...
    $tofind = array(
        /* regex URL     */ '#([^"\[\]|])((http|ftp)s?://([^"\'\[\]<>\s\)\(]+))#i',
        /* a href        */ '#\[([^[]+)\|([^[]+)\]#',
    );
    $toreplace = array(
        /* regex URL     */ '$1<a href="$2">$2</a>',
        /* a href        */ '<a href="$2">$1</a>',
    );


    $html = '<ul>';
    $links = trim(addon_get_setting('sidelinks', 'links'));
    $links = explode(']', $links);
    for ($i = 0; $i < sizeof($links) -1; $i++) { // don't care of last ']'
        $l = $links[$i] . ']';
        $link = preg_replace($tofind, $toreplace, $l);
        $html .= "<li>".$link."</li>\n";
    }
    $html .= '</ul>';
    return $html;
}
