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

const sassTask = (cb) => {
    return gulp
        .src(sassSrc + "style.scss")
        .pipe(sass().on("error", sass.logError))
        .pipe(autoprefixer({ cascade: false }))
        .pipe(gulp.dest(cssDest))
        .pipe(browserSync.stream());
    cb();
};

// const editorStylesTask = (cb) => {
//     return gulp
//         .src(editorSrc + "editor-style.scss")
//         .pipe(sass().on("error", sass.logError))
//         .pipe(autoprefixer({ cascade: false }))
//         .pipe(gulp.dest(cssDest))
//         .pipe(browserSync.stream());
//     cb();
// };

const rtlCssTask = (cb) => {
    return gulp
        .src(cssDest + "style.css")
        .pipe(rtlcss())
        .pipe(rename("style-rtl.css"))
        .pipe(gulp.dest(cssDest));
    cb();
};

const cssConcatExternalTask = (cb) => {
    return gulp
        .src([cssDest + "style.css"])
        .pipe(concat("style.css"))
        .pipe(gulp.dest(cssDest));
    cb();
};

const mainScriptsTask = (cb) => {
    return gulp
        .src("./assets/src/js/")
        .pipe(concat("main.js"))
        .pipe(gulp.dest("./assets/js"));
    cb();
};

const vendorScriptsTask = (cb) => {
    return gulp
        .src(["./assets/src/js/vendor/iconify.js"])
        .pipe(concat("vendors.js"))
        .pipe(gulp.dest("./assets/js"));
    cb();
};

function liveServerTask(cb) {
    browserSync.init({
        proxy: "brilliance.local",
    });
    gulp.watch([sassSrc + "**/*.scss"]).on(
        "change",
        series(sassTask, cssConcatExternalTask)
    );
    gulp.watch("./**/*.php").on("change", browserSync.reload);
    cb();
}

exports.default = series(
    sassTask,
    cssConcatExternalTask,
    mainScriptsTask,
    vendorScriptsTask,
    liveServerTask
);
exports.rtlcss = series(rtlCssTask);
