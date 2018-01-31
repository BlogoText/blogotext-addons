<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

$declaration = array(
    // the tag of your addon (required)
    'tag' => 'contact',

    // the name, showed in admin/addon (required)
    'name' => array(
        'en' => 'Contact',
        'fr' => 'Contact',
    ),

    // the desc, showed in admin/addon (required)
    'desc' => array(
        'en' => 'Add a contact form',
        'fr' => 'Formulaire de contact',
    ),

    'url' => 'https://yeuxdelibad.net',
    // the version, showed in admin/addon (required)
    'version' => '1.0.0',
    'compliancy' => '3.7',
    'css' => 'contact.css',
    'js' => 'contact.js',
    'settings' => array(
        'label' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'Label to display where the form should be.',
                'fr' => 'Texte à afficher là où sera le formulaire de contact.'
            ),
            'value' => '✉ Contact',
        ),
    ),
);

function a_contact()
{

    $html = '<div id="contact_addon">';
    $html = '<div id="contact_addon_button">';
    $html .= '<a href="javascript:showhide(\'contact_form_addon\')">';
    $html .= addon_get_setting('contact', 'label');
    $html .= '</a>';
    $html .= '</div>';
    $html .= '<div id="contact_form_addon">';
    $html .= '<form id="contact" method="post" action="' . URL_ROOT .'/addons/contact/send_contact.php">';
    $html .= '<p><label for="objet">Objet : </label><input required type="text" id="objet" name="objet" /></p>';
    $html .= '<p><label for="message">Message :</label><textarea id="message" name="message" cols="10" rows="5"></textarea></p>';
    $html .= '<p><label for="email">Email : </label><input required type="email" id="email" name="email" /></p>';
    $html .= '<div class="contact_center" ><input type="submit" name="envoi" value="Envoyer ✓" /></div>';
    $html .= '</form>';
    $html .= '</div>';

    return $html;
}

