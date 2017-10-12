<?php

function a_hscroll()
{
    $color = addon_get_setting('hscroll', 'barcolor');
    $html = '<div id="scroll-bar"><div id="scroll-bar-inner" style="background:'.$color.'"></div></div>';
    return $html;
}
