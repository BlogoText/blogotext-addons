function start_echo()
{
    'use strict';
    echo.init({
        offset: 100,
        throttle: 250,
        unload: false,
        callback: function (element, op) {
            if (op === 'load') {
                element.className = element.className.replace(/(^|\s+)lazy-load(\s+|$)/, '$1lazy-loaded$2');
            }
        }
    });
}
window.onload = start_echo();
