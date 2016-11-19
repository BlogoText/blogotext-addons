<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***

/*
 * =================================================================================
 *
 * /!\ PLEASE READ THIS BEFORE WRITING YOUR OWN ADDONS /!\
 *
 * 1. You can use this official addon to see how it works and get inspired.
 *
 * 2. Ideally, write all in english.
 *
 * 3. Before spreading the world with your addon, make sure it is PSR-2 compliant.
 *    You can download this useful tool to help you:
 *        $ curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar
 *        $ php phpcs.phar --standard=PSR2 -n --colors "<path to the addon>/<addon>.php"
 *
 *        Example with this addon:
 *        $ php phpcs.phar --standard=PSR2 -n --colors addons/calendar/calendar.php
 *
 *    Source of the tool: https://github.com/squizlabs/PHP_CodeSniffer
 *
 * That's it! Enjoy and good luck :)
 *
 * =================================================================================
 */


/*
 * First, you must add a new entry to the global $addons.
 * Keywords accepted:
 *
 * 'tag' => 'plugin_example'
 * (required)
 * This is the addon ID. It must have the same name of this file without ".php".
 * The directory tree has to be "${addon ID}/${addon ID}.php".
 *
 * 'name' => 'Plugin Example'
 * (required)
 * This is the displayed name into back office. You can add translations using an associative array.
 *
 * 'desc' => 'Your plugin description.'
 * (required)
 * This is the addon description. You can add translations using an associative array.
 *
 * 'version' => '1.0.0'
 * (required)
 * Addon version that should follow the SemVer notation: http://semver.org/.
 *
 * 'url' => 'http://example.org/bt-addons/plugin_example'
 * (optional but highly recommended)
 * Addon developer website, for addon support.
 *
 * 'css' => 'style.css'
 * 'css' => array('style1.css', 'style1.css')
 * (optional)
 * CSS files to include. You can specifiy several files using an array of filenames.
 *
 * 'js' => 'script.js'
 * 'js' => array('script1.js', 'script.js')
 * (optional)
 * JS files to include. You can specifiy several files using an array of filenames.
 */


/**
 * for the show case,
 *  1 - edit your /themes/default/list.html
 *      - add {addon_plugin_example} in #body-layout > #sidenav
 *  2 - go to the admin interface / modules / Plugin example / params
 *  3 - play with label for bool and check in your public interface (in the side nav bar)
 */

$GLOBALS['addons'][] = array(
    // the tag of your addon (required)
    'tag' => 'plugin_example',

    // the name, showed in admin/addon (required)
    'name' => array(
        'en' => 'Plugin example',
        'fr' => 'Plugin exemple',
    ),

    // the desc, showed in admin/addon (required)
    'desc' => array(
        'en' => 'Just a showcase... Don\'t use it in prod !',
        'fr' => 'Juste un exemple... Ne pas utiliser en prod !',
    ),

    // the version, showed in admin/addon (required)
    'version' => '1.0.0',

    // if your plugin allow user (admin) to change some params
    'config' => array(
        'exemple_config_1' => array(
            'type' => 'bool',
            'label' => array(
                'en' => 'label for bool',
                'fr' => 'label pour bool'
            ),
            'value' => true,
        ),
        'exemple_config_2' => array(
            'type' => 'int',
            'label' => array(
                'en' => 'label for int',
                'fr' => 'label pour int'
            ),
            'value' => true,
            'value' => 10,
            'value_min' => 1,
            'value_max' => 20,
        ),
        'exemple_config_3' => array(
            'type' => 'text',
            'label' => array(
                'en' => 'label for text',
                'fr' => 'label pour text'
            ),
            'value' => 'There is an exemple.',
        ),
    ),

    /**
     * optional, define your own style
     * css file(s) must be in /addons/{your addon}/
     * 'css' can be a string :
     *    'css' => 'example.css'
     * 'css' can be an array :
     *    'css' => array('style1.css', 'style2.css')
     */
    'css' => 'style.css',

    /**
     * optional, define your own script
     * js file(s) must be in /addons/{your addon}/
     * 'js' can be a string :
     *    'js' => 'example.js'
     * 'js' can be an array :
     *    'js' => array('script1.js', 'script2.js')
     */
    //'js' => 'script.js',
);

/*
 * You must declare the callback function as follow:
 *     function addon_${addon tag}() { ... }
 *
 * Note: if your addon contains only CSS/JS files, no need to define such a function.
 *
 * The function has to return valid HTML data.
 * In this addon, it returns a HTML <table> calendar.
 *
 * If you need more functions, you can declare how many as needed below this one.
 * You should suffix theme with an underscore.
 *
 * You can use BlogoText function and defines.
 */
function addon_plugin_example()
{
    // get the addon conf (if you need)
    $addon_conf = addon_get_conf('plugin_example');

    // do your stuff...
    $html = '<div id="addon_plugin_example">';

    if ($addon_conf['exemple_config_1']['value'] == 1) {
        $html .= 'exemple_config_1 is <span class="true">true !</span>';
    } else {
        $html .= 'exemple_config_1 is <span class="false">false !</span>';
    }

    $html .= '</div>';

    // the {addon_plugin_example} will be replaced by $html
    return $html;
}
