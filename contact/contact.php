<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

/**
 * Change log
 *
 * 1.0.4 2018-02-28 @B4rb3rouss
 *  - add some css class followin @remrem suggestions
 *
 * 1.0.3 2018-02-26 @B4rb3rouss
 *  - fix missing tanslation
 *  - fix form action
 *
 * 1.0.2 2018-02-09 @remrem
 *  - add @ on mail()
 */

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
    'version' => '1.0.4',
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
        'object' => 'New contact from your blog ' . $GLOBALS['nom_du_site'] . '.',
        'error_mail' => 'Please enter your mail address.',
        'error_message' => 'Please write something.',
        'error_captcha' => 'Please check captcha',
        'error' => 'An error occured ☹',
        'success' => 'Your message has been send ☺',
        'send' => 'Send ✓',
    );
    if ($GLOBALS['lang']['id'] == "fr") {
        $msgs = array(
            'object' => 'Nouveau message depuis votre blog ' . $GLOBALS['nom_du_site'] . '.',
            'error_mail' => 'Entrez une adresse courriel valide svp',
            'error_message' => 'Écriver un message svp',
            'error_captcha' => 'Erreur de calcul?',
            'error' => 'Une erreur est survenue ☹',
            'success' => 'Votre message a bien été envoyé ☺',
            'send' => 'Envoyer ✓',
        );
    }

    if (isset($_POST['contact_envoi'])) {
        // let's believe it's ok
        $form_proceed = 'tosend';
        $destinataire = $GLOBALS['email'];
        
        // get datas
        $datas['from'] = filter_input(INPUT_POST, 'a_contact_from', FILTER_SANITIZE_SPECIAL_CHARS);
        $datas['message'] = filter_input(INPUT_POST, 'a_contact_message', FILTER_SANITIZE_SPECIAL_CHARS);
        $datas['captcha'] = filter_input(INPUT_POST, 'a_contact_captcha', FILTER_SANITIZE_SPECIAL_CHARS);
        $datas['token'] = filter_input(INPUT_POST, 'a_contact_token', FILTER_SANITIZE_SPECIAL_CHARS);

        // check datas
        if (!IsEmail($datas['from'])) {
            $errors['from'] = $msgs['error_mail'];
            $form_proceed = 'error';
        }
        if (empty($datas['message'])) {
            $errors['message'] = $msgs['error_message'];
            $form_proceed = 'error';
        }
        if (empty($datas['captcha']) || empty($datas['token'])) {
            $errors['captcha'] = $msgs['error_captcha'];
            $form_proceed = 'error';
        } else {
            $ua = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
            if ($datas['token'] != sha1($ua.$datas['captcha'])) {
                $errors['captcha'] = $msgs['error_captcha'];
                $form_proceed = 'error';
            }
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

            $ok = @mail($destinataire, $msgs['object'], $datas['message'], $headers);
            if (!$ok) {
                $form_proceed = 'error';
            }
        }
    }
    // display form
    $html = '<div id="contact_addon">';

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
        $html .= '<div>'.$msgs['error'].'</div>';
        foreach ($errors as $e) {
            if (!empty($e)) {
                $html .= '<div>'.$e.'</div>';
            }
        }
        $html .= '</div>';
        $html .= '<div id="contact_form_addon" class="contact_visible">';
    }

    if ($form_proceed == 'empty') {
        $html .= '<div id="contact_form_addon" class="contact_hidden">';
    }

    $html .= '<div class="contact_title">'.addon_get_setting('contact', 'title').'</div>';
    $html .= '<form id="contact" method="POST" action="'.$_SERVER['REQUEST_URI'].'">';
    $html .= '<div class="contact_content">';
    $html .= '<label for="a_contact_message">Message :</label><textarea name="a_contact_message" cols="10" rows="5"></textarea>';
    $html .= '</div>';
    $html .= '<div class="contact_email">';
    $html .= '<label for="email">Email : </label><input required type="email" name="a_contact_from" />';
    $html .= '</div>';
    $html .= '<div class="contact_captcha">';
    $html .= '<label>'.$GLOBALS['lang']['label_dp_captcha'].$GLOBALS['captcha']['x'].' &#x0002B; '.en_lettres($GLOBALS['captcha']['y']).' = ';
    $html .= '<input type="number" name="a_contact_captcha" autocomplete="off" value="" class="text" type="int"/></label>';
    $html .= '</div>';
    $html .= hidden_input('a_contact_token', $GLOBALS['captcha']['hash']);
    $html .= '<div class="contact_submit" ><input type="submit" name="contact_envoi" value="'.$msgs['send'].'" /></div>';
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
