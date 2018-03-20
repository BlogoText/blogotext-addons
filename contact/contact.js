function a_contact_showhide()
{
    'use strict';

    var i,
        aForms = document.querySelectorAll('.contact_form'),
        aBtns = document.querySelectorAll('.contact_addon_button');

    // show the form
    for (i = 0; i < aForms.length; ++i) {
        aForms[i].style.display = "block";
    }
    // hide the button
    for (i = 0; i < aBtns.length; ++i) {
        aBtns[i].style.display = "none";
    }
}
