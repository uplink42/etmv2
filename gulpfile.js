var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var minify = require('gulp-minify-css');

gulp.task('css', function(){
   gulp.src([
  'assets/luna/vendor/fontawesome/css/font-awesome.css',
  'assets/luna/vendor/animate.css/animate.css',
  'assets/luna/vendor/bootstrap/css/bootstrap.css',
  'assets/luna/vendor/toastr/toastr.min.css',
  'assets/luna/vendor/datatables/datatables.min.css',
  'assets/luna/styles/pe-icons/pe-icon-7-stroke.css',
  'assets/luna/styles/pe-icons/helper.css',
  'assets/luna/styles/stroke-icons/style.css',
  'assets/luna/styles/style.css'
])

   .pipe(concat('styles.css'))
   .pipe(minify())
   .pipe(gulp.dest('dist/luna/styles/css'));
});

gulp.task('js', function(){
   gulp.src([
  'assets/luna/vendor/pacejs/pace.min.js',
  'assets/luna/vendor/jquery/dist/jquery.min.js',
  'assets/luna/vendor/datatables/datatables.min.js',
  'assets/luna/vendor/bootstrap/js/bootstrap.min.js',
  'assets/luna/vendor/toastr/toastr.min.js',
  'assets/luna/vendor/sparkline/index.js',
  /*'assets/luna/vendor/blockui/blockui.js',*/
  /*'assets/luna/vendor/flot/jquery.flot.min.js',
  'assets/luna/vendor/flot/jquery.flot.resize.min.js',
  'assets/luna/vendor/flot/jquery.flot.spline.js',*/
  'assets/luna/vendor/jquery-ui/jquery-ui.min.js',
  /*'assets/luna/vendor/angular/angular.min.js',*/
  'assets/luna/scripts/luna.js',
  'assets/js/toastr_options.js',
  'assets/js/pace_options.js',
  'assets/js/app.js',
  'assets/js/header.js',
  //'assets/js/*.js'

])
   .pipe(concat('apps.js'))
   .pipe(uglify())
   .pipe(gulp.dest('dist/js'));
});

gulp.task('default',['js','css'],function(){
});
