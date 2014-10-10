
var gulp = require('gulp');
var notify = require('../');
var through = require('through2');
var path = require('path');
var plumber = require('gulp-plumber');
var nn = require('node-notifier');

gulp.task("multiple", function () {
  gulp.src("../test/fixtures/*")
      .pipe(notify());
});

gulp.task("one", function () {
  return gulp.src("../test/fixtures/1.txt")
      .pipe(notify());
});

gulp.task("message", function () {
  return gulp.src("../test/fixtures/1.txt")
      .pipe(notify("This is a message."));
});


gulp.task("customReporter", function () {
  var custom = notify.withReporter(function (options, callback) {
    console.log("Title:", options.title);
    console.log("Message:", options.message);
    callback();
  });

  return gulp.src("../test/fixtures/1.txt")
      .pipe(custom("This is a message."));
});

gulp.task("template", function () {
  return gulp.src("../test/fixtures/1.txt")
      .pipe(notify("Template: <%= file.relative %>"));
});


gulp.task("templateadv", function () {
  return gulp.src("../test/fixtures/1.txt")
      .pipe(notify({
        message: "Template: <%= file.relative %>",
        title: function (file) {
          if(file.isNull()) {
            return "Folder:";
          }
          return "File: <%= file.relative %> <%= options.extra %>";
        },
        templateOptions: {
          extra: "foo"
        }
      }));
});

gulp.task("function", function () {
  return gulp.src("../test/fixtures/1.txt")
      .pipe(notify(function(file) {
          return "Some file: " + file.relative;
      }));
});

gulp.task("advanced", function () {
  return gulp.src("../test/fixtures/*")
      .pipe(notify({
        "title": "Open Github",
        "subtitle": "Project web site",
        "message": "Click to open project site",
        "sound": "Frog", // case sensitive
        "icon": path.join(__dirname, "gulp.png"), // case sensitive
        "open": "https://github.com/mikaelbr/gulp-notify",
        "onLast": true
      }));
});


gulp.task("onlast", function () {
  return gulp.src("../test/fixtures/*")
      .pipe(notify({
        onLast: true,
        message: function(file) {
          return "Some file: " + file.relative;
        }
      }));
});

gulp.task("error", function () {
  return gulp.src("../test/fixtures/*")
      .pipe(through.obj(function (file, enc, callback) {
        this.emit("error", new Error("Something happend: Error message!"));
        callback();
      }))
      .on("error", notify.onError('Error: <%= error.message %>'))
      .on("error", function (err) {
        console.log("Error:", err);
      })
});

gulp.task("forceGrowl", function () {
  var custom = notify.withReporter(function (options, callback) {
    new nn.Growl().notify(options, callback);
  });

  return gulp.src("../test/fixtures/*")
      .pipe(through.obj(function (file, enc, callback) {
        this.emit("error", new Error("Something happend: Error message!"));
        callback();
      }))
      .on("error", custom.onError('Error: <%= error.message %>'));
});

gulp.task("customError", function () {

  var custom = notify.withReporter(function (options, callback) {
    console.log("Title:", options.title);
    console.log("Message:", options.message);
    callback();
  });

  custom.logLevel(1);

  return gulp.src("../test/fixtures/*")
      .pipe(custom('<%= file.relative %>'))
      .pipe(through.obj(function (file, enc, callback) {
        this.emit("error", new Error("Something happend: Error message!"));
        callback();
      }))
      .on("error", custom.onError({
        message: 'Error: <%= error.message %>',
        emitError: true
      }))
      .on("error", function (err) {
        console.log("Error:", err);
      })
});
