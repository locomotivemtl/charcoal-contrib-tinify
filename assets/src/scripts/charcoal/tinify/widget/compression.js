/* global Charcoal,EventSource */
/**
 * Compression widget
 *
 * Useful in handling the Compression EventSource and monitoring compression progress.
 */
;(function () {
    /**
     * `charcoal/tinify/widget/image-compression`
     * Widget_Compression Javascript class
     *
     */
    var Compression = function (data) {
        Charcoal.Admin.Widget.call(this, data);
    };

    Compression.prototype             = Object.create(Charcoal.Admin.Widget.prototype);
    Compression.prototype.constructor = Charcoal.Admin.Widget_Compression;
    Compression.prototype.parent      = Charcoal.Admin.Widget.prototype;

    Compression.prototype.set_opts = function (opts) {
        // Globals
        this._options = opts.options || {};
        this._source = null;
        this._close_callback = opts.close_callback;

        // Elements
        this.$widget  = $(this.element());
        this.$pBar = this.$widget.find('.js-progress-bar');
        this.$pText = this.$widget.find('.js-progress-text');

        return this;
    };

    Compression.prototype.init = function () {
        this._source = new EventSource('tinify/compress/event');

        this._source.addEventListener('CLOSE', function(e) {
            this._source.close();
            this._close_callback();
        }.bind(this));

        this._source.addEventListener('message', function(e) {
            var data = JSON.parse(e.data);

            this.$pBar.css('width', data.progress+'%');
            this.$pText.html(data.text);
        }.bind(this));
    };

    Compression.prototype.destroy = function () {
        console.log('destroy');
        if (this._source) {
            this._source.close();
        }
    };

    Compression.prototype.parse_options = function () {
        var defaultOptions = {};

        this._options = $.extend(true, defaultOptions, this._options);

        return this._options;
    };

    Charcoal.Admin.Widget_Compression = Compression;

}(jQuery, document));
