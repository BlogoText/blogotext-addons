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
    'js' => 'lazyload.js',
    'url' => 'https://kaizau.github.io/Lazy-Load-Images-without-jQuery/',

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

    // parcours les articles
    foreach ($datas['1'] as &$art) {

        $doc = new DOMDocument();
        $doc->loadHTML($art['bt_content'], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $imgs = $doc->getElementsByTagName('img');
        foreach ($imgs as $i) {
            // save img in <noscript>
            //$noscript = '<noscript>' . $i . '</noscript>';

            // get src
            $old_src = $i->getAttribute('src');
            // set src as blank gif
            $i->removeAttribute('src'); 
            $i->setAttribute('src', "data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="); 

            // set data-src as src
            $i->setAttribute('data-src', $old_src);
            // set class
            $i->setAttribute('class','lazy-load');

            // save
            //$i = $noscript . $i;
            //$doc->saveHTML($i);

        }
        $art['bt_content'] = $doc->saveHTML();
    }

    return $datas;
}

