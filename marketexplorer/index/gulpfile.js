var gulp = require('gulp'),
plumber = require('gulp-plumber'),
rename = require('gulp-rename');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var imagemin = require('gulp-imagemin'),
cache = require('gulp-cache');
var minifycss = require('gulp-clean-css');
var sass = require('gulp-sass');
var mainBowerFiles = require('main-bower-files');
var clean = require( 'gulp-clean' );
var angularFilesort = require('gulp-angular-filesort');
var runSequence = require('run-sequence');
var purify = require('gulp-purifycss');
var livereload = require('gulp-livereload');

//livereload({ start: true })

//optimize images
gulp.task('images', function(){
    gulp.src('assets/img/**/*')
    .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
    .pipe(gulp.dest('dist/img/'))
    .pipe(livereload());
});


//process styles
gulp.task('styles', function(){
    gulp.src(['assets/sass/style.scss'])
    .pipe(plumber({
        errorHandler: function (error) {
            console.log(error.message);
            this.emit('end');
        }}))
    .pipe(sass())
    .pipe(concat('styles.min.css'))
    .pipe(purify(['application/**/*.js', 'application/**/*.html', 'bower_components/**/*.html']))
    .pipe(minifycss({keepSpecialComments : 0}))
    .pipe(gulp.dest('dist/css/'))
    .pipe(livereload());
});


//move fonts
gulp.task('fonts', function(){
    return gulp.src(['bower_components/bootstrap-sass/assets/fonts/bootstrap/*.*'])
    //.pipe(minifycss({keepSpecialComments : 0}))
    .pipe(gulp.dest('dist/fonts/bootstrap/'))
    .pipe(livereload());
});


//move views
gulp.task('views', function(){
    return gulp.src('application/**/**/*.html')
    .pipe(gulp.dest('dist/app/'))
    .pipe(livereload());
});


//process vendor styles
gulp.task('bower_styles', function() {
    return gulp.src(mainBowerFiles('**/**/*.css'))
    .pipe(plumber({
        errorHandler: function (error) {
            console.log(error.message);
            this.emit('end');
        }}))
    .pipe(concat('libstyles.min.css'))
    .pipe(minifycss({keepSpecialComments : 0}))
    .pipe(gulp.dest('dist/vendor/'))
    .pipe(livereload());
});


//process app scripts
gulp.task('scripts', function(){
    return gulp.src('application/**/**/*.js')
    .pipe(plumber({
        errorHandler: function (error) {
            console.log(error.message);
            this.emit('end');
        }}))
    //.pipe(angularFilesort())
    .pipe(uglify())
    .pipe(concat('app.min.js'))
    .pipe(gulp.dest('dist/app/'))
    .pipe(livereload());
});


//process vendor scripts
gulp.task('bower', function() {
    return gulp.src(mainBowerFiles('**/**/*.js'))
    .pipe(plumber({
        errorHandler: function (error) {
            console.log(error.message);
            this.emit('end');
        }}))
    .pipe(uglify())
    .pipe(concat('libs.min.js'))
    .pipe(gulp.dest('dist/vendor/'))
    .pipe(livereload());
});


//delete dist folder
gulp.task('clean', function () {
    return gulp.src('dist', {read: false})
        .pipe(clean());
});


//gulp.watch("assets/sass/**/*.scss", ['styles']);
//gulp.watch("application/**/*.js", ['scripts']);
//gulp.watch("application/**/*.html", ['views']);


//global watch
gulp.task('watch', function() {
    livereload.listen();
    gulp.watch("assets/sass/**/*.scss", ['styles']);
    gulp.watch("application/**/*.js", ['scripts']);
    gulp.watch("application/**/*.html", ['views']);
});


gulp.task('default', function (cb) {
    runSequence('clean', ['styles', 'scripts', 'bower', 'bower_styles', 'fonts', 'views', 'watch'], cb);
});
