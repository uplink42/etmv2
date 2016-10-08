var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var minify = require('gulp-minify-css');
var livereload = require('gulp-livereload');
var imagemin = require('gulp-imagemin');

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
       'assets/js/header.js',
       /*'assets/js/*.js'*/],
    css: ['assets/vendor/fontawesome/css/font-awesome.css',
        'assets/vendor/animate.css/animate.css',
        'assets/vendor/bootstrap/css/bootstrap.css',
        'assets/vendor/toastr/toastr.min.css',
        'assets/vendor/datatables/datatables.min.css',
        'assets/luna/styles/pe-icons/pe-icon-7-stroke.css',
        'assets/luna/styles/pe-icons/helper.css',
        'assets/luna/styles/stroke-icons/style.css',
        'assets/luna/styles/style.css'],
    app: ['assets/js/assets-app.js',
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
          'assets/js/apikeymanagement-app.js'],

    home_css: ['assets/vendor/bootstrap/css/bootstrap.css',
              'assets/vendor/fontawesome/css/font-awesome.css',
              'assets/vendor/animate.css/animate.css',
              'assets/vendor/owl-carousel/owl.carousel.css',
              'assets/vendor/owl-carousel/owl.theme.css',
              'assets/vendor/jquery-magnificPopup/magnific-popup.css',
              'assets/rhijani/main/css/component/component.css',
              'assets/rhijani/slideshow/css/rinjani.css',
              ],
    home_js: ['assets/vendor/jquery/dist/jquery.min.js',
              'assets/vendor/jquery-easing/jquery.easing.min.js',
              'assets/vendor/bootstrap/js/bootstrap.min.js',
              'assets/vendor/detectmobilebrowser/detectmobilebrowser.js',
              'assets/vendor/smartresize/smartresize.js',
              'assets/vendor/jquery-backstretch/jquery.backstretch.min.js',
              'assets/vendor/jquery-sticky/jquery.sticky.js',
              'assets/vendor/jquery-inview/jquery.inview.min.js',
              'assets/vendor/jquery-countTo/jquery.countTo.js',
              //'assets/vendor/jquery-easypiechart/jquery.easypiechart.min.js',
              'assets/vendor/jquery-countdown/jquery.plugin.min.js',
              'assets/vendor/jquery-countdown/jquery.countdown.min.js',
              'assets/vendor/owl-carousel/owl.carousel.min.js',
              'assets/vendor/isotope/isotope.pkgd.min.js',
              'assets/vendor/jquery-magnificPopup/jquery.magnific-popup.min.js',
              //'assets/vendor/jquery-validation/jquery.validate.min.js',
              'assets/rhijani/slideshow/js/main.js',
              'assets/rhijani/slideshow/js/animation.js',
              //'assets/rhijani/main/js/component/bar-chart.js',
              'assets/rhijani/main/js/component/countdown.js',
              'assets/rhijani/main/js/component/counters.js',
              //'assets/rhijani/main/js/component/pie-chart.js',
              'assets/rhijani/main/js/component/portfolio.js',
              'assets/rhijani/main/js/component/animation.js'],
    img: ['assets/rhijani/main/img/*',
          'assets/rhijani/main/img/*/*']

};

gulp.task('css', function(){
   gulp.src(paths.css
)
   .pipe(concat('styles.css'))
   .pipe(minify())
   .pipe(gulp.dest('dist/luna/styles/css'))
});


gulp.task('js', function(){
   gulp.src(paths.js

)
   .pipe(concat('apps.js'))
   .pipe(uglify())
   .pipe(gulp.dest('dist/js'))
});


gulp.task('uglify', function(){
   gulp.src(paths.app

)
   .pipe(uglify())
   .pipe(gulp.dest('dist/js/apps'))
});


/*Homepage tasks*/
gulp.task('home_css', function(){
   gulp.src(paths.home_css
)
   .pipe(concat('styles.css'))
   .pipe(minify())
   .pipe(gulp.dest('dist/home/styles/css'))
});

gulp.task('home_js', function(){
   gulp.src(paths.home_js

)
   .pipe(concat('apps.js'))
   .pipe(uglify())
   .pipe(gulp.dest('dist/home/js'))
});

gulp.task('img', () =>
    gulp.src(paths.img)
        .pipe(imagemin())
        .pipe(gulp.dest('dist/img'))
);


gulp.task('watch', function() {
  gulp.watch(paths.js, ['js']);
  gulp.watch(paths.css, ['css']);
  gulp.watch(paths.app, ['uglify']);

  gulp.watch(paths.home_js, ['home_js']);
  gulp.watch(paths.home_css, ['home_css']);
  gulp.watch(paths.img, ['img']);
});

gulp.task('default',['js','css', 'uglify', 'watch', 'home_js', 'home_css', 'img'], function () {

});