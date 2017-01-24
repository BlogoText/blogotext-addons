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
 * 4. Take a look at the official documentation on the GitHub wiki :
 *    FR - Intro aux addons - https://github.com/BoboTiG/blogotext/wiki/[WIP][FR][addon]-introduction-à-la-création-d'un-addon
 *    FR - Intro aux hooks - https://github.com/BoboTiG/blogotext/wiki/[WIP][FR][hook]-introduction-et-informations-utiles
 *    EN - Coming Soon ;)
 * 
 * That's it! Enjoy and good luck :)
 *
 * =================================================================================
 */


/**
 * Changelog
 *
 * 0.1.1 2017-01-24 RemRem
 *  - upd comments
 *
 * 0.1.0 2016-11-28 RemRem, maybe need more work
 *  - upd addon to be BT#160 compliant
 *  - fix #12
 *  - upd current version to 0.X (dev version)
 */


/*
 * First, you must declare your addon
 */

$declaration = array(
    // (required) the tag of your addon
    'tag' => 'plugin_example',

    // (required) the name, showed in admin/addon
    'name' => array(
        'en' => 'Plugin example',
        'fr' => 'Plugin exemple',
    ),

    // (required) the desc, showed in admin/addon
    'desc' => array(
        'en' => 'Just a showcase... Don\'t use it in prod !',
        'fr' => 'Juste un exemple... Ne pas utiliser en prod !',
    ),

    // (required) the version, showed in admin/addon
    'version' => '0.1.1',

    // (required) the compliancy with BT version (major.minor)
    'compliancy' => '3.7',

    // (optional) if your plugin allow user (admin) to change some params
    'settings' => array(
        'exemple_config_1' => array(
            'type' => 'bool',
            'label' => array(
                'en' => 'label for bool',
                'fr' => 'label pour bool'
            ),
            'desc' => array(
                'en' => 'Just a showcase...',
                'fr' => 'Juste un exemple...',
            ),
            'value' => true,
        ),
        'exemple_config_2' => array(
            'type' => 'int',
            'label' => array(
                'en' => 'label for int',
                'fr' => 'label pour int'
            ),
            'desc' => array(
                'en' => 'Just a showcase...',
                'fr' => 'Juste un exemple...',
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
            'desc' => array(
                'en' => 'Just a showcase...',
                'fr' => 'Juste un exemple...',
            ),
            'value' => 'There is an exemple.',
        ),
        'exemple_config_4' => array(
            'type' => 'select',
            'label' => array(
                'en' => 'label for select',
                'fr' => 'label pour select'
            ),
            'desc' => array(
                'en' => 'Just a showcase...',
                'fr' => 'Juste un exemple...',
            ),
            'options' => array(
                'value1' => array(
                                'fr' => 'texte pour la valeur 1',
                                'en' => 'text for value 1',
                            ),
                'value2' => array(
                                'fr' => 'texte pour la valeur 2',
                                'en' => 'text for value 2',
                            ),
            ),
            'value' => 'value2' // default value
        ),
    ),

    /**
     * (optional) define your own style
     * css file(s) must be in /addons/{your addon}/
     * 'css' can be a string :
     *    'css' => 'example.css'
     * 'css' can be an array :
     *    'css' => array('style1.css', 'style2.css')
     */
    'css' => 'style.css',

    /**
     * (optional) define your own script
     * js file(s) must be in /addons/{your addon}/
     * 'js' can be a string :
     *    'js' => 'example.js'
     * 'js' can be an array :
     *    'js' => array('script1.js', 'script2.js')
     */
    //'js' => 'script.js',

    /**
     * (optional) (but recommended)
     * Addon developer website, for addon support.
     */
    'url' => 'http://example.org/bt-addons/plugin_example'
);

/*
 * You must declare the callback function as follow:
 *     function a_${addon tag}() { ... }
 *
 * Note: 
 *   if your addon contains only CSS/JS files or/and use hook without a template tag, 
 *   no need to define this function.
 *
 * The function has to return valid HTML data.
 * In this addon, it returns a HTML <table> calendar.
 *
 * If you need more functions, you can declare how many as needed below this one.
 * You should suffix theme with an underscore.
 *
 * You can use BlogoText function and defines.
 */
function a_plugin_example()
{
    // get the addon conf (if you need)
    $exemple_config_1 = addon_get_setting('plugin_example', 'exemple_config_1');

    // do your stuff...
    $html = '<div id="addon_plugin_example">';

    if ($exemple_config_1 == 1) {
        $html .= 'exemple_config_1 is <span class="true">true !</span>';
    } else {
        $html .= 'exemple_config_1 is <span class="false">false !</span>';
    }

    $html .= '</div>';

    // the {a_plugin_example} will be replaced by $html
    return $html;
}
