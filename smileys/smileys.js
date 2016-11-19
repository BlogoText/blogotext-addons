// replace smileys string in to emojis in blogotext
// 2016, thuban, <thuban@yeuxdelibad.net>
// Licence MIT

// Edit this array with regex you like
var strtostr= [
    [/(\s|&nbsp;):\)/g,' 😊'],
    [/(\s|&nbsp;):\(/g,' 😞'],
    [/(\s|&nbsp;):D/g,' 😃'],
    [/(\s|&nbsp;):S/g,' 😖'],
    [/(\s|&nbsp;):s/g,' 😖'],
    [/(\s|&nbsp;):P/g,' 😋'],
    [/(\s|&nbsp;):p/g,' 😋'],
    [/(\s|&nbsp;);\)/g,' 😉'],
    [/(\s|&nbsp;);-\)/g,' 😉'],
    [/(\s|&nbsp;):\//g,' 😕'],
    [/(\s|&nbsp;):\|/g,' 😒'],
    [/(\s|&nbsp;):\'\(/g,' 😢'],
    [/(\s|&nbsp;)oO/g,' 😲'],
    [/(\s|&nbsp;)x\.x/g,' 😵'],
    [/(\s|&nbsp;)O:\)/g,' 😇'],
    [/(\s|&nbsp;)\^\^/g,' 😊']
];

// class div where regexp will be applied
var classes_to_replace = ["com-content", "art-content", "post-content"];

// regexp to find tags (no replacement in <pre> and <code>)
var htmlTagRegex =/(<[^>]*>)/g

function convert_smileys()
{
    "use strict";

    // loop in classes
    classes_to_replace.forEach(function (class_) {
        var tochange = document.getElementsByClassName(class_);
        var codecnt = 0;

        var classcnt = 0;
        var div = "";
        for (classcnt = 0; classcnt < tochange.length; classcnt++) {
            div = tochange[classcnt]

            // check if in <code> or <pre>
            var tagArray = div.innerHTML.split(htmlTagRegex);
            var divtxt = "";
            var tagcnt = 0;
            var t = "";
            for (tagcnt = 0; tagcnt < tagArray.length; tagcnt++) {
                t = tagArray[tagcnt];
                if (t.toLowerCase() == "<pre>" || t == "<code>") {
                    codecnt++;
                } else if (t.toLowerCase() == "</pre>" || t == "</code>") {
                    codecnt--;
                }
            
                if (codecnt == 0) {
                    var i;
                    var newtxt = "";
                    for (i = 0; i < strtostr.length; i++) {
                        t = t.replace(strtostr[i][0],strtostr[i][1]);
                    }
                }
                divtxt += t;
            }
            div.innerHTML = divtxt;
        }
    });
}

window.addEventListener('load', convert_smileys, false);
