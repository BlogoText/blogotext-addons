<?php

/**
 * Changelog
 *
 * 1.1.0 2017-08-23 Thuban
 */
 
$declaration = array(
    // the tag of your addon (required)
    'tag' => 'showrss',

    // the name, showed in admin/addon (required)
    'name' => array(
        'en' => 'Show RSS ',
        'fr' => 'Montre un flux rss',
    ),

    // the desc, showed in admin/addon (required)
    'desc' => array(
        'en' => 'Show last items of a RSS feed.',
        'fr' => 'Affiche les derniers éléments d\'un flux RSS.',
    ),
    'settings' => array(
        'feedurl' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'URL',
                'fr' => 'URL'
            ),
            'desc' => array(
                'en' => 'Feed URL',
                'fr' => 'Feed URL',
            ),
            'value' => 'https://blogotext.org/blog/rss.php',
        ),
    ),


    // the version, showed in admin/addon (required)
    'version' => '1.1.0',
    'compliancy' => '3.7',
    'url' => 'https://yeuxdelibad.net/Blog/',
);

function a_showrss()
{
    $feedurl = addon_get_setting('showrss', 'feedurl');
    $getrss = $GLOBALS['racine'].'/addons/showrss/getrss.php?q=';
    $html = '
    <script>
	"use strict";
    function showRSS(str, getrss) {
        var xmlhttp = "";
        //https://www.w3schools.com/php/php_ajax_rss_reader.asp
        if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        } else {  // code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function() {
            if (this.readyState==4 && this.status==200) {
                document.getElementById("rssOutput").innerHTML=this.responseText;
            }
        }
        xmlhttp.open("GET",getrss+str,true);
        xmlhttp.send();
    }
    showRSS("'.$feedurl.'","'.$getrss.'");
    </script>
    ';

    $html .= '<div id="rssOutput"></div>';
    return $html;
}
