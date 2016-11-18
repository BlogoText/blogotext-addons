// replace smileys string in to emojis in blogotext
// 2016, thuban, <thuban@yeuxdelibad.net>
// Licence MIT


// regexp and replacement
var strtostr= [
    [/:\)/g,'😊'],
    [/:\(/g,'😞'],
    [/:D/g,'😃'],
    [/:S/g,'😖'],
    [/:s/g,'😖'],
    [/:P/g,'😋'],
    [/:p/g,'😋'],
    [/;\)/g,'😉'],
    [/;-\)/g,'😉'],
    [/:\//g,'😕'],
    [/:\|/g,'😒'],
    [/:\'\(/g,'😢'],
    [/oO/g,'😲'],
    [/x\.x/g,'😵'],
    [/O:\)/g,'😇'],
    [/\^\^/g,'😊']
];

// class div where regexp will be applied
var classes_to_replace = ["com-content", "art-content", "post-content"];

// regexp to find tags (no replacement in <pre> and <code>
var htmlTagRegex =/(<[^>]*>)/g

// loop in classes
classes_to_replace.forEach(function (class_) {
    var tochange = document.getElementsByClassName(class_);
    var codecnt = 0;

    var classcnt;
    for (classcnt = 0; classcnt < tochange.length; classcnt++) {
        div = tochange[classcnt]

        // check if in <code> or <pre>
        var tagArray = div.innerHTML.split(htmlTagRegex);
        var divtxt = "";
        var tagcnt;
        for (tagcnt = 0; tagcnt < tagArray.length; tagcnt++) {
            t = tagArray[tagcnt];
            console.log(t);
            if (t.toLowerCase() == "<pre>" || t == "<code>") {
                codecnt++;
            } 
            else if (t.toLowerCase() == "</pre>" || t == "</code>") {
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
