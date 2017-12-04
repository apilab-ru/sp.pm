//npm install gulp-minify-css
var gulp = require('gulp');
var watch = require('gulp-watch');
//var watch = require('gulp-watch'); // Better Watch
//var autoprefixer = require('gulp-autoprefixer');
var minifyCss = require('gulp-minify-css');
var minify = require("gulp-babel-minify");
var concat = require('gulp-concat');

var path = {
    admin : {
        css : ['src/admin/css/*.css','src/project/css/spinner.css','vendor/chosen/chosen.css','vendor/nestable/nestable.css'],
        js  : ['src/admin/js/*.js','vendor/nestable/jquery.nestable.js']
    }
    
};

gulp.task('css-prod', function() {
    return gulp.src(['src/project/css/*.css','vendor/chosen/chosen.css'])
       .pipe(minifyCss())
       .pipe(concat('build.css'))
       .pipe(gulp.dest('build'));
});

gulp.task('js-prod', function() {
    return gulp.src([
        'src/project/js/*.js',
        'src/admin/js/fileUploader.js',
        'vendor/chosen/chosen.jquery.js'
    ])
       .pipe(minify({
            mangle: {
              keepClassName: true
            }
        }))
       .pipe(concat('build.js'))
       .pipe(gulp.dest('build'));
});

gulp.task('css', function() {
    return gulp.src(['src/project/css/*.css','vendor/chosen/chosen.css'])
       .pipe(concat('build.css'))
       .pipe(gulp.dest('build'));
});

gulp.task('js', function() {
    return gulp.src([
        'src/project/js/*.js',
        'src/admin/js/fileUploader.js',
        'vendor/chosen/chosen.jquery.js'
    ])
    .pipe(concat('build.js'))
    .pipe(gulp.dest('build'));
});


gulp.task('css-admin', function() {
    return gulp.src(path.admin.css)
       .pipe(concat('admin.css'))
       .pipe(gulp.dest('build'));
});

gulp.task('js-admin', function() {
    return gulp.src(path.admin.js)
       .pipe(concat('admin.js'))
       .pipe(gulp.dest('build'));
});

gulp.task('css-admin-prod', function() {
    return gulp.src(path.admin.css)
       .pipe(minifyCss())
       .pipe(concat('admin.css'))
       .pipe(gulp.dest('build'));
});

gulp.task('js-admin-prod', function() {
    return gulp.src(path.admin.js)
       .pipe(minify({
            mangle: {
              keepClassName: true,
              keepFnName   : true
            }
        }))
       .pipe(concat('admin.js'))
       .pipe(gulp.dest('build'));
});
/*
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
*/
gulp.task('admin-prod', ['js-admin-prod','css-admin-prod'])

gulp.task('start-admin', function() {
    return  gulp.watch([
        "src/admin/css/*.css",
        "src/admin/js/*.js",
    ], 
    ['css-admin','js-admin']);
});

gulp.task('watch', function() {
    
    gulp.watch("src/project/js/*.js", ['js']);
    gulp.watch("src/project/css/*.css", ['css']);
    gulp.watch("src/admin/css/*.css", ['css-admin']);
    gulp.watch("src/admin/js/*.js", ['js-admin']);
    
});
