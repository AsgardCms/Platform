;(function ( $, window, document, undefined ) {
    var pluginName = "keypressAction",
        defaults = {};

    // The actual plugin constructor
    function keypressAction ( element, options ) {
        this.element = element;
        this.settings = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    $.extend(keypressAction.prototype, {
        bindKeyToRoute: function (key, route) {
            Mousetrap.bind([key], function(e) {
                window.location = route;
                return false;
            });
        },
        init: function () {
            var self = this;
            $.each(this.settings.actions, function( index, object ) {
                self.bindKeyToRoute(object.key, object.route);
            });
        }
    });

    $.fn[ pluginName ] = function ( options ) {
        this.each(function() {
            if ( !$.data( this, "plugin_" + pluginName ) ) {
                $.data( this, "plugin_" + pluginName, new keypressAction( this, options ) );
            }
        });

        // chain jQuery functions
        return this;
    };

})( jQuery, window, document );
