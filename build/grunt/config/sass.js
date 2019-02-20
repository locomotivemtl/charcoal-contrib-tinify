module.exports = {
    options: {
        sourceMap:   false,
        outputStyle: 'expanded'
    },
    app: {
        files: {
            '<%= paths.css.dist %>/charcoal.tinify.css': '<%= paths.css.src %>/**/charcoal.tinify.scss'
        }
    },
    vendors: {
        files: {
            '<%= paths.css.dist %>/charcoal.tinify.vendors.css': '<%= paths.css.src %>/**/charcoal.tinify.vendors.scss'
        }
    }
};
