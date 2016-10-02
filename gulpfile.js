var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var minify = require('gulp-minify-css');
var livereload = require('gulp-livereload');

var paths = {
    js: ['assets/luna/vendor/pacejs/pace.min.js',
       'assets/luna/vendor/jquery/dist/jquery.min.js',
       'assets/luna/vendor/datatables/datatables.min.js',
       'assets/luna/vendor/bootstrap/js/bootstrap.min.js',
       'assets/luna/vendor/toastr/toastr.min.js',
       'assets/luna/vendor/sparkline/index.js',
       'assets/luna/vendor/jquery-ui/jquery-ui.min.js',
       'assets/luna/scripts/luna.js',
       'assets/js/toastr_options.js',
       'assets/js/pace_options.js',
       'assets/js/app.js',
       'assets/js/header.js',
       /*'assets/js/*.js'*/],
    css: ['assets/luna/vendor/fontawesome/css/font-awesome.css',
        'assets/luna/vendor/animate.css/animate.css',
        'assets/luna/vendor/bootstrap/css/bootstrap.css',
        'assets/luna/vendor/toastr/toastr.min.css',
        'assets/luna/vendor/datatables/datatables.min.css',
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
          'assets/js/settings-app.js']
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



gulp.task('watch', function() {
  gulp.watch(paths.js, ['js']);
  gulp.watch(paths.css, ['css']);
  gulp.watch(paths.app, ['uglify']);
});

gulp.task('default',['js','css', 'uglify', 'watch', 'uglify'], function () {

});