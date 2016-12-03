<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

/**
 * @author    RemRem <>
 * @copyright Copyright (C) RemRem
 * @licence   MIT
 * @version   0.0.13 POC
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
 * 0.0.13
 *  - add some stuff
 *
 * 0.0.12
 *  - upd conforme PR #160
 *  - add debug mode
 *
 * 0.0.11
 *  - upd addon_light_seo_work_on_content() for new feed url
 *  - upd $GLOBALS['addons'][] > $declaration
 *  - add $declaration['compliancy']
 *
 * 0.0.10
 *  - update for PR#139 of BT
 *  - put hook-push in $GLOBALS['addons'][]
 *  - change $GLOBALS['addons'][]['configs'] to $GLOBALS['addons'][]['settings']
 *
 * 0.0.9
 *  - add test for config > [ use_url_rewrite | use_sitemap] witch allow
 *    or not some part of the plugin
 *  - some URL added to addon_light_seo_work_on_content()
 *  - add some options (sitemap TTL)
 *
 * 0.0.8
 *  - some URL correction on addon_light_seo_work_on_content()
 *    cache gravatar
 *
 * 0.0.7
 *  - some URL correction on addon_light_seo_work_on_content()
 *
 * 0.0.6
 *  - some changes (depends to hook system)
 *
 * 0.0.5
 *  - some bugs fix
 *  - performance ;)
 */



$declaration = array(
    'tag' => 'light_seo',
    'name' => array(
        'en' => 'Light SEO (POC - just for test)',
        'fr' => 'Light SEO (POC - juste pour test)',
    ),
    'desc' => array(
        'en' => 'POC - Light SEO, do not use in production ! With URL rewrite and sitemap.',
        'fr' => 'POC - Light SEO, ne pas utiliser en production ! Avec réécriture d\'URL et sitemap.',
    ),
    'url' => 'https://github.com/remrem/blogotext_light_seo',
    'version' => '0.0.12',
    'compliancy' => '3.7',
    'settings' => array(
            'debug_mode' => array(
                    'type' => 'bool',
                    'label' => array(
                            'en' => 'debug mode',
                            'fr' => 'debug mode'
                        ),
                    'value' => false,
                ),
            'use_url_rewrite' => array(
                    'type' => 'bool',
                    'label' => array(
                                'en' => 'Use URL rewrite',
                                'fr' => 'Utiliser la réécriture d\'URL'
                            ),
                    'value' => false,
                ),
            /* coming soon
            'url_article' => array(
                    'type' => 'text',
                    'label' => array(
                        'en' => 'Url for articles<br/><small>(no space, special chars, will be like "\article\")</small>',
                        // 'fr' => 'Url des articles<br/><small>(sans espaces, ni caractere spéciaux, resemblera à "\article\")</small>'
                    ),
                    'value' => 'article',
                ),
            'url_link' => array(
                    'type' => 'text',
                    'label' => array(
                        'en' => 'Url for link<br/><small>(no space, special chars, will be like "\link\")</small>',
                        'fr' => 'Url des link<br/><small>(sans espaces, ni caractere spéciaux, resemblera à "\link\")</small>'
                    ),
                    'value' => 'link',
                ),
            'url_tag' => array(
                    'type' => 'text',
                    'label' => array(
                        'en' => 'Url for tag<br/><small>(no space, special chars, will be like "\tag\")</small>',
                        'fr' => 'Url des tags<br/><small>(sans espaces, ni caractere spéciaux, resemblera à "\tag\")</small>'
                    ),
                    'value' => 'link',
                ),
            'use_sitemap' => array(
                    'type' => 'bool',
                    'label' => array(
                                'en' => '(not available) Use a sitemap &amp; robots.txt',
                                'fr' => '(pas disponible) Utiliser un sitemap &amp; robots.txt'
                            ),
                    'value' => false,
                ),
            'sitemap_ttl' => array(
                    'type' => 'int',
                    'label' => array(
                        'en' => '(not available) Refresh sitemap at least after (sec)',
                        'fr' => '(pas disponible) Rafraichir le sitemap aprés (sec)'
                    ),
                    'value' => 86400,
                    'value_min' => 60,
                    'value_max' => (86400*31),
                ),
            */
        ),
    'hook-push' => array(
            'system-start' => array(
                    'callback' => 'a_light_seo_hook_at_start',
                    'priority' => 200
                ),
            'before_show_rss_no_cache' => array(
                    'callback' => 'a_light_seo_hook_article_converter',
                    'priority' => 200
                ),
            'before_show_atom_no_cache' => array(
                    'callback' => 'a_light_seo_hook_article_converter',
                    'priority' => 200
                ),
            'list_items' => array(
                    'callback' => 'a_light_seo_hook_article_converter',
                    'priority' => 200
                ),
            'show_index' => array( // ?
                    'callback' => 'a_light_seo_hook_work_on_content',
                    'priority' => 200
                ),
            'conversion_theme_addons_end' => array(
                    'callback' => 'a_light_seo_hook_work_on_content',
                    'priority' => 200
                ),
            'before_redirection' => array(
                    'callback' => 'a_light_seo_hook_before_redirection',
                    'priority' => 200
                ),
        ),
    'buttons' => array(
            'return-an-array-true' => array(
                    'callback' => 'a_light_seo_return_array_true',
                    'label' => array(
                            'en' => 'Return an array (true)',
                            // 'fr' => 'S'
                        ),
                    'desc' => array(
                            'en' => 'EN - Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... ',
                            // 'fr' => 'FR - Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... '
                        ),
                ),
            'return-an-array-false' => array(
                    'callback' => 'a_light_seo_return_array_false',
                    'label' => array(
                            'en' => 'Return an array (false)',
                            // 'fr' => 'S'
                        ),
                    'desc' => array(
                            'en' => 'EN - Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... ',
                            // 'fr' => 'FR - Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... '
                        ),
                ),
            'return-true' => array(
                    'callback' => 'a_light_seo_return_true',
                    'label' => array(
                            'en' => 'Return true',
                            // 'fr' => 'S'
                        ),
                    'desc' => array(
                            'en' => 'EN - Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... ',
                            // 'fr' => 'FR - Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... '
                        ),
                ),
            'return-false' => array(
                    'callback' => 'a_light_seo_return_false',
                    'label' => array(
                            'en' => 'Return false',
                            // 'fr' => 'S'
                        ),
                    'desc' => array(
                            'en' => 'EN - Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... ',
                            // 'fr' => 'FR - Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... Lorem ipsum ... '
                        ),
                ),
        )
);


