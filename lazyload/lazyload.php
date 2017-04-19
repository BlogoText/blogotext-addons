<?php

/**
 * Changelog
 *
 * 1.0.2 2017-04-19
 *   fix error message when articles not available (/blog/?liste)
 * 1.0.1 2017-04-18
 *   fix some issues with libxml
 * 1.0.0 2017-03-24 thuban with help of RemRem
 */
 
$declaration = array(
    // the tag of your addon (required)
    'tag' => 'lazyload',

    // the name, showed in admin/addon (required)
    'name' => array(
        'en' => 'Lazyload images',
        'fr' => 'lazyload - chargement d\'images à la demande',
    ),

    // the desc, showed in admin/addon (required)
    'desc' => array(
        'en' => 'Load images when in viewport',
        'fr' => 'Chargement des images lorsqu\'elles sont dans le viewport',
    ),

    // the version, showed in admin/addon (required)
    'version' => '1.0.2',
    'compliancy' => '3.7',
    'css' => 'lazyload.css',
    'js' => array('echo.js', 'lazyload.js'),
    'url' => 'http://yeuxdelibad.net',

    'hook-push' => array(
            'list_items' => array(
                    'callback' => 'a_lazy_work_on_content',
                    'priority' => 100
                )
        ),
);

function a_lazy_work_on_content($datas)
{
    // test le contenu
    if (!$datas || !is_array($datas)) {
        return $datas;
    }

    // on ne traite que les articles (à adapter au besoin)
    if ($datas['2'] != 'articles') {
        return $datas;
    }

    // parcours les articles
    foreach ($datas['1'] as &$art) {
        // check presence article
        if (!isset($art['bt_content'])) {
            continue;
        }
        // check presence de <img
        if (strpos($art['bt_content'], '<img') === false) {
            continue;
        }

        // Je ne sais pas pourquoi, mais sans convertir en UTF-8, ça merdouille ...
        // du moins sur un environement window, à tester sous linux
        $art['bt_content'] = mb_convert_encoding($art['bt_content'], 'HTML-ENTITIES', 'UTF-8');

        $doc = new DOMDocument();

        // set error level (avoid error for href="...&..")
        $internalErrors = libxml_use_internal_errors(true);

        $libxml_compat = (LIBXML_VERSION >= '20708');
        if ($libxml_compat) {
            $doc->loadHTML($art['bt_content'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        } else {
            $doc->loadHTML('<div>'.$art['bt_content'].'</div>');
            $libxml_compat = false;
        }

        // Restore error level
        libxml_use_internal_errors($internalErrors);
        
        $imgs = $doc->getElementsByTagName('img');

        // on traite les images
        for ($i = $imgs->length; --$i >= 0;) {
            $img = $imgs->item($i);

            $orgin_src = $img->getAttribute('src');
            $orgin_alt = $img->getAttribute('alt');

            // set data-echo as src
            $img->setAttribute('data-echo', $orgin_src);
            // set src as blank gif
            // $img->removeAttribute('src');
            $img->setAttribute('src', "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==");

            // class lazy-load
            $img->setAttribute('class', 'lazy-load');

            // on gére le noscript
            $noscript = $doc->createElement('noscript');

            // on insert noscript avant l'image
            $img->parentNode->insertBefore($noscript, $img);

            // on gére l'image du noscript
            $alt_img = $doc->createElement('img');
            // on insert l'img du noscript dans le noscript
            $noscript->appendChild($alt_img);
            // on modifie l'img du noscript
            $alt_img->setAttribute('src', $orgin_src);
            $alt_img->setAttribute('alt', $orgin_alt);
        }

        // fix for Libxml < 2.7.8
        // found on http://stackoverflow.com/questions/29493678/loadhtml-libxml-html-noimplied-on-an-html-fragment-generates-incorrect-tags
        if (!$libxml_compat) {
            $container = $doc->getElementsByTagName('div')->item(0);
            $container = $container->parentNode->removeChild($container);
            while ($doc->firstChild) {
                $doc->removeChild($doc->firstChild);
            }
            while ($container->firstChild) {
                $doc->appendChild($container->firstChild);
            }
        }

        // save
        $art['bt_content'] = $doc->saveHTML();
    }

    return $datas;
}
