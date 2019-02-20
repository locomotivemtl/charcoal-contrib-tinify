module.exports = {
    options: {
        open:      false,
        proxy:     'charcoal-contrib-tinify.test',
        port:      3000,
        watchTask: true,
        notify:    false
    },
    dev: {
        bsFiles: {
            src: [
                '<%= paths.prod %>/**/*'
            ]
        }
    }
};
