<?php
# *** LICENSE ***
# This file is a addon for BlogoText.
# You can redistribute it under the terms of the MIT / X11 Licence.
# *** LICENSE ***


function a_use_firefox()
{
    $msg = addon_get_setting('use_firefox', 'message');
    $dlmsg = addon_get_setting('use_firefox', 'dlmessage');
    $html = 'lala';

    $html = '<div id="use_ffx_modal" class="use_ffx_modal">
    <div class="use_ffx_modal-content">
        <div class="use_ffx_modal-header">
            <span id="use_ffx_close">&times;</span>
            <h2>'.$msg.'</h2>
        </div>
        <div class="use_ffx_modal-body">
            <p><a target="_blank" href="https://www.mozilla.org/en-US/firefox/new/" title="get firefox">'.$dlmsg.'</a></p>
        </div>
    </div>
</div>';
    return $html;
}
