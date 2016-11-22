<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

$GLOBALS['addons'][] = array(
    'tag' => 'readmore',
    'name' => array(
        'en' => 'Read more',
        'fr' => 'Autres articles',
    ),
    'desc' => array(
        'en' => 'List 3 "read-also like" thumbnails below each post.',
        'fr' => 'Afficher des image d\'autres articles.',
    ),
    'version' => '1.0.0',
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

function addon_readmore()
{
    $conf = addon_get_conf('readmore');
    $nbPosts = $conf['nb_posts']['value'];

    // Find all posts
    $sql = '
        SELECT ID
          FROM articles
         WHERE bt_statut = 1
               AND bt_date <= '.date('YmdHis');
    try {
        $result = $GLOBALS['db_handle']->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return ((bool)DISPLAY_PHP_ERRORS) ? 'Error addon_readmore(): '.$e->getMessage() : '';
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
        return ((bool)DISPLAY_PHP_ERRORS) ? 'Error fetch content addon_readmore(): '.$e->getMessage() : '';
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
        $link = $GLOBALS['racine'].'?d='.implode('/', $decId).'-'.titre_url($post['bt_title']);
        $html .= "\t".'<li style="background-image: url('.$img.');"><a href="'.$link.'">'.$post['bt_title'].'</a></li>'."\n";
    }
    $html .= '</ul>'."\n";

    return $html;
}
