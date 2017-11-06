var gulp = require('gulp');
var watch = require('gulp-watch');
//var watch = require('gulp-watch'); // Better Watch
//var autoprefixer = require('gulp-autoprefixer');
var minifyCss = require('gulp-minify-css');
var minify = require("gulp-babel-minify");
var concat = require('gulp-concat');

gulp.task('css', function() {
    return gulp.src('src/project/css/*.css')
       .pipe(minifyCss())
       .pipe(concat('build.css'))
       .pipe(gulp.dest('build'));
});

gulp.task('js', function() {
    return gulp.src('src/project/js/*.js')
       .pipe(minify({
            mangle: {
              keepClassName: true
            }
        }))
       .pipe(concat('build.js'))
       .pipe(gulp.dest('build'));
});

gulp.task('css-admin', function() {
    return gulp.src('src/admin/css/*.css')
       .pipe(minifyCss())
       .pipe(concat('admin.css'))
       .pipe(gulp.dest('build'));
});

gulp.task('js-admin', function() {
    return gulp.src('src/admin/js/*.js')
       .pipe(minify({
            mangle: {
              keepClassName: true,
              keepFnName   : true
            }
        }))
       .pipe(concat('admin.js'))
       .pipe(gulp.dest('build'));
});

gulp.task('js-angular', function() {
    return gulp.src([
        'src/angular/angular.min.js',
        //'src/angular/angular-resource.min.js',
        'src/angular/angular-route.min.js',
        'src/angular/ng-table.min.js'
        ])
       .pipe(concat('angular.js'))
       .pipe(gulp.dest('build'));
});

gulp.task('start', function() {
    return  gulp.watch([
        "src/admin/css/*.css",
        "src/admin/js/*.js",
        "src/project/js/*.js",
        "src/project/css/*.css"
    ], 
    ['css','js','css-admin','js-admin']);
});