/**
 * functions
 */


function a_light_seo_return_false()
{
    return false;
}
function a_light_seo_return_true()
{
    return true;
}
function a_light_seo_return_array_true()
{
    return array(
            'success' => true,
            'message' => 'This is a dev note to the user when array and true !'
        );
}
function a_light_seo_return_array_false()
{
    return array(
            'success' => false,
            'message' => 'This is a dev note to the user when array and false !'
        );
}





function a_light_seo_is_active($part)
{
    return (bool)addon_get_setting('light_seo',$part);
}

function a_light_seo_is_debug()
{
    return addon_get_setting('light_seo','debug_mode');
}

/**
 * get relative URL
 * http://example.com/blog/ => /blog/'
 */
function a_light_seo_get_relative_url()
{
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }
    $t = str_replace(
        array('http://', 'https://'),
        '',
        URL_ROOT
    );
    $t = explode('/', $t);
    unset($t['0']);
    return a_light_seo_return_clean_url('/'.implode('/', $t));
}


/**
 * check url before redirection
 * used for random article and after comment submission
 */
function a_light_seo_hook_before_redirection($args)
{
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }
    if (!a_light_seo_is_active('use_url_rewrite')) {
        return $args;
    }
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }

    if (strpos($args['1'], 'index.php?addon_light_seo=') !== false) {
        $args['1'] = str_replace(
            'index.php?addon_light_seo=',
            a_light_seo_get_relative_url(),
            $args['1']
        );
    }
    $args['1'] = a_light_seo_return_clean_url($args['1']);

    return $args;
}


/**
 * check robots.txt
 *
 * trigger by a_light_seo_build_sitemap()
 */
function a_light_seo_update_robots_txt()
{
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }

    $robots = '';
    $file = a_light_seo_return_clean_url(BT_ROOT .'/robots.txt');
    if (file_exists($file)) {
        $robots = file_get_contents($file);
    }
    if (!strpos($robots, 'Sitemap: '. a_light_seo_return_clean_url(a_light_seo_get_relative_url() .'/sitemap.xml'))) {
        $robots .= "\r".'Sitemap: '. a_light_seo_return_clean_url(a_light_seo_get_relative_url() .'/sitemap.xml');
    }

    return (file_put_contents($file, $robots, LOCK_EX) !== false);
}


/**
 * refresh sitemap
 *
 * refresh if sitemap is older than 24h
 */
