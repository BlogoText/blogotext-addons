<?php

/**
 * Changelog
 *
 * 1.0.0 2017-03-24 thuban with help of RemRem
 */
 
$declaration = array(
    // the tag of your addon (required)
    'tag' => 'lazyload',

    // the name, showed in admin/addon (required)
    'name' => array(
        'en' => 'Lazyload images',
        'fr' => 'Chargement d\'images à la demande',
    ),

    // the desc, showed in admin/addon (required)
    'desc' => array(
        'en' => 'Load images when in viewport',
        'fr' => 'Chargement des images lorsqu\'elles sont dans le viewport',
    ),

    // the version, showed in admin/addon (required)
    'version' => '1.0.0',
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
        // check presence de <img
        if (strpos($art['bt_content'], '<img') === false) {
            continue;
        }

        // Je ne sais pas pourquoi, mais sans convertir en UTF-8, ça merdouille ...
        // du moins sur un environement window, à tester sous linux
        $art['bt_content'] = mb_convert_encoding($art['bt_content'], 'HTML-ENTITIES', 'UTF-8');

        $doc = new DOMDocument();
        $doc->loadHTML($art['bt_content'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
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
        // save
        $art['bt_content'] = $doc->saveHTML();
    }

    return $datas;
}
