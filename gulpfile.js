var gulp            = require('gulp'),
    angularFilesort = require('gulp-angular-filesort'),
    mainBowerFiles  = require('main-bower-files'),
    plumber         = require('gulp-plumber'),
    babel           = require('gulp-babel'),
    minifycss       = require('gulp-clean-css'),
    cache           = require('gulp-cache'),
    concat          = require('gulp-concat'),
    uglify          = require('gulp-uglify'),
    minify          = require('gulp-minify-css'),
    imagemin        = require('gulp-imagemin'),
    connect         = require('gulp-connect'),
    plumber         = require('gulp-plumber'),
    clean           = require('gulp-clean'),
    purify          = require('gulp-purifycss'),
    runSequence     = require('run-sequence'),
    livereload      = require('gulp-livereload'),
    sass            = require('gulp-sass');

var paths = {
    js: ['assets/vendor/pacejs/pace.min.js',
         'assets/vendor/jquery/dist/jquery.min.js',
         'assets/vendor/datatables/datatables.min.js',
         'assets/vendor/bootstrap/js/bootstrap.min.js',
         'assets/vendor/toastr/toastr.min.js',
         'assets/vendor/sparkline/index.js',
         'assets/vendor/jquery-ui/jquery-ui.min.js',
         'assets/luna/scripts/luna.js',
         'assets/js/toastr_options.js',
         'assets/js/pace_options.js',
         'assets/js/app.js',
         'assets/js/header.js'],
    /*'assets/js/*.js'],*/
    css: ['assets/luna/styles/fontawesome/css/font-awesome.css',
          'assets/vendor/animate.css/animate.css',
          'assets/luna/styles/bootstrap/css/bootstrap.css',
          'assets/vendor/toastr/toastr.min.css',
          'assets/vendor/datatables/datatables.min.css',
          'assets/luna/styles/pe-icons/pe-icon-7-stroke.css',
          'assets/luna/styles/pe-icons/helper.css',
          'assets/luna/styles/stroke-icons/style.css'
    ],
    app_core: ['assets/luna/scripts/luna.js',
               'assets/js/app.js'],
    app: ['assets/js/*.js', '!assets/js/app.js'],
    home_css: ['assets/vendor/bootstrap/css/bootstrap.css',
               'assets/vendor/fontawesome/css/font-awesome.css',
               'assets/vendor/animate.css/animate.css',
               'assets/vendor/owl-carousel/owl.carousel.css',
               'assets/vendor/owl-carousel/owl.theme.css',
               'assets/vendor/jquery-magnificPopup/magnific-popup.css',
               'assets/rhijani/main/css/component/component.css',
               'assets/rhijani/slideshow/css/rinjani.css'],
    home_fonts: ['assets/vendor/fontawesome/fonts/*.*'],
    fonts: ['assets/luna/styles/pe-icons/*.*',
            'assets/luna/styles/stroke-icons/*.*',
            'assets/luna/styles/fontawesome/fonts/*.*',
            'assets/luna/styles/bootstrap/fonts/*.*',],
    home_js: ['assets/vendor/jquery/dist/jquery.min.js',
              'assets/vendor/jquery-easing/jquery.easing.min.js',
              'assets/vendor/bootstrap/js/bootstrap.min.js',
              'assets/vendor/detectmobilebrowser/detectmobilebrowser.js',
              'assets/vendor/smartresize/smartresize.js',
              'assets/vendor/jquery-backstretch/jquery.backstretch.min.js',
              'assets/vendor/jquery-sticky/jquery.sticky.js',
              'assets/vendor/jquery-inview/jquery.inview.min.js',
    /*'assets/vendor/jquery-countTo/jquery.countTo.js',
    'assets/vendor/jquery-countdown/jquery.plugin.min.js',*/
              'assets/vendor/owl-carousel/owl.carousel.min.js',
              'assets/vendor/isotope/isotope.pkgd.min.js',
              'assets/vendor/jquery-magnificPopup/jquery.magnific-popup.min.js',
              'assets/rhijani/slideshow/js/main.js',
              'assets/rhijani/slideshow/js/animation.js',
              'assets/rhijani/main/js/component/portfolio.js',
              'assets/rhijani/main/js/component/animation.js',
              'assets/js/home.js'
    ],
    me_base: 'marketexplorer/index/',
    img: ['assets/rhijani/main/img/*', 'assets/rhijani/main/img/*/*']
};

//sass
gulp.task('sass', function(){
    gulp.src(['assets/luna/styles/sass/style.scss'])
    .pipe(plumber({
        errorHandler: function (error) {
          console.log(error.message);
          this.emit('end');
      }}))
    .pipe(sass())
    .pipe(concat('theme.min.css'))
    .pipe(minify())
    .pipe(gulp.dest('dist/luna/styles'));
});

//delete production files
gulp.task('clean', function () {
    return gulp.src('dist', {read: false})
    .pipe(clean());
});

//css
gulp.task('css', function(){
    gulp.src(paths.css)
    .pipe(concat('styles.css'))
    .pipe(minify())
    .pipe(gulp.dest('dist/luna/styles'))
    .pipe(connect.reload());
});

//js dependencies
gulp.task('js', function(){
    gulp.src(paths.js)
    .pipe(concat('apps.js'))
    .pipe(uglify())
    .pipe(gulp.dest('dist/js'))
    .pipe(connect.reload());
});

