//import autoprefixer from 'gulp-autoprefixer';
//require('dotenv').config();
const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
//const autoprefixer = require('gulp-autoprefixer');

// let autoprefixer;
// const autoprefixerPromise = import('gulp-autoprefixer');
// autoprefixerPromise.then(module => {
//     autoprefixer = module.default;
//     // Now you can use autoprefixer here
// }).catch(error => {
//     // Handle error if import fails
//     console.error('Failed to import autoprefixer:', error);
// });
const sourcemaps = require('gulp-sourcemaps');
const concat = require('gulp-concat');
const util = require('gulp-util');


// Styles
gulp.task('sass', function () {
    return gulp.src([
        "src/scss/style.scss"
    ])
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'compressed',
            sourceComments: false
        }).on('error', sass.logError))
        .pipe(util.noop())
        //.pipe(autoprefixer())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('build/css'));
});

// Scripts
gulp.task('scripts', function () {
    return gulp.src([
        "src/js/script.js"
    ])
        .pipe(sourcemaps.init())
        .pipe(concat('scripts.min.js'))
        .pipe(util.noop())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('build/js'));
});



// Watch
gulp.task('watch', function (done) {
    gulp.watch(['src/scss/'], gulp.series('sass'));
    //gulp.watch(['assets/img/'], gulp.series('images'));
    gulp.watch(['src/js/'], gulp.series('scripts'));
});


// Default task
gulp.task('build', gulp.series('scripts', 'sass'));
gulp.task('default', gulp.series('build', 'watch'));