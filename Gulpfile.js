var gulp = require('gulp');
var sass = require('gulp-sass');
var minify = require('gulp-minifier');

gulp.task('sass', function () {
    gulp.src('./src/AppBundle/Resources/sass/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(minify({
            minify: true,
            collapseWhitespace: true,
            conservativeCollapse: true,
            minifyJS: false,
            minifyCSS: true,
            getKeptComment: function (content, filePath) {
                var m = content.match(/\/\*![\s\S]*?\*\//img);
                return m && m.join('\n') + '\n' || '';
            }
        }))
        .pipe(gulp.dest('./web/css'));
});

gulp.task('js', function () {
    return gulp.src('./src/AppBundle/Resources/js/*.js').pipe(minify({
        minify: false,
        collapseWhitespace: true,
        conservativeCollapse: true,
        minifyJS: false,
        minifyCSS: false,
        getKeptComment: function (content, filePath) {
            var m = content.match(/\/\*![\s\S]*?\*\//img);
            return m && m.join('\n') + '\n' || '';
        }
    })).pipe(gulp.dest('./web/js'));
});

gulp.task('updateFoundationJS', function () {
    return gulp.src('./vendor/zurb/foundation/dist/js/foundation.min.js').pipe(gulp.dest('./web/js'));
});

//Watch tasks
gulp.task('default', function () {
    gulp.watch('./src/AppBundle/Resources/sass/*.scss', ['sass']);
    gulp.watch('./src/app/Resources/js/**/*.js', ['js']);
    gulp.watch('./src/AppBundle/Resources/js/**/*.js', ['js']);
    gulp.watch('./src/vendor/zurb/foundation/dist/js/**/*.js', ['updateFoundationJS']);
});
