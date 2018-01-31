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
        'title' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'title over the form',
                'fr' => 'Titre au dessus du formulaire.'
            ),
            'value' => 'Message à l\'auteur : ',
        ),

    ),
);



/*
* Cette fonction sert à vérifier la syntaxe d'un email
*/
function IsEmail($email)
{
    $value = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
    return (($value === 0) || ($value === false)) ? false : true;
}

function a_contact()
{

    // form status
    // 'empty', 'tosend', 'error'
    $form_proceed = 'empty';

    // les données par défault
    $datas = array(
        'from' => '',
        'message' => '',
        'captcha' => '',
    );
    $errors = array(
        'from' => '',
        'message' => '',
        'captcha' => '',
    );

    require_once BT_ROOT.'/config/settings.php';

    // Some translations
    $msgs = array(
        'object' => 'New contact from your blog' . $GLOBALS['nom_du_site'] . '.',
        'error_mail' => 'Please enter your mail address.',
        'error_message' => 'Please write something.',
        'error' => 'An error occured ☹',
        'success' => 'Your message has been send ☺',
    );
    if ($GLOBALS['lang']['id'] == "fr") {
        $msgs = array(
            'object' => 'Nouveau message depuis votre blog' . $GLOBALS['nom_du_site'] . '.',
            'error_mail' => 'Entrez une adresse courriel valide svp',
            'error_message' => 'Écriver un message svp',
            'error' => 'Une erreur est survenue ☹',
            'success' => 'Votre message a bien été envoyé ☺',
        );
    }

    if (isset($_POST['contact_envoi'])) {
        // let's believe it's ok
        $form_proceed = 'tosend';
        $destinataire = $GLOBALS['email'];
        
        // get datas
        $datas['from'] = filter_input(INPUT_POST, 'a_contact_from', FILTER_SANITIZE_SPECIAL_CHARS);
        $datas['message'] = filter_input(INPUT_POST, 'a_contact_message', FILTER_SANITIZE_SPECIAL_CHARS);

        // check datas
        if (!IsEmail($datas['from'])) {
            $errors['from'] = $msgs['error_mail'];
            $form_proceed = 'error';
        }
        if (empty($datas['message'])) {
            $errors['message'] = $msgs['error_message'];
            $form_proceed = 'error';
        }
    
        // send email if no error
        if ($form_proceed == 'tosend') {
            $datas['message'] = htmlspecialchars_decode($datas['message'], ENT_NOQUOTES);
    
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'From: <'.$datas['from'].'>' . "\r\n" .
                    'Reply-To:'.$datas['from']. "\r\n" .
                    'Content-Type: text/plain; charset="utf-8"; DelSp="Yes"; format=flowed '."\r\n" .
                    'Content-Disposition: inline'. "\r\n" .
                    'Content-Transfer-Encoding: 7bit'." \r\n" .
                    'X-Mailer:PHP/'.phpversion();
            $ok = mail($destinataire, $msgs['object'], $datas['message'], $headers);
            if (!$ok) {
                $form_proceed = 'error';
            }
        }
    }
    // display form
    $html = '<div id="contact_addon">';
    $html .= '<h3>'.addon_get_setting('contact', 'title').'</h3>';

    // if succeed
    if ($form_proceed == 'tosend') {
        $html .= '<div id="contact_addon_content" class="contact_success">';
        $html .= $msgs['success'];
        $html .= '</div></div>';
        return $html;
    }

    // error
    if ($form_proceed == 'error') {
        $html .= '<div id="contact_addon_content" class="contact_error">';
        $html .= '<h3>'.$msgs['error'].'</h3>';
        foreach ($errors as $e) {
            if (!empty($e)) {
                $html .= '<p>'.$e.'</p>';
            }
        }
        $html .= '</div>';
        $html .= '<div id="contact_form_addon" class="contact_visible">';
    }

    if ($form_proceed == 'empty') {
        $html .= '<div id="contact_form_addon" class="contact_hidden">';
    }

    //$html .= '<form id="a_contact" method="POST">';
    $html .= '<form id="contact" method="POST" action="'.URL_ROOT.'/index.php">';
    $html .= '<p><label for="a_contact_message">Message :</label><textarea name="a_contact_message" cols="10" rows="5"></textarea></p>';
    $html .= '<p><label for="email">Email : </label><input required type="email" name="a_contact_from" /></p>';
    $html .= '<div class="contact_center" ><input type="submit" name="contact_envoi" value="Envoyer ✓" /></div>';
    $html .= '</form>';
    $html .= '</div>';

    if ($form_proceed == 'empty') {
        $html .= '<button id="contact_addon_button" type="button" onclick="javascript:showhide(\'contact_form_addon\')">';
        $html .= addon_get_setting('contact', 'label');
        $html .= '</button>';
    }
    $html .= '</div>';

    return $html;
}
