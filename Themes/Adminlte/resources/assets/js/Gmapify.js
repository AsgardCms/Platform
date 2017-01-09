;(function ( $, window, document, undefined ) {
    var pluginName = "Gmapify",
        defaults = {
        };

    // The actual plugin constructor
    function Gmapify ( element, options ) {
        this.element = element;
        this.settings = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this.map = '';
        this.lat = '';
        this.lng = '';
        this.styles = [{"featureType":"water","stylers":[{"color":"#19a0d8"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"},{"weight":6}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#e85113"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-40}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-20}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"road.highway","elementType":"labels.icon"},{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape","stylers":[{"lightness":20},{"color":"#efe9e4"}]},{"featureType":"landscape.man_made","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"hue":"#11ff00"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"hue":"#4cff00"},{"saturation":58}]},{"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#f0e4d3"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-10}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"simplified"}]}];
        this._name = pluginName;
        this.init();
    }

    // Avoid Gmapify.prototype conflicts
    $.extend(Gmapify.prototype, {
        createMap: function () {
            this.map = new GMaps({
                div: this.element,
                lat: this.lat,
                lng: this.lng,
                styles: this.styles,
                height: '400px'
            });
        },
        initCoordiates: function () {
            this.lat = $(this.element).data('latitude');
            this.lng = $(this.element).data('longitude');
        },
        addMarkerOnMap: function () {
            this.map.addMarker({
                lat: this.lat,
                lng: this.lng,
                title: 'Location center'
            });
        },
        init: function () {
            this.initCoordiates();
            this.createMap();
            this.addMarkerOnMap();
        }
    });

    $.fn[ pluginName ] = function ( options ) {
        this.each(function() {
            if ( !$.data( this, "plugin_" + pluginName ) ) {
                $.data( this, "plugin_" + pluginName, new Gmapify( this, options ) );
            }
        });
        return this;
    };

})( jQuery, window, document );