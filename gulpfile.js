var gulp = require('gulp')
var plumber = require('gulp-plumber')
var sass = require('gulp-sass')
var autoprefixer = require('gulp-autoprefixer')
var browserify = require('browserify')
var transform = require('vinyl-transform')
var exec = require('gulp-exec')
var concat = require('gulp-concat')
var $ = require('gulp-load-plugins')()

gulp.task('sass', function () {
  gulp.src('src/**/*.scss')
  .pipe(plumber())
  .pipe(sass())
  .pipe(autoprefixer())
  .pipe(gulp.dest('./site'))
})

gulp.task('js', function () {
  var browserified = transform(function (filename) {
    var b = browserify(filename)
    b.add(filename)
    return b.bundle()
  })
  gulp.src(['src/**/*.js', '!src/**/_*.js'])
  .pipe($.sourcemaps.init({loadMaps: true}))
  .pipe(plumber())
  .pipe(browserified)
  .pipe($.uglify())
  .pipe($.sourcemaps.write('./map'))
  .pipe(gulp.dest('./site'))
})

gulp.task('php', function () {
  gulp.src('src/**/*.php')
  .pipe(gulp.dest('./site'))
})

gulp.task('db:init', function () {
  var options = {
    continueOnError: false,
    pipeStdout: true }
  var reportOptions = {err: false, stderr: true, stdout: true}
  gulp.src(['src/db/initial.sql', 'src/db/seed.sql'])
  .pipe(exec('php <%= file.path %>', options))
  .pipe(exec.reporter(reportOptions))
  .pipe(concat('default.sql'))
  .pipe(gulp.dest('./db'))
})

gulp.task('default', function () {
  gulp.watch('src/**/*.scss', ['sass'])
  gulp.watch('src/**/*.js', ['js'])
  gulp.watch('src/**/*.php', ['php'])
})
