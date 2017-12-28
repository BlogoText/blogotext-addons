<?php

/**
 * Changelog
 *
 * 1.0.0 2017-12-28 thuban
 */
 
$declaration = array(
    // the tag of your addon (required)
    'tag' => 'about',

    // the name, showed in admin/addon (required)
    'name' => array(
        'en' => 'About',
        'fr' => 'À propos',
    ),

    // the desc, showed in admin/addon (required)
    'desc' => array(
        'en' => 'Description about your blog, available on <a href="../?about">about page</a>',
        'fr' => 'À propos de votre blog, disponible sur <a href="../?about">la page à propos</a>',
    ),
    'settings' => array(
        'title' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Title',
                'fr' => 'Titre'
            ),
            'desc' => array(
                'en' => 'Title of about page',
                'fr' => 'Titre de la page à-propos',
            ),
            'value' => 'À propos',
        ),
        'description' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Description',
                'fr' => 'Description'
            ),
            'desc' => array(
                'en' => 'Description in about page',
                'fr' => 'Description dans la page à-propos',
            ),
            'value' => 'Ceci est une description de mon super blog',
        ),

        'license' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'License',
                'fr' => 'Licence'
            ),
            'desc' => array(
                'en' => 'The content of this website is under...',
                'fr' => 'Le contenu de ce site est sous licence...',
            ),
            'value' => 'CC BY-SA'
        ),
        'licenseurl' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'License URL',
                'fr' => 'Lien de la licence'
            ),
            'desc' => array(
                'en' => 'Link to license description...',
                'fr' => 'Lien vers la description de la licence...',
            ),
            'value' => 'https://creativecommons.org/licenses/by-sa/4.0/'
        ),


    ),


    // the version, showed in admin/addon (required)
    'version' => '1.0.0',
    'compliancy' => '3.7',
    'url' => 'https://yeuxdelibad.net/Blog',
    'hook-push' => array(
            'conversion_theme_addons_end' => array(
                    'callback' => 'a_show_about',
                    'priority' => 100
                )
        ),
);

function a_show_about($datas)
{

    if (!$datas || !is_array($datas)) {
        return $datas;
    }
    if (isset($_GET['about'])) {
        $title = addon_get_setting('about', 'title');
        $description = addon_get_setting('about', 'description');
        $license = addon_get_setting('about', 'license');
        $licenseurl = addon_get_setting('about', 'licenseurl');

        $html = '<article class="article post"> <header class="art-title post-title">'."\n";
        $html .= '<h1 class="entry-title">'.$title.'</h1></header>'."\n";
        $html .= '<div class="art-content post-content entry-content" itemprop="articleBody">'."\n";
        $html .= $description."\n";
        $html .= '</div>'."\n";
        $html .= '<footer class="art-footer post-footer entry-footer">'."\n";
        $html .= '<a href="'.$licenseurl.'">'.$license.'</a>'."\n";
        $html .= "</footer>\n</article>\n";

        $doc = new DomDocument;

        // set error level (avoid error for href="...&..")
        $internalErrors = libxml_use_internal_errors(true);

        $libxml_compat = (LIBXML_VERSION >= '20708');
        if ($libxml_compat) {
            $doc->loadHTML($datas[1], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        } else {
            $doc->loadHTML('<div>'.$datas[1].'</div>');
            $libxml_compat = false;
        }


        $main = $doc->getElementById('main');

        $newmain = $doc->createElement('main');
        $newmain->setAttribute("id", "main");
        $tmpDoc = new DOMDocument();
        $tmpDoc->loadHTML($html);
        foreach ($tmpDoc->getElementsByTagName('body')->item(0)->childNodes as $node) {
            $node = $doc->importNode($node, true);
            $newmain->appendChild($node);
        }

        // Restore error level
        libxml_use_internal_errors($internalErrors);
        $main->parentNode->replaceChild($newmain, $main);

        $datas[1] = $doc->saveHTML();
    }
    return $datas;
}
