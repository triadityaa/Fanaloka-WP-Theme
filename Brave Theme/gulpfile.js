'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('default', function() {
	 gulp.src('sass/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./'))
});

//Watch task
gulp.task('watch',function() {
    gulp.watch('sass/**/*.scss',['default']);
}); 