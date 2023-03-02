const gulp = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const postcss = require("gulp-postcss");
const cssnano = require("cssnano");
const autoprefixer = require("autoprefixer");
const concat = require("gulp-concat");
const tailwindcss = require("tailwindcss");
const rename = require("gulp-rename");
const uglify = require("gulp-uglify");
const imagemin = require("gulp-imagemin");
const webp = require("gulp-webp");

gulp.task("styles-scss", () => {
  const plugins = [autoprefixer(), cssnano()];

  return (
    gulp
      .src(["src/scss/**/*.scss"])
      // Process SCSS to CSS
      .pipe(sass().on("error", sass.logError))

      .pipe(concat("style.css"))

      // Autoprefix and minify
      .pipe(postcss(plugins))

      .pipe(rename({ suffix: ".min" }))

      // Save minified file
      .pipe(gulp.dest("assets/css/"))
  );
});

gulp.task("styles-tailwind", () => {
  const plugins = [
    tailwindcss("config/tailwind.config.js"),
    autoprefixer(),
    cssnano(),
  ];

  return (
    gulp
      .src(["src/tailwind.css"])

      .pipe(postcss(plugins))

      .pipe(concat("core.css"))

      .pipe(rename({ suffix: ".min" }))

      // Save minified file
      .pipe(gulp.dest("assets/css/"))
  );
});

gulp.task("scripts", () => {
  return (
    gulp
      .src(["src/js/components/*.js", "src/js/main.js"])

      .pipe(concat("main.js"))

      // Minify js
      .pipe(uglify())

      .pipe(rename({ suffix: ".min" }))

      // Save minified file
      .pipe(gulp.dest("assets/js/"))
  );
});

gulp.task("images", () => {
  return (
    gulp
      .src(["src/images/*"])

      // Minify image
      .pipe(imagemin())

      // Save minified source image
      .pipe(gulp.dest("assets/images/"))

      // Create webp image
      .pipe(webp())

      // Save minified webp image
      .pipe(gulp.dest("assets/images/"))
  );
});

gulp.task("watch", () => {
  // Init BrowserSync server
  // browserSync.init({
  //   proxy: "local.webgrowstudio.com",
  // });

  // Run clean and styles tasks on SCSS changes
  gulp.watch(["src/scss/**/*.scss", "src/js/**/*.js"], (done) => {
    gulp.series(["styles", "scripts"])(done);
  });
});

gulp.task("styles", gulp.series(["styles-scss", "styles-tailwind"]));

gulp.task(
  "default",
  gulp.series(["styles-scss", "styles-tailwind", "scripts", "images"])
);