function a_light_seo_build_sitemap()
{
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }
    $file = a_light_seo_return_clean_url(BT_ROOT .'/sitemap.xml');

    // if (file_exists($file) && (time()-filemtime($file)) > $addons_params['sitemap_ttl']['value']) {
        // return true;
    // }

    if (!isset($GLOBALS['db_handle']) || !is_object($GLOBALS['db_handle'])) {
        $GLOBALS['db_handle'] = open_base();
    }
    $query = 'SELECT bt_date,bt_id,bt_title,bt_link
                FROM articles
               WHERE bt_date <= '.date('YmdHis').' AND bt_statut=1
            ORDER BY bt_date DESC';
    $tableau = liste_elements($query, array(), 'articles');

    $xml  = '';
    $xml .= '<?xml version="1.0" encoding="UTF-8"?>'."\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

    foreach ($tableau as $art) {
        $xml .= '<url>' . "\n";
        $xml .= '  <loc>'. a_light_seo_blogo_to_addon($art['bt_link'], 'article') .'</loc>'."\n";
        $xml .= '  <lastmod>'. $art['annee'] .'-'. $art['mois'] .'-'. $art['jour'] .'</lastmod>'."\n";
        $xml .= '</url>' . "\n";
    }

    $xml .= '</urlset>';

    a_light_seo_update_robots_txt();
    return (file_put_contents($file, $xml, LOCK_EX) !== false);
}


/**
 * working on all url in final rendered html
 */
function a_light_seo_hook_work_on_content($args)
{
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }
    if (!a_light_seo_is_active('use_url_rewrite')) {
        return $args;
    }
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }

    $rel = a_light_seo_get_relative_url();

    $args['1'] = str_replace(
        array(
                'href="?liste"',
                'href="?random"',
                'href="rss.php"',
                'href="feed.php?mode=rss"',
                'href="atom.php"',
                'href="feed.php?mode=atom"',
                'href="?tag=',
                'href="?mode=links',
                'href="'.$rel.'index.php?mode=links',
                'href="index.php?mode=links',
                '&amp;mode=links" rel="tag"',
                '@import url(\'',
                '<link rel="stylesheet" href="themes/',
                '<link rel="stylesheet" href="addons/',
                'action="?addon_light_seo=/',
                'class="com-gravatar" src="themes/',
                '/&amp;',
                // fix
                'http://http',
                'https://https',
                '//http:',
                '//https:',
            ),
        array(
                'href="'.$rel.'?liste"',
                'href="'.$rel.'?random"',
                'href="'.$rel.'feed.php?mode=rss"',
                'href="'.$rel.'feed.php?mode=rss"',
                'href="'.$rel.'feed.php?mode=atom"',
                'href="'.$rel.'feed.php?mode=atom"',
                'href="'.$rel.'tag/',
                'href="'.$rel.'link/',
                'href="'.$rel.'link/',
                'href="'.$rel.'link/',
                '?mode=links" rel="tag"',
                '@import url(\''. $rel,
                '<link rel="stylesheet" href="'. $rel .'themes/',
                '<link rel="stylesheet" href="'. $rel .'addons/',
                'action="'. $rel,
                'class="com-gravatar" src="'.$rel.'themes/',
                '/?',
                // fix
                'http:',
                'https:',
                'http:',
                'https:',
            ),
        $args['1']
    );

    return $args;
}


/**
 * return the flatDb handler
 */
function a_light_seo_get_db()
{
    global $addons_light_seo;

    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }
    if (!is_object($addons_light_seo)) {
        $addons_light_seo = new DirtyScript\FlatDB\FlatDB(addon_get_vhost_cache_path('light_seo').'/db-rewrite', true);
    }

    return $addons_light_seo;
}


/**
 * clean url
 *
 * remove //
 * handle http(s)://
 * to do : handle "//example.com"
 *
 * @param string $url absolute or relative
 */
function a_light_seo_return_clean_url($url)
{
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }
    $host = '';
    if (strpos($url, 'https://') === 0) {
        $host = 'https://';
        $url = substr($url, 8);
    } elseif (strpos($url, 'http://') === 0) {
        $host = 'http://';
        $url = substr($url, 7);
    }
    while (strstr($url, '//')) {
        $url = str_replace('//', '/', $url);
    }
    return $host.$url;
}


/**
 * init the addon
 *
 * check htaccess files
 * check if url is rewrited
 */
