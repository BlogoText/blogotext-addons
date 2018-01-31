<?php

/**
 * Changelog
 *
 * 1.0.0 2017-31-12 thuban
 */
 
$declaration = array(
    // the tag of your addon (required)
    'tag' => 'readmore',

    // the name, showed in admin/addon (required)
    'name' => array(
        'en' => 'Excerpt',
        'fr' => 'Aperçus',
    ),

    // the desc, showed in admin/addon (required)
    'desc' => array(
        'en' => 'Show excerpt of articles with a link to read more',
        'fr' => 'Affiche des résumés des articles avec un lien pour le lire entier'
    ),
    'settings' => array(
        'length' => array(
            'type' => 'int',
            'label' => array(
                'en' => 'Length of excerpt',
                'fr' => 'Longueur de l\'extrait'
            ),
            'value' => true,
            'value' => 250,
        ),

        'linklabel' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Label for the link',
                'fr' => 'Label pour le lien '
            ),
            'value' => 'Lire la suite.',
        ),
    ),



    // the version, showed in admin/addon (required)
    'version' => '1.0.0',
    'compliancy' => '3.7',
    'url' => 'https://yeuxdelibad.net/Blog',
    'hook-push' => array(
            'list_items' => array(
                    'callback' => 'a_show_excerpt',
                    'priority' => 100
                )
        ),
);

function a_show_excerpt($datas)
{
    // to do only on main page : no parameters
    if (!count($_GET)) {
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
            if (!empty($art['bt_abstract'])) {
                $art['bt_content'] = $art['bt_abstract'];
            } else {
                $art['bt_content'] = mb_substr(
                    strip_tags($art['bt_content']),
                    0,
                    addon_get_setting('readmore', 'length')
                );
                $art['bt_content'].= '… '.'<a href="'.$art['bt_link'].'">'.addon_get_setting('readmore', 'linklabel').'</a>';
            }
        }
    }
    return $datas;
}
