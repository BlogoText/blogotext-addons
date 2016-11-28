<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

/**
 * Changelog
 *
 * 0.1.0
 *  2016-11-28 RemRem, maybe need more work
 *  - upd addon to be BT#160 compliant
 *  - fix #12
 *  - upd current version to 0.X (dev version)
 */

$declaration = array(
    'tag' => 'readmore',
    'name' => array(
        'en' => 'Read more',
        'fr' => 'Autres articles',
    ),
    'desc' => array(
        'en' => 'List 3 "read-also like" thumbnails below each post.',
        'fr' => 'Afficher des image d\'autres articles.',
    ),
    'version' => '0.1.0',
    'compliancy' => '3.7',
    'css' => 'style.css',

    'config' => array(
        'nb_posts' => array(
            'type' => 'int',
            'label' => array(
                'en' => 'Number of posts to list',
                'fr' => 'Nombre d\'articles à lister'
            ),
            'value' => 4,
            'value_min' => 1,
            'value_max' => 8,
        ),
    ),
);

function a_readmore()
{
    $nbPosts = addon_get_setting('readmore', 'nb_posts');

    // Find all posts
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

    // Get posts
    $sql = '
        SELECT bt_title, bt_id, bt_content
          FROM articles
         WHERE ID IN ('.implode(',', $posts).')';
    try {
        $posts = $GLOBALS['db_handle']->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return ((bool)DISPLAY_PHP_ERRORS) ? 'Error fetch content a_readmore(): '.$e->getMessage() : '';
    }

    // Generates the list
    $html = '<ul id="readmore">'."\n";
    foreach ($posts as $i => $post) {
        // Extract the image from $post['bt_content']
        preg_match('<img *.* src=(["|\']?)(([^\1 ])*)(\1).*>', $post['bt_content'], $matches);
        $img = '';
        if ($matches) {
            $img = $matches[2];  // chemin_thb_img_test($matches[2])
        }
        // Generates the link
        $decId = decode_id($post['bt_id']);
        $link = URL_ROOT.'?d='.implode('/', $decId).'-'.titre_url($post['bt_title']);
        $html .= "\t".'<li style="background-image: url('.$img.');"><a href="'.$link.'">'.$post['bt_title'].'</a></li>'."\n";
    }
    $html .= '</ul>'."\n";

    return $html;
}
