var gulp = require('gulp'),
concat = require('gulp-concat'),
uglify = require('gulp-uglify'),
minify = require('gulp-minify-css'),
imagemin = require('gulp-imagemin'),
connect = require('gulp-connect'),
plumber = require('gulp-plumber'),
clean = require('gulp-clean'),
sass = require('gulp-sass');

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
    'assets/luna/styles/stroke-icons/style.css'/*,
'assets/luna/styles/style.css'*/],

    app: ['assets/luna/scripts/luna.js',
    'assets/js/toastr_options.js',
    'assets/js/pace_options.js',
    'assets/js/app.js',
    'assets/js/login.js',
    'assets/js/header.js',
    'assets/js/assets-app.js',
    'assets/js/contracts-app.js',
    'assets/js/dashboard-app.js',
    'assets/js/marketorders-app.js',
    'assets/js/nwtracker-app.js',
    'assets/js/statistics-app.js',
    'assets/js/stocklists-app.js',
    'assets/js/profits-app.js',
    'assets/js/traderoutes-app.js',
    'assets/js/tradesimulator-app.js',
    'assets/js/transactions-app.js',
    'assets/js/citadeltax-app.js',
    'assets/js/settings-app.js',
    'assets/js/apikeymanagement-app.js',
    'assets/js/loginapikeymanagement-app.js',
    'assets/js/recovery-app.js'],

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
    'assets/vendor/jquery-countTo/jquery.countTo.js',
    'assets/vendor/jquery-countdown/jquery.plugin.min.js',
    'assets/vendor/owl-carousel/owl.carousel.min.js',
    'assets/vendor/isotope/isotope.pkgd.min.js',
    'assets/vendor/jquery-magnificPopup/jquery.magnific-popup.min.js',
    'assets/rhijani/slideshow/js/main.js',
    'assets/rhijani/slideshow/js/animation.js',
    'assets/rhijani/main/js/component/portfolio.js',
    'assets/rhijani/main/js/component/animation.js',
    'assets/js/home.js'
    ],

    img: ['assets/rhijani/main/img/*',
    'assets/rhijani/main/img/*/*']

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
    gulp.src(paths.js

        )
    .pipe(concat('apps.js'))
    //.pipe(uglify())
    .pipe(gulp.dest('dist/js'))
    .pipe(connect.reload());
});

//app files
gulp.task('uglify', function(){
    gulp.src(paths.app

        )
    //.pipe(uglify())
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
    gulp.src(paths.home_css
        )
    .pipe(concat('styles.css'))
    .pipe(minify())
    .pipe(gulp.dest('dist/home/styles/css'))
    .pipe(connect.reload());
});

//homejs
gulp.task('home_js', function(){
    gulp.src(paths.home_js

        )
    .pipe(concat('apps.js'))
    .pipe(uglify())
    .pipe(gulp.dest('dist/home/js'))
    .pipe(connect.reload())
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

    .pipe(gulp.dest('dist/home/styles/fonts'))
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

gulp.task('default',['js','css', 'sass', 'uglify', 'watch', 'home_js', 'home_css', 'img', 'connect', 'home_fonts', 'fonts'], function () {

});