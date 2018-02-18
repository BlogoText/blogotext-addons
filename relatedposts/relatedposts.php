<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
#
# 2016 Mickaël S. <contact@tiger-222.fr>
# *** LICENSE ***

/**
 * Changelog
 * 1.0.1 2017-12-30 thuban
 *  - fix #35
 *
 * 1.0.0 2017-01-24 RemRem
 *  - upd version for BT 3.7
 *  - upd addons declaration (config > settings)
 *
 * 0.1.0
 *  2016-11-28 RemRem, maybe need more work
 *  - upd addon to be BT#160 compliant
 *  - fix #12
 *  - upd current version to 0.X (dev version)
 */

$declaration = array(
    'tag' => 'relatedposts',
    'version' => '1.0.0',
    'compliancy' => '3.7',
    'url' => 'http://www.tiger-222.fr/',

    'name' => array(
        'en' => 'Related posts',
        'fr' => 'Articles en relation',
    ),
    'desc' => array(
        'en' => 'Show a list of posts in relation of the current displayed one.',
        'fr' => 'Afficher une liste d\'articles en relation avec celui en cours de lecture.',
    ),

    'settings' => array(
        'nb_posts' => array(
            'type' => 'int',
            'label' => array(
                'en' => 'Number of posts to list',
                'fr' => 'Nombre d\'articles à lister'
            ),
            'value' => 5,
            'value_min' => 1,
            'value_max' => 10,
        ),
        'sentence' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Sentence printed before the post list',
                'fr' => 'Phrase d\'accroche affichée avant la liste des articles'
            ),
            'value' => 'Découvrez d\'autres articles de la même trempe que cet article :',
        ),

        'random' => array(
            'type' => 'bool',
            'label' => array(
                'en' => 'Pick random article',
                'fr' => 'Choisir des articles au hasard'
            ),
            'desc' => array(
                'en' => 'No relation with current article.',
                'fr' => 'Aucun lien avec l\'article en train d\'être lu'
            ),
            'value' => false,
        ),

        'showimg' => array(
            'type' => 'select',
            'label' => array(
                'en' => 'Show images or text lists',
                'fr' => 'Afficher des images des articles ou leur titre.'
            ),
            'desc' => array(
                'en' => 'Select image to show a list of pictures inside suggested articles, or text to show only titles.',
                'fr' => 'Choisissez entre afficher des images des articles suggérés ou seulement leur titre en texte.',
            ),
            'options' => array(
                'images' => array(
                                'en' => 'Show image in article',
                                'fr' => 'Afficher des images dans la liste d\'articles',
                            ),
                'text' => array(
                                'en' => 'Show article title only',
                                'fr' => 'Afficher seulement le titre de l\'article',
                            ),
            ),
            'value' => 'text' // default value
        ),
        'defaultimg' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Default image to show (URL)',
                'fr' => 'Image par défaut si aucune n\'est trouvée (URL)'
            ),
            'value' => $GLOBALS['racine'].'/favicon.ico',
        ),

        'backgroundcss' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Background color (css)',
                'fr' => 'Couleur de fond (css)'
            ),
            'value' => 'transparent;'
        ),
        'linkbgcolor' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Links Background color (css)',
                'fr' => 'Couleur d\'arrière plan des liens (css)'
            ),
            'value' => '#ddd',
        ),
        'linkhoverbgcolor' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Links Background color on hover (css)',
                'fr' => 'Couleur d\'arrière plan des liens au survol (css)'
            ),
            'value' => '#eee',
        ),
        'linkcolor' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Links color (css)',
                'fr' => 'Couleur des liens (css)'
            ),
            'value' => '#444',
        ),
        'squares' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Left squares color (css)',
                'fr' => 'Couleur des carrés à gauche (css)',
            ),
            'value' => '#fa8072',
        ),
    ),
    'css' => 'style.css',
);