function a_light_seo_hook_at_start($args = array())
{
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }
    if (!a_light_seo_is_active('use_url_rewrite')) {
        return $args;
    }
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }

    // var_dump(DIR_VHOST_ADDONS .'light_seo/FlatDB.php' );
    require_once DIR_ADDONS .'light_seo/FlatDB.php';

    a_light_seo_build_sitemap();

    $rewrites = array('link', 'article', 'tag');
    foreach ($rewrites as $rewrite) {
        if (!file_exists(BT_ROOT.'/'. $rewrite .'/') || !is_dir(BT_ROOT .'/'. $rewrite .'/') || !file_exists(BT_ROOT . $rewrite .'/.htaccess')) {
            if (!is_dir(BT_ROOT.'/'. $rewrite .'/')) {
                mkdir(BT_ROOT.'/'. $rewrite .'/');
            }

            file_put_contents(
                BT_ROOT.'/'. $rewrite .'/.htaccess',
                "\r\n".'RewriteEngine on'."\r\n".'RewriteRule ^(.*)$ '. a_light_seo_return_clean_url(a_light_seo_get_relative_url() .'/index.php?addon_light_seo=/'). $rewrite .'/$1 [NC,L,QSA]'."\r\n",
                LOCK_EX
            );
        }
    }

    if (!empty($_GET['addon_light_seo'])) {
        if (strpos($_GET['addon_light_seo'], '/article/') !== false) {
            $db = a_light_seo_get_db();
            $original_url = str_replace(URL_ROOT.'?d=', '', $db->dataSearch(array( '=='. a_light_seo_return_clean_url(URL_ROOT.$_GET['addon_light_seo']))));

            // founded !
            if (is_array($original_url)) {
                // 1 found
                if (count($original_url) === 1 && isset($original_url['0'])) {
                    $_GET['d'] = $original_url['0'];
                // multiple found
                } elseif (count($original_url) > 1) {
                    // try to found the right one ...
                    foreach ($original_url as $o) {
                        $t = $db->dataGet(URL_ROOT.'?d='.$o);
                        if (substr($t, -(strlen($_GET['addon_light_seo']))) == $_GET['addon_light_seo']) {
                            $_GET['d'] = $o;
                        }
                    }
                }
            }
        } elseif (strpos($_GET['addon_light_seo'], '/tag/') !== false) {
            $_GET['tag'] = str_replace('/tag/', '', $_GET['addon_light_seo']);
        } elseif (strpos($_GET['addon_light_seo'], '/link/') !== false) {
            $_GET['mode'] = str_replace('/link/', 'links', $_GET['addon_light_seo']);
        }
    }
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }

    return $args;
}


/**
 * return the uniq rewrited URL
 */
function a_light_seo_get_uniq_slug($url)
{
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }
    $i = 1;
    $db = a_light_seo_get_db();
    $t = $db->dataSearch(array('=='. $url));

    if (count($t) == 0) {
        if (a_light_seo_is_debug()) {
            var_dump(__FUNCTION__);
        }
        return $url;
    }

    while (count($db->dataSearch(array('=='. $url .'-'. $i))) !== 0) {
        ++$i;
    }

    return $url .'-'. $i;
}


/**
 * convert a blogotext article URL to a rewrited URL
 */
function a_light_seo_blogo_to_addon($url, $url_type = 'article')
{
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }
    // check for url anchor #
    $anchor = '';
    if (strpos($url, '#') !== false) {
        list($url, $anchor) = explode('#', $url);
        $anchor = '#'. $anchor;
    }

    // search in db
    $t_url = $url;
    $db_url = a_light_seo_get_db()->dataGet($url);

    // not in db
    if ($db_url === null) {
        if (preg_match("/\?d=(\d{4}\/\d{2}\/\d{2}\/\d{2}\/\d{2}\/\d{2})-/", $url, $matches)) {
            $tab = explode('/', $matches['1']);
            $id = substr($tab['0'].$tab['1'].$tab['2'].$tab['3'].$tab['4'].$tab['5'], '0', '14');
            $t_url = str_replace($matches['0'], 'article/', $url);
            $db_url = a_light_seo_get_uniq_slug($t_url);

            if (a_light_seo_get_db()->dataPush($url, $db_url) !== false) {
                $t_url = $db_url;
            }
        }

    // in db
    } else {
        $t_url = $db_url;
    }

    return $t_url.$anchor;
}


/**
 * main URL converter
 */
function a_light_seo_hook_article_converter($args)
{
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }
    if (!a_light_seo_is_active('use_url_rewrite')) {
        if (a_light_seo_is_debug()) {
            var_dump(__FUNCTION__);
        }
        return $args;
    }

    if (!isset($args['1']) || empty($args['1'])) {
        if (a_light_seo_is_debug()) {
            var_dump(__FUNCTION__);
        }
        return $args;
    }

    if (is_string($args['1'])) {
        $args['1'] = a_light_seo_blogo_to_addon($args['1'], 'article');
    } elseif (is_array($args['1'])) {
        if (isset($args['1']['bt_link']) && is_string($args['1']['bt_link'])) {
            $args['1']['bt_link'] = a_light_seo_blogo_to_addon($args['1']['bt_link'], 'article');
        } else {
            foreach ($args['1'] as &$art) {
                if (!empty($art['bt_link'])) {
                    $art['bt_link'] = a_light_seo_blogo_to_addon($art['bt_link'], 'article');
                }
            }
        }
    }
    if (a_light_seo_is_debug()) {
        var_dump(__FUNCTION__);
    }

    return $args;
}
