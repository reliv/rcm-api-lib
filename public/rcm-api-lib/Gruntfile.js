module.exports = function (grunt) {

    // Project configuration.
    grunt.initConfig(
        {
            pkg: grunt.file.readJSON('package.json'),
            uglify: {
                dist : {
                    options: {
                        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
                        mangle: false,
                        sourceMap: true
                    },
                    files: {
                        'dist/<%= pkg.name %>.min.js': [
                            'src/rcm-api-lib.js',
                            'src/rcm-api-lib-api-params.js',
                            'src/rcm-api-lib-api-data.js',
                            'src/rcm-api-lib-api-message.js',
                            'src/rcm-api-lib-service.js',
                            'src/rcm-api-lib-message-service.js',
                            'src/rcm-api-lib-message-directive.js',
                            'src/rcm-api-lib-message-global.js'
                        ]
                    }
                }
            },
            concat: {
                options: {
                },
                dist: {
                    files: {
                        'dist/<%= pkg.name %>.js': [
                            'src/rcm-api-lib.js',
                            'src/rcm-api-lib-api-params.js',
                            'src/rcm-api-lib-api-data.js',
                            'src/rcm-api-lib-api-message.js',
                            'src/rcm-api-lib-service.js',
                            'src/rcm-api-lib-message-service.js',
                            'src/rcm-api-lib-message-directive.js',
                            'src/rcm-api-lib-message-global.js'
                        ]
                    }
                }
            }
        }
    );

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');

    // Default task(s).
    grunt.registerTask('default', ['uglify', 'concat']);
};
