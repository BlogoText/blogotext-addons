<?php

/**
 * Changelog
 *
 * 1.1.0 2018-02-18 @RemRem
 *   - add param "hideOnLenght"
 *   - add param "useAbstract"
 *   - add support for "?page="
 *   - add class "addon_readmore_link" on the readmore link
 *   - add encapsulate the 3 dots in a <span class="addon_readmore_dots">
 *   - add description to "a_readmore_run()"
 *   - upd main function name "a_show_excerpt()" => "a_readmore_run()"
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
        'en' => 'Show excerpt of articles with a link to read more.',
        'fr' => 'Affiche des résumés des articles avec un lien pour le lire entier.'
    ),

    // the version, showed in admin/addon (required)
    'version' => '1.1.0',
    'compliancy' => '3.7',

    'url' => 'https://yeuxdelibad.net/Blog',

    'hook-push' => array(
            'list_items' => array(
                    'callback' => 'a_readmore_run',
                    'priority' => 100
                )
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

        'hideOnLenght' => array(
            'type' => 'bool',
            'label' => array(
                'en' => 'Hide the link if the extract does not reach the required length',
                'fr' => 'Cache le lien si l`extrait n`atteind pas la longueur requise',
            ),
            'value' => true,
        ),

        'useAbstract' => array(
            'type' => 'bool',
            'label' => array(
                'en' => 'Use the chapo of the article if it is provided',
                'fr' => 'Utiliser le chapô de l`article si il est fournit',
            ),
            'value' => true,
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
);

/**
 * used by hook
 * Transform article content on the main blog page (and aside page ?p=)
 *   to reduce the content to show and put a link to read the article 
 *   on the dedicated page of the article
 *
 * @params array $datas, from the hook
 * @return array
 */
function a_readmore_run($datas)
{
    // check for URL params
    $ct_get = count($_GET);
    if ($ct_get > 0 // no params
     && ($ct_get === 1 && !isset($_GET['p'])) // only ?p= params
     && ($ct_get === 1 && $_GET['p'] == '0') // or ?p=0
    ) {
        return $datas;
    }

    // test le contenu
    if (!$datas
     || !is_array($datas)
     || $datas['2'] != 'articles'
    ) {
        // var_dump($datas);
        return $datas;
    }

    // get settings
    $setting_length = addon_get_setting('readmore', 'length');
    $setting_label = addon_get_setting('readmore', 'linklabel');
    $setting_useAbstract = addon_get_setting('readmore', 'useAbstract');
    $setting_hideOnLenght = addon_get_setting('readmore', 'hideOnLenght');

    // parcours les articles
    foreach ($datas['1'] as &$art) {
        // check presence article
        if (!isset($art['bt_content'])) {
            continue;
        }

        // use chapo
        if ($setting_useAbstract && !empty($art['bt_abstract'])) {
            $art['bt_content'] = $art['bt_abstract'];
        }

        // remove HTML tags
        $art['bt_content'] = strip_tags($art['bt_content']);

        //get content length
        $content_size = mb_strlen($art['bt_content']);

        // cut content if length reach the required length
        if ($content_size > $setting_length) {
            $art['bt_content'] = mb_substr($art['bt_content'], 0, $setting_length);
        }

        // show ? the read more link
        if (!$setting_hideOnLenght
         || ($setting_hideOnLenght && $content_size > $setting_length)
        ) {
            $art['bt_content'].= '<span class="addon_readmore_dots">…</span> <a href="'.$art['bt_link'].'" class="addon_readmore_link">'.$setting_label.'</a>';
        }
    }

    return $datas;
}
