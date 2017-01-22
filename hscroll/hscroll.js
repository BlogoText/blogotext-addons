// stolen from http://www.tiger-222.fr/?d=2016/10/18/14/00/25-scrollbar-horizontale
function scroll_bar()
{
    'use strict';
    var t = document.querySelector('#scroll-bar'),
        a = document.body.clientHeight,
        n = window.innerHeight,
        g = window.pageYOffset,
        o = g / (a - n) * 100;

    t.style.width = o + '%';
}
window.addEventListener('load', scroll_bar);
window.addEventListener('scroll', scroll_bar);
