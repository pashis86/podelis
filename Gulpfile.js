'use strict';

var gulp    = require('gulp');
var sass    = require('gulp-sass');
var concat  = require('gulp-concat');
var uglify  = require('gulp-uglify');
var scsslint = require('gulp-scss-lint');

var dir = {
    assets: './src/AppBundle/Resources/',
    dist: './web/',
    npm: './node_modules/'
};

gulp.task('sass', function() {
    gulp.src([
            dir.assets + 'style/main.scss',
            dir.assets + 'style/css/*'
        ])
        .pipe(sass({ outputStyle: 'compressed' , includePaths:[dir.npm] }).on('error', sass.logError))
        .pipe(concat('style.css'))
        .pipe(gulp.dest(dir.dist + 'css'));
});

gulp.task('scripts', function() {
    gulp.src([
            //Third party assets
            dir.npm + 'jquery/dist/jquery.min.js',
            dir.npm + 'bootstrap-sass/assets/javascripts/bootstrap.min.js',
          //  dir.npm + 'jquery.countdown/jquery.countdown.js',

            // Main JS file
            dir.assets + 'scripts/main.js',
            dir.assets + 'scripts/script.js',
            dir.assets + 'scripts.bunttons.js',
            dir.assets + 'scripts/basic.js',
            dir.assets + 'scripts/markup.js',
            dir.assets + 'scripts/codester/*'
        ])
        .pipe(concat('script.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.dist + 'js'));
});

gulp.task('images', function() {
    gulp.src([
            dir.assets + 'images/**'
        ])
        .pipe(gulp.dest(dir.dist + 'images'));
});

gulp.task('background', function() {
    gulp.src([
        dir.assets + 'background/**'
    ])
        .pipe(gulp.dest(dir.dist + 'background'));
});

gulp.task('fonts', function() {
    gulp.src([
        dir.npm + 'bootstrap-sass/assets/fonts/**',
        dir.assets + 'fonts/**'
        ])
        .pipe(gulp.dest(dir.dist + 'fonts'));
});


gulp.task('scss-lint', function() {
    return gulp.src('/scss/*.scss')
        .pipe(scsslint());
});

gulp.task('default', ['sass', 'scripts', 'fonts', 'images', 'background']);
