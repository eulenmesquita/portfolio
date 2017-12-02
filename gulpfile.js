var gulp = require('gulp');
var $ = require('gulp-load-plugins')({rename: {'gulp-rev-delete-original':'revdel', 'gulp-if': 'if'}});

var sass = require('gulp-sass');
var browserSync = require('browser-sync').create();
var clean = require('gulp-clean');
var cleanCSS = require('gulp-clean-css');
var rename = require("gulp-rename");
var uglify = require('gulp-uglify');
var filter = require('gulp-filter');
var gutil = require('gulp-util');
var useref = require('gulp-useref');
var pkg = require('./package.json');



// Compiles SCSS files from /scss into /css
gulp.task('sass', function() {
  return gulp.src('source/scss/freelancer.scss')
    .pipe(sass())
    .pipe(gulp.dest('source/css'))
    .pipe(browserSync.reload({
      stream: true
    }))
});

// Minify compiled CSS
gulp.task('minify-css', ['sass'], function() {
  return gulp.src('source/css/*.css')
    .pipe(cleanCSS({
      compatibility: 'ie8'
    }))
    .pipe(gulp.dest('dist/css'))
});

// Minify custom JS
gulp.task('minify-js', function() {
  return gulp.src('source/js/*.js')
    .pipe(uglify())
    .on('error', function (err) { gutil.log(gutil.colors.red('[Error]'), err.toString()); })
    .pipe(gulp.dest('dist/js'))
});


/* Concatenning js */
gulp.task('useref', function () {
  return gulp.src('source/index.html')
      .pipe($.useref())
      .pipe($.if('*.html', $.inlineSource()))
      .pipe($.if('*.html', $.htmlmin({collapseWhitespace: true})))
      .pipe($.if('*.js', $.uglify()))
      .pipe(gulp.dest('dist'));
});


// Copy vendor files from /node_modules into /vendor
// NOTE: requires `npm install` before running!
gulp.task('copy', function() {
  gulp.src([
      'node_modules/bootstrap/dist/**/*',
      '!**/npm.js',
      '!**/bootstrap-theme.*',
      '!**/*.map'
    ])
    .pipe(gulp.dest('dist/vendor/bootstrap'))

  gulp.src(['node_modules/jquery/dist/jquery.js', 'node_modules/jquery/dist/jquery.min.js'])
    .pipe(gulp.dest('dist/vendor/jquery'))

  gulp.src(['node_modules/jquery.easing/*.js'])
    .pipe(gulp.dest('dist/vendor/jquery-easing'))

  gulp.src(['node_modules/magnific-popup/dist/*'])
    .pipe(gulp.dest('dist/vendor/magnific-popup'))

  gulp.src([
      'node_modules/font-awesome/**',
      '!node_modules/font-awesome/**/*.map',
      '!node_modules/font-awesome/.npmignore',
      '!node_modules/font-awesome/*.txt',
      '!node_modules/font-awesome/*.md',
      '!node_modules/font-awesome/*.json'
    ])
    .pipe(gulp.dest('dist/vendor/font-awesome'))

    gulp.src(['source/{img,vendor,mail,downloads}/**/*','source/index.html','source/.env'], {base: 'source', dot: true})
    .pipe(gulp.dest('dist'));
})

//clean
gulp.task('clean', function() {
  return gulp.src(['dist/{img,js,vendor,mail,downloads}','index.html','dist/[.]?env'], {read: false})
      .pipe(clean());
});

// Default task
gulp.task('default', ['sass', 'minify-css', 'minify-js', 'copy']);
