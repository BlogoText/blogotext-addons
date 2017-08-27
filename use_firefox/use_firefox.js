"use strict";

var modal = document.getElementById('use_ffx_modal');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("use_ffx_close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

if (navigator.userAgent.toLowerCase().indexOf('firefox') == -1) {
    modal.style.display = "block";
}
