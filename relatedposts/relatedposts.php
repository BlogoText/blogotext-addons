<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
#
# 2016 Mickaël S. <contact@tiger-222.fr>
# *** LICENSE ***

$GLOBALS['addons'][] = array(
    'tag' => 'relatedposts',
    'version' => '1.0.0',
    'url' => 'http://www.tiger-222.fr/',

    'name' => array(
        'en' => 'Related posts',
        'fr' => 'Articles en relation',
    ),
    'desc' => array(
        'en' => 'Show a list of posts in relation of the current displayed one.',
        'fr' => 'Afficher une liste d\'articles en relation avec celui en cours de lecture.',
    ),

    'config' => array(
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
            'value' => 'Découvrez d\'autres articles de la même trempe que %s :',
        ),
    ),

    'css' => 'style.css',
);

// Include the posts list.
// To use in theme/$theme/post.html.
function addon_relatedposts()
{
    $conf = addon_get_conf('relatedposts');
    $nb_posts = $conf['nb_posts']['value'];

    // 1. Get the article ID
    $id_ = !empty($_GET['d']) ? $_GET['d'] : null;
    if ($id_ === null) {
        return '';
    }
    if (preg_match('#^\d{4}(/\d{2}){5}#', $id_)) {
        $id = substr(str_replace('/', '', $_GET['d']), 0, 14) + 0;
    } elseif (preg_match('#^\d{14}#', $id_)) {
        $id = substr($_GET['id'], 0, 14) + 0;
    } else {
        return '';
    }

    // 2. Get article tags
    try {
        $sql = $GLOBALS['db_handle']->prepare(
            'SELECT bt_tags
               FROM articles
              WHERE bt_statut = 1
                    AND bt_id = :id'
        );
        $sql->bindValue(':id', $id, SQLITE3_INTEGER);
        $sql->execute();
        $tags = $sql->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die('Error step 2 addon_relatedposts() : '.$e->getMessage());
    }
    if (!$tags) {
        return '';
    }

    // 3. Find related posts based on a random tag from current article
    $tags = current($tags);
    $tags = explode(', ', $tags['bt_tags']);
    shuffle($tags);
    $tag = current($tags);
    if (!$tag) {
        return '';
    }
    try {
        $sql = $GLOBALS['db_handle']->prepare(
            'SELECT bt_id, bt_title
               FROM articles
              WHERE bt_statut = 1
                    AND bt_id != :id
                    AND bt_tags LIKE :tag'
        );
        $sql->bindValue(':id', $id, SQLITE3_INTEGER);
        $sql->bindValue(':tag', '%'.$tag.'%');
        $sql->execute();
        $related_posts = $sql->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die('Error step 3 addon_relatedposts() : '.$e->getMessage());
    }
    if (!$related_posts) {
        return '';
    }
    shuffle($related_posts);
    $related_posts = array_slice($related_posts, 0, $nb_posts);

    // 4. Generate the ul/li list
    $html = '<div class="related-posts">';
    $html .= '<p>';
        $html .= sprintf($conf['sentence']['value'], '<span class="category">'.htmlentities($tag).'</span>');
    $html .= '</p>';
    $html .= '<ul class="rectangle-list">';
    foreach ($related_posts as $article) {
        $dec_id = decode_id($article['bt_id']);
        $html .= '<li><a href="?d='.implode('/', $dec_id).'-'.titre_url($article['bt_title']).'">'.$article['bt_title'].'</a></li>';
    }
    $html .= '</ul>';
    $html .= '</div>';

    return $html;
}
