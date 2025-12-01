module.exports = function (grunt) {
  require("load-grunt-tasks")(grunt);

  grunt.initConfig({
    pkg: grunt.file.readJSON("package.json"),

    sass: {
      options: {
        implementation: require("sass"), // Dart Sass
      },
      dist: {
        files: [
          {
            expand: true,
            cwd: "assets/scss",
            src: ["*.scss"],
            dest: "assets/css",
            ext: ".css",
          },
        ],
      },
    },

    cssmin: {
      target: {
        files: [
          {
            expand: true,
            cwd: "assets/css/",
            src: [
              "*.css",
              "!*.min.css",
              "!rael-admin.css",
              "!rael-frontend.css",
              "!rael-admin.min.css",
              "!rael-frontend.min.css",
            ],
            dest: "assets/css/",
            ext: ".min.css",
          },
          {
            expand: true,
            cwd: "assets/css/frontend/",
            src: ["**/*.css", "!**/*.min.css"],
            dest: "assets/css/frontend/",
            ext: ".min.css",
          },
        ],
      },
    },

    uglify: {
      target: {
        files: [
          {
            expand: true,
            cwd: "assets/js/",
            src: ["*.js", "!*.min.js", "!rae-duplicator-admin.js","!rae-duplicator-admin.min.js"],
            dest: "assets/js/",
            ext: ".min.js",
          },
          {
            expand: true,
            cwd: "assets/js/frontend/",
            src: [
              "**/*.js",
              "!**/*.min.js",
              "!rael-frontend.js",
              "!rael-frontend.min.js",
            ],
            dest: "assets/js/frontend/",
            ext: ".min.js",
          },
          {
            expand: true,
            cwd: "assets/js/widgets/",
            src: [
              "**/*.js",
              "!**/*.min.js",
              "!rael-widgets.js",
              "!rael-widgets.min.js",
            ],
            dest: "assets/js/widgets/",
            ext: ".min.js",
          },
        ],
      },
    },

    clean: {
      build: ["build/"],
    },
    copy: {
      build: {
        files: [
          {
            dot: true,
            expand: true,
            cwd: "./",
            src: [
              "**/*",
              "!node_modules/**",
              "!build/**",
              "!*.zip",
              "!package.json",
              "!package-lock.json",
              "!Gruntfile.js",
              "!composer.json",
              "!composer.lock",
              "!vendor/**",
              "!*.md",
              "!.git/**",
              "!.idea/**",
            ],
            dest: "build/responsive-addons-for-elementor/",
          },
        ],
      },
    },

    // Zip the build folder → versioned ZIP
    zip: {
      release: {
        cwd: "build/responsive-addons-for-elementor/",
        src: ["**/*"],
        dest: "responsive-addons-for-elementor-<%= pkg.version %>.zip",
      },
    },
  });

  grunt.registerTask("build", ["sass", "cssmin", "uglify"]);
  // Release ZIP task
  grunt.registerTask("release", [
    "clean:build",
    "build",
    "copy:build",
    "zip:release",
  ]);
};
