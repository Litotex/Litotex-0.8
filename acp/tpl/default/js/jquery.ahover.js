/* Copyright (c) 2008 Brian Beck (exogen@gmail.com)
 * MIT (http://www.opensource.org/licenses/mit-license.php) licensed.
 *
 * Version: 1.0
 *
 * Requires:
 *   jQuery 1.2+
 *   Dimensions (http://plugins.jquery.com/project/dimensions)
 */

(function($) {
    $.extend({
        ahover: {
            version: 1.0,
            defaults: {
                toggleSpeed: 75,
                toggleEffect: 'both',
                hoverEffect: null,
                moveSpeed: 250,
                easing: 'swing',
                className: 'ahover'
            },
            effects: {
                'width': {width: 0},
                'height': {height: 0},
                'both': {width: 0, height: 0}
            }
        }
    });
    
    $.fn.extend({
        ahover: function(options) {
            var options = $.extend({}, $.ahover.defaults, options);
            var effect = (
                (typeof options.toggleEffect == 'string') ?
                $.ahover.effects[options.toggleEffect] : options.toggleEffect
            );
            var parent = this.offsetParent();
            return this.hover(
                function(e) {
                    var over = $(this);
                    var overSize = {
                        width: over.outerWidth(),
                        height: over.outerHeight()
                    };
                    var overOffset = over.offset();
                    var parentOffset = parent.offset();

                    var under = $('div.' + options.className, parent).stop();
                    var created = (under.length == 0);
                    if (created) {
                        under = $('<div>&nbsp;</div>')
                            .addClass(options.className)
                            .appendTo(parent).css(overSize);
                    }
                    
                    var underOffset = {
                        left: overOffset.left - parentOffset.left -
                            (under.outerWidth() - under.width()) / 2,
                        top: overOffset.top - parentOffset.top -
                            (under.outerHeight() - under.height()) / 2
                    }

                    if (created) {
                        under.css(underOffset).css(effect).animate(overSize, {
                            queue: false,
                            duration: options.toggleSpeed,
                            easing: options.easing
                        });
                    }
                    else {
                        var underCSS = $.extend({}, overSize, underOffset);
                        under.animate(underCSS, {
                            queue: false,
                            duration: options.moveSpeed,
                            easing: options.easing
                        });
                    }
                    if ($.isFunction(options.hoverEffect)) {
                        under.queue(options.hoverEffect);
                    }
                },
                function(e) {
                    $('div.' + options.className, parent).animate(effect, {
                        queue: false,
                        duration: options.toggleSpeed,
                        easing: options.easing,
                        complete: function() { $(this).remove(); }
                    });
                }
            );
        }
    });
})(jQuery);