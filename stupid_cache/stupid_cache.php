<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

/**
 * @author    RemRem <>
 * @copyright Copyright (C) RemRem
 * @licence   MIT
 * @version   0.0.1 POC
 *
 * You can redistribute it under the terms of the MIT / X11 Licence.
 */

/**
 * This is a Proof Of Concept
 * This addon can have some bugs and may not be optimised
 *
 * DO NO USE IN PROD !
 * REALLY !
 *
 * 0.0.1
 *  - init
 *  - put in cache
 *  - get from cache
 *  - cache TTL
 */

$GLOBALS['addons'][] = array(
    'tag' => 'stupid_cache',
    'name' => array(
        'en' => 'Stupid Cache (POC - just for test)',
        'fr' => 'Stupid Cache (POC - juste pour test)',
    ),
    'desc' => array(
        'en' => 'POC - Stupid Cache - do not use in production ! - just for blog articles',
        'fr' => 'POC - Stupid Cache - ne pas utiliser en production ! - juste pour les articles',
    ),
    'url' => 'https://github.com/remrem/blogotext_light_seo',
    'version' => '0.0.1',

    'config' => array(
                'cache_ttl' => array(
                        'type' => 'int',
                        'label' => array(
                            'en' => 'FYI - Cache TTL (in sec)',
                            'fr' => 'Pour votre information - Durée du cache (en sec)'
                        ),
                        'value' => 60,
                        'value_min' => 59,
                        'value_max' => 61,
                    ),
            )
);



/**
 * set hooks
 */
hook_push('system-start', 'addon_stupid_cache_at_start', 100);

/**
 * functions
 */

function addon_stupid_cache_at_start()
{
    // don't want use cache ?
    if (isset($_GET['no-stupid-cache'])){
        return true;
    }

    // can be cached ?
    if (isset($_GET['d']) and preg_match('#^\d{4}/\d{2}/\d{2}/\d{2}/\d{2}/\d{2}#', $_GET['d'])) {
        $tab = explode('/', $_GET['d']);
        $id = substr($tab['0'].$tab['1'].$tab['2'].$tab['3'].$tab['4'].$tab['5'], '0', '14');
        $path = BT_ROOT.DIR_ADDONS .'/stupid_cache/cache/'.$tab['0'].$tab['1'].'/'.$tab['2'].$tab['3'].$tab['4'].$tab['5'].'.cache.html';

        addon_stupid_cache_get_from_cache($path);
        addon_stupid_cache_put_url_in_cache($path);
    }
    // doesn't matter ;)
    return true;
}

function addon_stupid_cache_get_from_cache($path)
{
    if (!file_exists($path)){
        return false;
    }
    if ((time()-filemtime($path)) > 60) {
        return false;
    }
    $cached = file_get_contents($path);
    if ($cached !== false){
        global $begin;
        echo $cached;
        $end = microtime(true);
        echo '<!-- stupid_cache : from cache in '.round(($end - $begin), 6).' seconds ;) -->';
        exit();
    }
    return false;
}

// it's dirty ... mehhhh ... POC
function addon_stupid_cache_put_url_in_cache($path)
{
    $url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}&no-stupid-cache";

    $opts = array(
        'http'=>array(
            'method'=>"GET",
            'header'=>"Accept-language: fr",
            'timeout' => 1 
        )
    );

    $context = stream_context_create($opts);

    $content = file_get_contents($url, false, $context);

    // todo : check 404
    if ($content === false) {
        return false;
    }

    $folder = dirname($path);
    if (!is_dir($folder)) {
        if (mkdir($folder, 0777, true) === true) {
            // blogotext function, todo : remove this dependancy
            fichier_index($folder);
            // blogotext function, todo : remove this dependancy
            fichier_htaccess($folder); // to prevent direct access to files
        } else {
            echo $content;
            exit();
        }
    }

    echo $content;
    if (file_put_contents($path, $content) === false) {
        echo '<!-- stupid_cache : fail to put in cache -->';
    } else {
        echo '<!-- stupid_cache : putted in cache -->';
    }
    exit();
}
