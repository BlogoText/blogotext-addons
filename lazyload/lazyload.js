/*!
 * Lazy Load Images without jQuery
 * http://kaizau.github.com/Lazy-Load-Images-without-jQuery/
 *
 * Original by Mike Pulaski - http://www.mikepulaski.com
 * Modified by Kai Zau - http://kaizau.com
 * Modified by thuban - http://yeuxdelibad.net
 */
(function() {
  var addEventListener =  window.addEventListener || function(n,f) { window.attachEvent('on'+n, f); },
      removeEventListener = window.removeEventListener || function(n,f,b) { window.detachEvent('on'+n, f); };

  var lazyLoader = {
    cache: [],

    addObservers: function() {
      addEventListener('scroll', lazyLoader.throttledLoad);
      addEventListener('resize', lazyLoader.throttledLoad);
    },

    removeObservers: function() {
      removeEventListener('scroll', lazyLoader.throttledLoad, false);
      removeEventListener('resize', lazyLoader.throttledLoad, false);
    },

    throttleTimer: new Date().getTime(),

    throttledLoad: function() {
      var now = new Date().getTime();
      if ((now - lazyLoader.throttleTimer) >= 200) {
        lazyLoader.throttleTimer = now;
        lazyLoader.loadVisibleImages();
      }
    },

    loadVisibleImages: function() {
      var scrollY = window.pageYOffset || document.documentElement.scrollTop;
      var pageHeight = window.innerHeight || document.documentElement.clientHeight;
      var range = {
        min: scrollY - 200,
        max: scrollY + pageHeight + 200
      };

      var i = 0;
      while (i < lazyLoader.cache.length) {
        var image = lazyLoader.cache[i];
        var imagePosition = getOffsetTop(image);
        var imageHeight = image.height || 0;

        if ((imagePosition >= range.min - imageHeight) && (imagePosition <= range.max)) {

          image.onload = function() {
            this.className = this.className.replace(/(^|\s+)lazy-load(\s+|$)/, '$1lazy-loaded$2');
          };

          image.src = image.getAttribute('data-src');

          image.removeAttribute('data-src');

          lazyLoader.cache.splice(i, 1);
          continue;
        }

        i++;
      }

      if (lazyLoader.cache.length === 0) {
        lazyLoader.removeObservers();
      }
    },

    init: function() {
      addEventListener('load', function _lazyLoaderInit() {
        var imageNodes = document.querySelectorAll('img[data-src]');

        for (var i = 0; i < imageNodes.length; i++) {
          var imageNode = imageNodes[i];

          // Add a placeholder if one doesn't exist
          //imageNode.src = imageNode.src || lazyLoader.tinyGif;

          lazyLoader.cache.push(imageNode);
        }

        lazyLoader.addObservers();
        lazyLoader.loadVisibleImages();

        removeEventListener('load', _lazyLoaderInit, false);
      });
    }
  }

  lazyLoader.init();
})();

