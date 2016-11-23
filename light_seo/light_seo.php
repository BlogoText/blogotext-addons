<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

/**
 * @author    RemRem <>
 * @copyright Copyright (C) RemRem
 * @licence   MIT
 * @version   0.0.8 POC
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

require_once DIR_ADDONS.'light_seo/FlatDB.php';

$GLOBALS['addons'][] = array(
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
    'version' => '0.0.9',
    'config' => array(
                'use_url_rewrite' => array(
                        'type' => 'bool',
                        'label' => array(
                                    'en' => 'Use URL rewrite',
                                    'fr' => 'Utiliser la réécriture d\'URL'
                                ),
                        'value' => false,
                    ),
                /*
                'url_article' => array(
                        'type' => 'text',
                        'label' => array(
                            'en' => 'Url for articles<br/><small>(no space, special chars, will be like "\article\")</small>',
                            'fr' => 'Url des articles<br/><small>(sans espaces, ni caractere spéciaux, resemblera à "\article\")</small>'
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
                */
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
            )
);



/**
 * set hooks
 */
hook_push('system-start', 'addon_light_seo_at_start', 200);

hook_push('before_afficher_rss_no_cache', 'addon_light_seo_article_converter', 200);
hook_push('encart_commentaires', 'addon_light_seo_article_converter', 200);
hook_push('liste_elements', 'addon_light_seo_article_converter', 200);

hook_push('afficher_index', 'addon_light_seo_work_on_content', 200);
hook_push('conversion_theme_addons_end', 'addon_light_seo_work_on_content', 200);
hook_push('before_redirection', 'addon_light_seo_before_redirection', 200);



/**
 * functions
 */

function addon_light_seo_is_active($part)
{
    $addons_status = list_addons();
    $addons_params = addon_get_conf('light_seo');
    if ($part == 'use_url_rewrite') {
        return false;
    }
    return ($addons_status['light_seo'] === true && $addons_params[$part]['value'] == true);
}

/**
 * get relative URL
 * http://example.com/blog/ => /blog/'
 */
function addon_light_seo_get_relative_url()
{
    $t = str_replace(
        array('http://', 'https://'),
        '',
        $GLOBALS['racine']
    );
    $t = explode('/', $t);
    unset($t['0']);
    return addon_light_seo_return_clean_url('/'.implode('/', $t));
}


/**
 * check url before redirection
 * used for random article and after comment submission
 */
function addon_light_seo_before_redirection($args)
{
    if (!addon_light_seo_is_active('use_url_rewrite')) {
        return $args;
    }

    if (strpos($args['1'], 'index.php?addon_light_seo=') !== false) {
        $args['1'] = str_replace(
            'index.php?addon_light_seo=',
            addon_light_seo_get_relative_url(),
            $args['1']
        );
    }
    $args['1'] = addon_light_seo_return_clean_url($args['1']);

    return $args;
}


/**
 * check robots.txt
 *
 * trigger by addon_light_seo_build_sitemap()
 */
function addon_light_seo_update_robots_txt()
{
    $robots = '';
    $file = addon_light_seo_return_clean_url(BT_ROOT .'/robots.txt');
    if (file_exists($file)) {
        $robots = file_get_contents($file);
    }
    if (!strpos($robots, 'Sitemap: '. addon_light_seo_return_clean_url(addon_light_seo_get_relative_url() .'/sitemap.xml'))) {
        $robots .= "\r".'Sitemap: '. addon_light_seo_return_clean_url(addon_light_seo_get_relative_url() .'/sitemap.xml');
    }

    return (file_put_contents($file, $robots) !== false);
}


/**
 * refresh sitemap
 *
 * refresh if sitemap is older than 24h
 */
function addon_light_seo_build_sitemap()
{
    $file = addon_light_seo_return_clean_url(BT_ROOT .'/sitemap.xml');

    if (file_exists($file) && (time()-filemtime($file)) > $addons_params['sitemap_ttl']['value']) {
        return true;
    }

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
        $xml .= '  <loc>'. addon_light_seo_blogo_to_addon($art['bt_link'], 'article') .'</loc>'."\n";
        $xml .= '  <lastmod>'. $art['annee'] .'-'. $art['mois'] .'-'. $art['jour'] .'</lastmod>'."\n";
        $xml .= '</url>' . "\n";
    }

    $xml .= '</urlset>';

    addon_light_seo_update_robots_txt();
    return (file_put_contents($file, $xml) !== false);
}


/**
 * working on all url in final rendered html
 */
