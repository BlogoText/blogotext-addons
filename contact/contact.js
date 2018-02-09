function showhide(id)
{
    'use strict';
    var e = document.getElementById(id);
    e.style.display = (e.style.display == "block") ? "none" : "block";
    var b = document.getElementById('contact_addon_button');
    b.style.display = "none";

}