// Include the posts list.
// To use in theme/$theme/post.html.
function a_relatedposts()
{
    $nbPosts = addon_get_setting('relatedposts', 'nb_posts');

    // 1. Get the post ID
    $postId = (string)filter_input(INPUT_GET, 'd');
    if (preg_match('#^\d{4}(/\d{2}){5}#', $postId)) {
        $postId = (int)substr(str_replace('/', '', $postId), 0, 14);
    } elseif (preg_match('#^\d{14}#', $postId)) {
        $postId = (int)substr($postId, 0, 14);
    }

    $pick_random = addon_get_setting('relatedposts', 'random');
    if ($pick_random) {
        // 2. Find all posts
        $sql = '
            SELECT ID
            FROM articles
            WHERE bt_statut = 1
                AND bt_date <= '.date('YmdHis');
        try {
            $result = $GLOBALS['db_handle']->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ((bool)DISPLAY_PHP_ERRORS) ? 'Error a_readmore(): '.$e->getMessage() : '';
        }

        // Clean array
        foreach ($result as $i => $post) {
            $result[$i] = (int)$post['ID'];
        }

        // Select N entries
        shuffle($result);
        $posts = array_slice($result, 0, $nbPosts);

        // 3. Get posts
        $sql = '
            SELECT bt_title, bt_id, bt_content
            FROM articles
            WHERE ID IN ('.implode(',', $posts).')';
        try {
            $relatedPosts = $GLOBALS['db_handle']->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ((bool)DISPLAY_PHP_ERRORS) ? 'Error fetch content a_readmore(): '.$e->getMessage() : '';
        }
    } else {
        // 2. Get post tags
        try {
            $sql = $GLOBALS['db_handle']->prepare(
                'SELECT bt_tags
                FROM articles
                WHERE bt_statut = 1
                        AND bt_id = :id'
            );
            $sql->bindValue(':id', $postId, SQLITE3_INTEGER);
            $sql->execute();
            $tags = $sql->fetchAll(PDO::FETCH_ASSOC);
            $tags = current($tags);
        } catch (Exception $e) {
            return ((bool)DISPLAY_PHP_ERRORS) ? 'Error step 2 addon_relatedposts() : '.$e->getMessage() : '';
        }

        // 3. Find related posts based on a random tag from current article
        $tags = explode(', ', $tags['bt_tags']);
        shuffle($tags);
        $tag = current($tags);
        try {
            $sql = $GLOBALS['db_handle']->prepare(
                'SELECT bt_id, bt_title, bt_content
                FROM articles
                WHERE bt_statut = 1
                        AND bt_id != :id
                        AND bt_tags LIKE :tag'
            );
            $sql->bindValue(':id', $postId, SQLITE3_INTEGER);
            $sql->bindValue(':tag', '%'.$tag.'%');
            $sql->execute();
            $relatedPosts = $sql->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ((bool)DISPLAY_PHP_ERRORS) ? 'Error step 3 addon_relatedposts() : '.$e->getMessage() : '';
        }
        shuffle($relatedPosts);
        $relatedPosts = array_slice($relatedPosts, 0, $nbPosts);
    }

    // 4. Generate the list
    $html = '<div class="related-posts">';
    $html .= '<p>';
    $html .= addon_get_setting('relatedposts', 'sentence');
    $html .= '</p>';

    $showimg = addon_get_setting('relatedposts', 'showimg');

    if ($showimg == 'images') {
        $html = '<ul id="readmore">'."\n";
        foreach ($relatedPosts as $i => $post) {
        // Extract the image from $post['bt_content']
            preg_match('<img *.* src=(["|\']?)(([^\1 ])*)(\1).*>', $post['bt_content'], $matches);
            $img = '';
            if ($matches) {
                $img = $matches[2];  // chemin_thb_img_test($matches[2])
            } else {
                $img = addon_get_setting('relatedposts', 'defaultimg');
            }
            // Generates the link
            $decId = decode_id($post['bt_id']);
            $link = URL_ROOT.'?d='.implode('/', $decId).'-'.titre_url($post['bt_title']);
            $html .= "\t".'<li style="background-image: url('.$img.');"><a href="'.$link.'">'.$post['bt_title'].'</a></li>'."\n";
        }
        $html .= '</ul>'."\n";
    } else {
        $html .= '<ul>';
        foreach ($relatedPosts as $post) {
            $decId = decode_id($post['bt_id']);
            $html .= '<li><a href="?d='.implode('/', $decId).'-'.titre_url($post['bt_title']).'">'.$post['bt_title'].'</a></li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
    }

    // 5. change css colors with js if not images
    if (! $showimg) {
        $html .= '<script>';
        $html .= 'var css = document.createElement("style");';
        $html .= 'css.type = "text/css";';
            // background
        $html .= 'css.innerHTML += ".article .related-posts { background-color: '.
            addon_get_setting('relatedposts', 'backgroundcss') . '}";';
            // link color
        $html .= 'css.innerHTML += ".article .related-posts a { color: '.
            addon_get_setting('relatedposts', 'linkcolor') . '}";';
            // link bg
        $html .= 'css.innerHTML += ".article .related-posts a { background-color: '.
            addon_get_setting('relatedposts', 'linkbgcolor') . '}";';
            // link hover bg
        $html .= 'css.innerHTML += ".article .related-posts a:hover { background: '.
            addon_get_setting('relatedposts', 'linkhoverbgcolor') . '}";';
            // left squares
        $html .= 'css.innerHTML += ".article .related-posts a::before { background: '.
            addon_get_setting('relatedposts', 'squares') . '}";';
        $html .= 'css.innerHTML += ".article .related-posts a:hover::after { border-left-color: '.
            addon_get_setting('relatedposts', 'squares') . '}";';
        $html .= 'document.head.appendChild(css);';
        $html .= '</script>';
    }

    return $html;
}