function addon_light_seo_work_on_content($args)
{
    if (!addon_light_seo_is_active('use_url_rewrite')) {
        return $args;
    }

    $rel = addon_light_seo_get_relative_url();

    $args['1'] = str_replace(
        array(
                'href="?liste"',
                'href="?random"',
                'href="rss.php"',
                'href="atom.php"',
                'href="?tag=',
                'href="?mode=links',
                'href="'.$rel.'index.php?mode=links',
                'href="index.php?mode=links',
                '&amp;mode=links" rel="tag"',
                '@import url(\'',
                '<link rel="stylesheet" href="themes/',
                'action="?addon_light_seo=/',
                'class="com-gravatar" src="themes/',
                '/&amp;',
            ),
        array(
                'href="'.$rel.'?liste"',
                'href="'.$rel.'?random"',
                'href="'.$rel.'rss.php"',
                'href="'.$rel.'atom.php"',
                'href="'.$rel.'tag/',
                'href="'.$rel.'link/',
                'href="'.$rel.'link/',
                'href="'.$rel.'link/',
                '?mode=links" rel="tag"',
                '@import url(\''. $rel,
                '<link rel="stylesheet" href="'. $rel .'themes/',
                'action="'. $rel,
                'class="com-gravatar" src="'.$rel.'themes/',
                '/?',
            ),
        $args['1']
    );

    return $args;
}


/**
 * return the flatDb handler
 */
function addon_light_seo_get_db()
{
    global $addons_light_seo;

    if (!is_object($addons_light_seo)) {
        $addons_light_seo = new DirtyScript\FlatDB\FlatDB(BT_ROOT.DIR_ADDONS .'/light_seo/cache/db-rewrite', true);
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
function addon_light_seo_return_clean_url($url)
{
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
function addon_light_seo_at_start($args = array())
{

    if (!addon_light_seo_is_active('use_url_rewrite')) {
        return $args;
    }

    addon_light_seo_build_sitemap();

    $rewrites = array('link', 'article', 'tag');
    foreach ($rewrites as $rewrite) {
        if (!file_exists(BT_ROOT.'/'. $rewrite .'/') || !is_dir(BT_ROOT .'/'. $rewrite .'/') || !file_exists(BT_ROOT . $rewrite .'/.htaccess')) {
            if (!is_dir(BT_ROOT.'/'. $rewrite .'/')) {
                mkdir(BT_ROOT.'/'. $rewrite .'/');
            }

            file_put_contents(
                BT_ROOT.'/'. $rewrite .'/.htaccess',
                "\r\n".'RewriteEngine on'."\r\n".'RewriteRule ^(.*)$ '. addon_light_seo_return_clean_url(addon_light_seo_get_relative_url() .'/index.php?addon_light_seo=/'). $rewrite .'/$1 [NC,L,QSA]'."\r\n"
            );
        }
    }

    if (!empty($_GET['addon_light_seo'])) {
        if (strpos($_GET['addon_light_seo'], '/article/') !== false) {
            $db = addon_light_seo_get_db();
            $original_url = str_replace($GLOBALS['racine'].'?d=', '', $db->dataSearch(array( '=='. addon_light_seo_return_clean_url($GLOBALS['racine'].$_GET['addon_light_seo']))));

            // founded !
            if (is_array($original_url)) {
                // 1 found
                if (count($original_url) === 1 && isset($original_url['0'])) {
                    $_GET['d'] = $original_url['0'];
                // multiple found
                } elseif (count($original_url) > 1) {
                    // try to found the right one ...
                    foreach ($original_url as $o) {
                        $t = $db->dataGet($GLOBALS['racine'].'?d='.$o);
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

    return $args;
}


/**
 * return the uniq rewrited URL
 */
function addon_light_seo_get_uniq_slug($url)
{
    $i = 1;
    $db = addon_light_seo_get_db();
    $t = $db->dataSearch(array('=='. $url));

    if (count($t) == 0) {
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
function addon_light_seo_blogo_to_addon($url, $url_type = 'article')
{
    // check for url anchor #
    $anchor = '';
    if (strpos($url, '#') !== false) {
        list($url, $anchor) = explode('#', $url);
        $anchor = '#'. $anchor;
    }

    // search in db
    $t_url = $url;
    $db_url = addon_light_seo_get_db()->dataGet($url);

    // not in db
    if (is_null($db_url)) {
        if (preg_match("/\?d=(\d{4}\/\d{2}\/\d{2}\/\d{2}\/\d{2}\/\d{2})-/", $url, $matches)) {
            $tab = explode('/', $matches['1']);
            $id = substr($tab['0'].$tab['1'].$tab['2'].$tab['3'].$tab['4'].$tab['5'], '0', '14');
            $t_url = str_replace($matches['0'], 'article/', $url);
            $db_url = addon_light_seo_get_uniq_slug($t_url);

            if (addon_light_seo_get_db()->dataPush($url, $db_url) !== false) {
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
function addon_light_seo_article_converter($args)
{
    if (!addon_light_seo_is_active('use_url_rewrite')) {
        return $args;
    }

    if (!isset($args['1']) || empty($args['1'])) {
        return $args;
    }

    if (is_string($args['1'])) {
        $args['1'] = addon_light_seo_blogo_to_addon($args['1'], 'article');
    } elseif (is_array($args['1'])) {
        if (isset($args['1']['bt_link']) && is_string($args['1']['bt_link'])) {
            $args['1']['bt_link'] = addon_light_seo_blogo_to_addon($args['1']['bt_link'], 'article');
        } else {
            foreach ($args['1'] as &$art) {
                if (!empty($art['bt_link'])) {
                    $art['bt_link'] = addon_light_seo_blogo_to_addon($art['bt_link'], 'article');
                }
            }
        }
    }

    return $args;
}
