;(function ($) {
    function jQueryImageCaching(params) {
        var ImageCaching = {
            selector: 'img',
            debugMode: false,
            cachingKeyAttribute: 'data-caching-key',
            sourceKeyAttribute: 'data-src',
            renderCallback: null,
            crossOrigin: 'Anonymous',
            init: function (params) {
                ImageCaching.log('Initialization of ImageCaching');
                for (var param in params) {
                    ImageCaching[param] = params[param];
                    console.log("%c[CACHE LOGGER] Image caching initialized for " + params[param]['selector'] + "!", "color: brown;");
                }

                $(ImageCaching.selector).each(function () {
                    ImageCaching.applyToImage($(this));
                });
            },
            getCookie(cookie) {
                var value = "; " + document.cookie;
                var parts = value.split("; " + cookie + "=");
                if (parts.length === 2) return parts.pop().split(";").shift();
            },
            getCacheKey: function (element) {
                if (element.attr(ImageCaching.cachingKeyAttribute)) {
                    return element.attr(ImageCaching.cachingKeyAttribute);
                } else {
                    return element.attr(ImageCaching.sourceKeyAttribute);
                }
            },
            getCache: function (element) {
                var key = this.getCacheKey(element);
                return this.getCookie(key);
            },
            setCache: function (element, imageData) {
                var key = ImageCaching.getCacheKey(element);
                ImageCaching.log('Set cache', key, imageData, element);
                document.cookie = key + "=" + encodeURIComponent(imageData) + "; expires=Mon, 18 Dec 2102 04:48:00 CEST; path=/"; // save image data
                return true;
            },
            removeCache: function (element) {
                var key = ImageCaching.getCacheKey(element);
                ImageCaching.log('Remove cache', key);
                document.cookie = key + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/"; // delete image data
                return true;
            },
            renderImage: function (element, picture) {
                ImageCaching.log('Rendering...', picture, element);
                element.attr('src', picture);

                if (this.renderCallback) {
                    ImageCaching.log('Render Callback...', element);
                    this.renderCallback(picture, element);
                }
            },
            applyToImage: function (element, force) {
                var cache = null;
                if (!force) {
                    cache = this.getCache(element);
                }

                if (cache) {
                    ImageCaching.log('Image from cache', element);
                    this.renderImage(element, cache);
                } else {
                    var sourceLink = element.attr(ImageCaching.sourceKeyAttribute);
                    var getParamPrefix = "?";
                    if (sourceLink.indexOf('?') > 0) {
                        getParamPrefix = "&";
                    }
                    sourceLink += getParamPrefix + 'cacheTime=' + Date.now();

                    ImageCaching.log('Request to: ' + sourceLink, element);

                    var img = new Image();

                    if (ImageCaching.crossOrigin) {
                        img.setAttribute('crossOrigin', 'Anonymous');
                    }

                    img.onload = function () {
                        ImageCaching.log('Loading completed', img);
                        var canvas = document.createElement('canvas');
                        canvas.width = img.width;
                        canvas.height = img.height;

                        // Copy the image contents to the canvas
                        var ctx = canvas.getContext("2d");
                        ctx.drawImage(img, 0, 0);

                        var imageData = canvas.toDataURL("image/png");
                        ImageCaching.setCache(element, imageData);
                        ImageCaching.renderImage(element, imageData);
                    };
                    img.src = sourceLink;
                }
            },
            refresh: function (itemsSelector) {
                var selector = null;
                if (itemsSelector) {
                    selector = itemsSelector;
                } else {
                    selector = ImageCaching.selector;
                }

                $(selector).each(function () {
                    ImageCaching.applyToImage($(this), true);
                });

            },
            log: function () {
                if (this.debugMode) {
                    console.log(arguments);
                }
            }

        };
        ImageCaching.init(params);
        return ImageCaching;
    }

    $.fn.extend({
        imageCaching: function (options) {
            var params = {selector: this};
            params = $.extend(params, options);

            return new jQueryImageCaching(params);
        }
    });
})(jQuery);