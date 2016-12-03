<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

/**
 * @author    RemRem <>
 * @copyright Copyright (C) RemRem
 * @licence   MIT
 * @version   0.0.5 POC
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
 * 0.0.5
 *  - juste pour tester BT/core/addon et le changement de version d'un module
 *
 * 0.0.4
 *  - add dev mod
 *  - upd maj pour le core de BT
 *  - upd renomage des functions interne pour respecter la convention de nommage des functions des addons au sein d'un addon, histoire d'éviter les éventuels conflits de nom de function entre les functions du core et les function des addons ... :D (je peux continuer longtemps...)
 *
 * 0.0.3
 *  - Quelques modifications bien dégueulasses pour tester le PR#139 de BT
 *    This IS POOOOCCCCC !
 *
 * 0.0.2
 *  - put hook-push in $GLOBALS['addons'][]
 *  - change $GLOBALS['addons'][]['configs'] to $GLOBALS['addons'][]['settings']
 *  - use the new BlogoText fn addon_get_vhost_cache_path()
 *  - swith the custom create dir script to the BlogoText fn create_folder()
 *
 * 0.0.1
 *  - init
 *  - put in cache
 *  - get from cache
 *  - cache TTL
 */

$declaration = array(
    'tag' => 'stupid_cache',
    'name' => array(
        'en' => 'Stupid Cache (POC - just for test)',
        'fr' => 'Stupid Cache (POC - juste pour test)',
    ),
    'desc' => array(
        'en' => 'POC - Stupid Cache - do not use in production ! - just for blog articles',
        'fr' => 'POC - Stupid Cache - ne pas utiliser en production ! - juste pour les articles',
    ),
    'version' => '0.0.5',
    'compliancy' => '3.7',

    'url' => 'https://github.com/remrem/blogotext_light_seo',

    'settings' => array(
            'debug_mode' => array(
                    'type' => 'bool',
                    'label' => array(
                            'en' => 'debug mode',
                            'fr' => 'debug mode'
                        ),
                    'value' => false,
                ),
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
        ),

    'hook-push' => array(
            'system-start' => array(
                    'callback' => 'a_stupid_cache_at_start',
                    'priority' => 100
                )
        ),
    'buttons' => array(
            'hellow-world' => array(
                    'callback' => 'a_stupid_cache_hello',
                    'label' => array(
                            'en' => 'just a test',
                            'fr' => 'juste un test'
                        ),
                    'desc' => array(
                            'en' => 'EN - Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... ',
                            'fr' => 'FR - Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... '
                        ),
                )
        )
);



/**
 * functions
 */

function a_stupid_cache_hello()
{
    echo '<pre>Hello world!</pre>';
}

function a_stupid_cache_at_start()
{
    $debug_mode = (bool)addon_get_setting('stupid_cache', 'debug_mode');

    // for dev testing
    if ($debug_mode == true) {
        echo '<pre>Stupid cache > Ok : Started</pre>';
    }

    // don't want use cache ?
    if (isset($_GET['no-stupid-cache'])) {
        // for dev testing
        if ($debug_mode == true) {
            // echo '<pre>Stupid cache > no cache wanted</pre>';
        }
        return true;
    }

    // can be cached ?
    if (isset($_GET['d']) and preg_match('#^\d{4}/\d{2}/\d{2}/\d{2}/\d{2}/\d{2}#', $_GET['d'])) {
        $tab = explode('/', $_GET['d']);
        $id = substr($tab['0'].$tab['1'].$tab['2'].$tab['3'].$tab['4'].$tab['5'], '0', '14');
        $path = addon_get_vhost_cache_path('stupid_cache');
        if ($path === false) {
            if ($debug_mode == true) {
                echo '<pre>Stupid cache > cannot get the cache path</pre>';
            }
            return false;
        }
        $path .= '/'.$tab['0'].$tab['1'].'/'.$tab['2'].$tab['3'].$tab['4'].$tab['5'].'.cache.html';

        a_stupid_cache_get_from_cache($path, $debug_mode);
        a_stupid_cache_put_url_in_cache($path, $debug_mode);
    }

    // doesn't matter ;)
    return true;
}

function a_stupid_cache_get_from_cache($path, $debug_mode)
{
    // for dev testing
    if ($debug_mode == true) {
        echo '<pre>Stupid cache > From cache</pre>';
    }

    if (!file_exists($path)) {
        // for dev testing
        if ($debug_mode == true) {
            echo '<pre>Stupid cache > no cache file</pre>';
        }
        return false;
    }
    if ((time()-filemtime($path)) > 60) {
        // for dev testing
        if ($debug_mode == true) {
            echo '<pre>Stupid cache > cache file expired</pre>';
        }
        return false;
    }
    $cached = file_get_contents($path);
    if ($cached !== false) {
        global $begin;

        // for dev testing
        if ($debug_mode == true) {
            echo '<pre>Stupid cache > get from cache</pre>';
        }

        echo $cached;
        $end = microtime(true);
        echo '<!-- stupid_cache : from cache in '.round(($end - $begin), 6).' seconds ;) -->';
        exit();
    }

    // for dev testing
    if ($debug_mode == true) {
        echo '<pre>Stupid cache > fail to get cache</pre>';
    }

    return false;
}

// it's dirty ... mehhhh ... POC
function a_stupid_cache_put_url_in_cache($path, $debug_mode)
{

    // for dev testing
    if ($debug_mode == true) {
        echo '<pre>Stupid cache > put in cache</pre>';
    }

    $url = 'http'. (isset($_SERVER['HTTPS']) ? 's' : '') .'://'."{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}&no-stupid-cache";

    $opts = array(
        'http' => array(
            'method' => "GET",
            'header' => "Accept-language: fr",
            'timeout' => 1
        )
    );

    $context = stream_context_create($opts);
    $content = @file_get_contents($url, false, $context);

    // todo : check 404
    if ($content === false) {
        // for dev testing
        if ($debug_mode == true) {
            echo '<pre>Stupid cache > fail to get page throught http</pre>';
        }
        return false;
    }

    $folder = dirname($path);
    if (!is_dir($folder) && !create_folder($folder, false, true)) {
        // for dev testing
        if ($debug_mode == true) {
            echo '<pre>Stupid cache > cache folder doesn\'t exists</pre>';
        }
        echo $content;
        exit();
    }

    echo $content;
    if (file_put_contents($path, $content, LOCK_EX) === false) {
        // for dev testing
        if ($debug_mode == true) {
            echo '<pre>Stupid cache > fail to put in cache</pre>';
        }
        echo '<!-- stupid_cache : fail to put in cache -->';
    } else {
        // for dev testing
        if ($debug_mode == true) {
            echo '<pre>Stupid cache > putted in cache</pre>';
        }
        echo '<!-- stupid_cache : putted in cache -->';
    }
    exit();
}
