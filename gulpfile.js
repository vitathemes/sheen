"use strict";

const { series, parallel } = require("gulp");
const browserSync = require("browser-sync").create();
const gulp = require("gulp");
const rtlcss = require("gulp-rtlcss");
const rename = require("gulp-rename");
const concat = require("gulp-concat");
const autoprefixer = require("gulp-autoprefixer");
const sass = require("gulp-sass")(require("sass"));

const sassSrc = "./assets/src/scss/";
const cssDest = "./assets/css/";
const cssSrc = "./assets/src/css/";

const sassTask = (cb) => {
    return gulp
        .src([
            "./node_modules/flickity/dist/flickity.css",
            "./assets/src/scss/style.scss",
        ])
        .pipe(sass().on("error", sass.logError))
        .pipe(autoprefixer({ cascade: false }))
        .pipe(gulp.dest("./assets/src/css"))
        .pipe(browserSync.stream());
    cb();
};

const cssConcatExternalTask = (cb) => {
    return gulp
        .src([cssSrc + "style.css"])
        .pipe(concat("style.css"))
        .pipe(gulp.dest(cssDest));
    cb();
};

const rtlCssTask = (cb) => {
    return gulp
        .src(cssSrc + "style.css")
        .pipe(rtlcss())
        .pipe(rename("style-rtl.css"))
        .pipe(gulp.dest(cssDest));
    cb();
};

const mainScriptsTask = (cb) => {
    return gulp
        .src("./assets/src/js/main.js")
        .pipe(concat("main.js"))
        .pipe(gulp.dest("./assets/js"));
    cb();
};

const vendorScriptsTask = (cb) => {
    return gulp
        .src([
            "./assets/src/js/vendor/iconify.js",
            "./node_modules/masonry-layout/dist/masonry.pkgd.js",
            "./node_modules/vanilla-lazyload/dist/lazyload.js",
            "./node_modules/flickity/dist/flickity.pkgd.js",
        ])
        .pipe(concat("vendors.js"))
        .pipe(gulp.dest("./assets/js"));
    cb();
};

function liveServerTask(cb) {
    browserSync.init({
        proxy: "sheen.local/",
    });
    gulp.watch([sassSrc + "**/*.scss"]).on(
        "change",
        series(sassTask, cssConcatExternalTask)
    );
    gulp.watch(["./assets/src/js/main.js"]).on("change", series(mainScriptsTask));
    gulp.watch("./**/*.php").on("change", browserSync.reload);
    cb();
}

exports.default = series(
    sassTask,
    cssConcatExternalTask,
    rtlCssTask,
    mainScriptsTask,
    vendorScriptsTask,
    liveServerTask
);
exports.rtlcss = series(rtlCssTask);
