# blogotext_light_seo
[POC] url rewrite and sitemap for blogotext

THIS IS FOR TESTING

you need to test with the Proof Of Concept hook system
https://github.com/remrem/blogotext/tree/testing
( based on https://github.com/timovn/blogotext/tree/testing with the "testing" new addon system version ).


## How to use
 - download and install https://github.com/remrem/blogotext/tree/testing
 - put the folder "light_seo" in the blogotext "addons" folder
 - \addons\light_seo\cache\ folder must be readable+writable
 - the plugin must create some folders in your blog directory
    - "article/" and ".htaccess" 
    - "link/" and ".htaccess" 
    - "tag/" and ".htaccess" 
 - plugin try to manage "robots.txt" and "sitemap.xml"
 - make sur of plugin is here and activ in the new admin module in your blogotest (http://your-testing/admin/modules.php)
 - surf on the public side of the blogotext and check the url of the article, tag ...

## Support
 - article URL rewrite
 - RSS + Atom URL rewrite
 - sitemap + robots.txt
 - url rewrite for tag "/tag/tag-name"
 - url rewrite for link "/link/?id=41654654"
 

## to do
 - add more security

## can be done quickly
 - nginx support (must have acces to the nginx config file)
 - bugfix and more testing
 - performance optimisation
 - check sitemap format
 - drop the DirtyScript\FlatDB 
 - more ...