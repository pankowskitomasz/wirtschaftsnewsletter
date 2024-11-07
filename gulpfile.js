const {src,dest,parallel} = require('gulp');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const minify = require('gulp-minify-css');

function js(){
    return src([
        'public/js/jquery-3.7.1.min.js',
        'public/js/bootstrap.bundle.min.js',
        'public/js/main.js'
    ])
    .pipe(concat('main.min.js'))
    .pipe(uglify())
    .pipe(dest("public/js/"));
}

function css(){
    return src([
        'public/css/bootstrap.min.css',
        'public/css/styles.css'
    ])
    .pipe(concat("styles.min.css"))
    .pipe(minify())
    .pipe(dest("public/css/"));
}

exports.js = js;
exports.css = css;
exports.default = parallel([js,css]);