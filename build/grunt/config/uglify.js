module.exports = {
    options: {
        banner: '/*! <%= package.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
    },
    app: {
        files: {
            '<%= paths.js.dist %>/charcoal.tinify.min.js': [
                '<%= concat.tinify.dest %>'
            ]
        }
    },
    vendors: {
        files: {
            '<%= paths.js.dist %>/charcoal.tinify.vendors.min.js': [
                '<%= concat.vendors.dest %>'
            ]
        }
    }
};
