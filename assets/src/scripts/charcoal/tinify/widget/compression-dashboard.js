/* global Charcoal */
/**
 * Compression Dashboard widget
 *
 * Useful in displayed compression and api data.
 */
;(function () {
    /**
     * `charcoal/tinify/widget/image-compression`
     * Widget_Compression_Dashboard Javascript class
     *
     */
    var Compression_Dashboard = function (data) {
        Charcoal.Admin.Widget.call(this, data);
    };

    Compression_Dashboard.prototype             = Object.create(Charcoal.Admin.Widget.prototype);
    Compression_Dashboard.prototype.constructor = Charcoal.Admin.Widget_Compression_Dashboard;
    Compression_Dashboard.prototype.parent      = Charcoal.Admin.Widget.prototype;

    Compression_Dashboard.prototype.set_opts = function (opts) {
        // Globals
        this._opts = opts;

        var opts = this.opts() || {};
        this._options = opts.options || {};

        return this;
    };

    Compression_Dashboard.prototype.init = function () {
        // Elements
        this.$widget  = this.element();

        this.$widget.on('click.compression', '.js-optimize-btn',this.on_optimize.bind(this));
    };

    Compression_Dashboard.prototype.on_optimize = function () {
        var dialogOpts = {
            size:           BootstrapDialog.SIZE_NORMAL,
            widget_type:    'charcoal/tinify/widget/compression',
            dialog_options: {
                onhide: function() {
                    if (this._wid !== undefined) {
                        Charcoal.Admin.manager().get_widget(this._wid).destroy();
                        Charcoal.Admin.manager().remove_component('widgets',this._wid);
                    }
                }.bind(this),
                onhidden: function () {
                    $.each(Charcoal.Admin.manager().components.widgets, function (ident, widget) {
                        widget.reload();
                    });
                }
            }
        };

        var dialog = this.dialog(dialogOpts, function (response) {
            if (response.success) {
                if (!response.widget_id) {
                    return false;
                }

                this._wid = response.widget_id;

                Charcoal.Admin.manager().add_widget({
                    id: response.widget_id,
                    type: 'charcoal/tinify/widget/compression'
                });

                // Dangerous to re-render multiple times if not removed.
                Charcoal.Admin.manager().render();
            }
        }.bind(this));
    };

    Compression_Dashboard.prototype.parse_options = function () {
        var defaultOptions = {};

        this._options = $.extend(true, defaultOptions, this._options);

        return this._options;
    };

    Compression_Dashboard.prototype.destroy = function () {
        this.$widget.off('compression');
    };

    Charcoal.Admin.Widget_Compression_Dashboard = Compression_Dashboard;

}(jQuery, document));