//app files
gulp.task('uglify', function(){
    gulp.src(paths.app_core.concat(paths.app))
    .pipe(babel())
    .pipe(uglify())
    .pipe(gulp.dest('dist/js/apps'))
    .pipe(connect.reload());
});

//fonts
gulp.task('fonts', function() {
    gulp.src(paths.fonts, {base: './assets/luna/styles'})
    .pipe(gulp.dest('dist/luna/styles'));
});

/*Homepage tasks*/
gulp.task('home_css', function(){
    gulp.src(paths.home_css)
    .pipe(concat('styles.css'))
    .pipe(minify())
    .pipe(gulp.dest('dist/home/styles/css'))
    .pipe(connect.reload());
});

//homejs
gulp.task('home_js', function(){
    gulp.src(paths.home_js)
    .pipe(concat('apps.js'))
    .pipe(uglify())
    .pipe(gulp.dest('dist/home/js'))
    .pipe(connect.reload());
});

//home img
gulp.task('img', () =>
    gulp.src(paths.img)
    .pipe(imagemin())
    .pipe(gulp.dest('dist/img'))
    .pipe(connect.reload())
    );

//home fonts
gulp.task('home_fonts', function() {
    gulp.src(paths.home_fonts)
    .pipe(gulp.dest('dist/home/styles/fonts'));
});

//livereload
gulp.task('connect', function() {
    connect.server({
        livereload: true
    });
});


gulp.task('watch', function() {
    gulp.watch(paths.js, ['js']);
    gulp.watch(paths.css, ['css']);
    gulp.watch('assets/luna/styles/sass/**/*.scss', ['sass']);
    gulp.watch(paths.app, ['uglify']);
    gulp.watch(paths.home_js, ['home_js']);
    gulp.watch(paths.home_css, ['home_css']);
    gulp.watch(paths.img, ['img']);
});


/////////////////////////////////
// marketexplorer project
// /////////////////////////////

//optimize images
gulp.task('me_images', function(){
    gulp.src(paths.me_base + 'assets/img/**/*')
    .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
    .pipe(gulp.dest('dist/me/img/'))
    .pipe(livereload());
});


//process styles
gulp.task('me_styles', function(){
    gulp.src([paths.me_base + 'assets/sass/style.scss'])
    .pipe(plumber({
        errorHandler: function (error) {
            console.log(error.message);
            this.emit('end');
        }}))
    .pipe(sass())
    .pipe(concat('styles.min.css'))
    //.pipe(purify(['application/**/*.js', 'application/**/*.html', 'bower_components/**/*.html']))
    .pipe(minifycss({keepSpecialComments : 0}))
    .pipe(gulp.dest('dist/me/css/'))
    .pipe(livereload());
});


//move fonts
gulp.task('me_fonts', function(){
    return gulp.src([paths.me_base + 'bower_components/bootstrap-sass/assets/fonts/bootstrap/*.*'])
    //.pipe(minifycss({keepSpecialComments : 0}))
    .pipe(gulp.dest('dist/me/fonts/bootstrap/'))
    .pipe(livereload());
});


//move views
gulp.task('me_views', function(){
    return gulp.src(paths.me_base + 'application/**/**/*.html')
    .pipe(gulp.dest('dist/me/app/'))
    .pipe(livereload());
});


//process vendor styles
gulp.task('me_bower_styles', function() {
    return gulp.src(mainBowerFiles('**/**/*.css'))
    .pipe(plumber({
        errorHandler: function (error) {
            console.log(error.message);
            this.emit('end');
        }}))
    .pipe(concat('libstyles.min.css'))
    .pipe(minifycss({keepSpecialComments : 0}))
    .pipe(gulp.dest('dist/me/vendor/'))
    .pipe(livereload());
});


//process app scripts
gulp.task('me_scripts', function(){
    return gulp.src(paths.me_base + 'application/**/*.js')
    //.pipe(angularFilesort())
    .pipe(babel())
    .pipe(uglify())
    .pipe(concat('app.min.js'))
    .pipe(gulp.dest('dist/me/app/'))
    .pipe(livereload());
});


//process vendor scripts
gulp.task('me_bower', function() {
    return gulp.src(mainBowerFiles('**/**/*.js'))
    .pipe(plumber({
        errorHandler: function (error) {
            console.log(error.message);
            this.emit('end');
        }}))
    .pipe(uglify())
    .pipe(concat('libs.min.js'))
    .pipe(gulp.dest('dist/me/vendor/'))
    .pipe(livereload());
});



//global watch
gulp.task('me_watch', function() {
    livereload.listen();
    gulp.watch(paths.me_base + "assets/sass/**/*.scss", ['me_styles']);
    gulp.watch(paths.me_base + "application/**/*.js", ['me_scripts']);
    gulp.watch(paths.me_base + "application/**/*.html", ['me_views']);
});

gulp.task('default', function (cb) {
    runSequence('clean', ['me_styles', 'me_scripts', 'me_bower', 'me_bower_styles', 'me_fonts', 'me_views', 'me_watch', 
        'js','css', 'sass', 'uglify', 'watch', 'home_js', 'home_css', 'img', 'connect', 'home_fonts', 'fonts'], cb);
});