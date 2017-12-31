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

    'url' => 'http://yeuxdelibad.net',
    // the version, showed in admin/addon (required)
    'version' => '1.0.0',
    'compliancy' => '3.7',
    'css' => 'style.css',
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

    $html  = '<a href="javascript:showhide(\'contact_form_addon\')">';
    $html .= addon_get_setting('contact', 'label');
    $html .= '</a>';
    $html .= '<div id="contact_form_addon" style="display:none;">';
    $simple_URI = parse_url($GLOBALS['racine'])['path'];
    $html .= '<form id="contact" method="post" action="' . $simple_URI .'/addons/contact/contact.php">';
    $html .= '<p><label for="objet">Objet :</label><input required type="text" id="objet" name="objet" /></p>';
    $html .= '<p><label for="message">Message :</label><textarea id="message" name="message" cols="10" rows="5"></textarea></p>';
    $html .= '<p><label for="email">Email :</label><input required type="email" id="email" name="email" /></p>';
    $html .= '<div style="text-align:center;"><input type="submit" name="envoi" value="Envoyer ✓" /></div>';
    $html .= '</form>';
    $html .= '</div>';

    $html .= '<script type="text/javascript">';
    $html .= 'function showhide(id) {';
    $html .= '    var e = document.getElementById(id);';
    $html .= 'e.style.display = (e.style.display == "block") ? "none" : "block";';
    $html .= '}';
    $html .= '</script>';

    return $html;
}

if (isset($_POST['envoi'])) {
    require_once '../../config/prefs.php';
    $destinataire = $GLOBALS['email'];
    
    $message_envoye = "Message send ☺ <br />
    Back to the site in 1 sec";
    $message_envoye .= '<script language="JavaScript" type="text/javascript">
    setTimeout("window.history.go(-1)",1000);
    </script>
    ';
    $message_non_envoye = "Something went wrong ☹";
    $message_formulaire_invalide = "Did you fill every field before sending your mail?";
    
    // formulaire envoyé, on récupère tous les champs.
    $email   = (isset($_POST['email']))   ? Rec($_POST['email'])   : '';
    $objet   = (isset($_POST['objet']))   ? Rec($_POST['objet'])   : '';
    $message = (isset($_POST['message'])) ? Rec($_POST['message']) : '';
 
    // On va vérifier les variables et l'email ...
    $email = (IsEmail($email)) ? $email : ''; // soit l'email est vide si erroné, soit il vaut l'email entré
 
    if (($email != '') && ($objet != '') && ($message != '')) {
        // les 4 variables sont remplies, on génère puis envoie le mail
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: <'.$email.'>' . "\r\n" .
                'Reply-To:'.$email. "\r\n" .
                'Content-Type: text/plain; charset="utf-8"; DelSp="Yes"; format=flowed '."\r\n" .
                'Content-Disposition: inline'. "\r\n" .
                'Content-Transfer-Encoding: 7bit'." \r\n" .
                'X-Mailer:PHP/'.phpversion();
    
        // Remplacement de certains caractères spéciaux
        $message = str_replace("&#039;", "'", $message);
        $message = str_replace("&#8217;", "'", $message);
        $message = str_replace("&quot;", '"', $message);
        $message = str_replace('<br>', '', $message);
        $message = str_replace('<br />', '', $message);
        $message = str_replace("&lt;", "<", $message);
        $message = str_replace("&gt;", ">", $message);
        $message = str_replace("&amp;", "&", $message);
 
        // Envoi du mail
        if (mail($destinataire, $objet, $message, $headers)) {
            echo '<p>'.$message_envoye.'</p>';
        } else {
            echo '<p>'.$message_non_envoye.'</p>';
        };
    } else {
        // une des 3 variables (ou plus) est vide ...
        echo '<p>'.$message_formulaire_invalide.'</p>'."\n";
    };
}
/*
    * cette fonction sert à nettoyer et enregistrer un texte
    */
function Rec($text)
{
    $text = htmlspecialchars(trim($text), ENT_QUOTES);
    if (1 === get_magic_quotes_gpc()) {
        $text = stripslashes($text);
    }

    $text = nl2br($text);
    return $text;
};

/*
    * Cette fonction sert à vérifier la syntaxe d'un email
    */
function IsEmail($email)
{
    $value = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
    return (($value === 0) || ($value === false)) ? false : true;
}
