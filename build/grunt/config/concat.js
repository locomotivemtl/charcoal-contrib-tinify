module.exports = {
    options: {
        separator: ';'
    },
    tinify: {
        src: [
            '<%= paths.js.src %>/**/*.js',
        ],
        dest: '<%= paths.js.dist %>/tinify.js'
    },
    vendors: {
        src:       [
            // Tinify.js
            '<%= paths.npm %>/tinifyjs/dist/tinify.full.min.js',
        ],
        dest:      '<%= paths.js.dist %>/charcoal.tinify.vendors.js',
        separator: "\n"
    }
};
