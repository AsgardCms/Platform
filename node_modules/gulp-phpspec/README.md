# gulp-phpspec
> PHPSpec plugin for gulp 3

## Usage

First, install `gulp-phpspec` as a development dependency:

```shell
npm install --save-dev gulp-phpspec
```

Then, add it to your `gulpfile.js`:

```javascript
var phpspec = require('gulp-phpspec');

// option 1: default format
gulp.task('phpspec', function() {
	gulp.src('./spec/**/*.php').pipe(phpspec());
});

// option 2: with defined bin and options
gulp.task('phpspec', function() {
	var options = {debug: false};
	gulp.src('./spec/**/*.php').pipe(phpspec('./vendor/bin/phpspec run',options));
});

// option 3: supply callback to integrate something like notification (using gulp-notify)

var gulp    = require('gulp'),
    notify  = require('gulp-notify'),
    phpspec = require('gulp-phpspec');

gulp.task('phpspec', function() {
	var options = {notify: false};
	gulp.src('app/spec/**/*.php')
		.pipe(phpspec('', options))
		.on('error', notify.onError({
			title: "Testing Failed",
			message: "Error(s) occurred during test..."
		}))
		.pipe(notify({
			title: "Testing Passed",
			message: "All tests have passed..."
		}));
});

```

### Sample Gulpfile

If you want a quick and dirty gulpfile, here is one I created for testing this plugin

[Gist: https://gist.github.com/mikeerickson/9163621](https://gist.github.com/mikeerickson/9163621)


## API

### (phpspecpath,options,cb)

#### phpspecpath

Type: `String`

The path to the desired PHPSpec binary
- If not supplied, the defeault path will be ./vendor/bin/phpspec

#### options.debug
Type: `Boolean (Default: false)`

Emit error details and shows command used in console

#### options.clear
Type: `Boolean (Default: false)`

Clear console before executing command


#### options.notify
Type: `Boolean (Default: false)`

Call user supplied callback to handle notification (use gulp-notify)

#### options.verbose
Type: `String (Default null)`

Adjust the default verbosity of messages
v|vv|vvv Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

#### options.noInteraction
Type: `Boolean (Default: false)`
*Note: changed from noInteract -* __0.3.0__

Do not ask any interactive question (disables code generation).

#### options.noAnsi
Type: `Boolean (Default: false)`

Disable ANSI output (ommiting parameter or setting to false will display ansi colors output)

#### options.quiet
Type: `Boolean (Default: false)`

Do not output any message.

#### options.formatter
Type: `String`

Display PHPSpec custom formatters (ie pretty)


## Changelog

- 0.3.0: Bug Fixes
  - refactored noInteraction option to match PHPSpec option (was called noInteract)

- 0.2.6: Added formatter option
  - added support for -f formatter options

- 0.2.5: Bug fix introduced in 0.2.4

- 0.2.4: Added options
    - added support for quiet option

- 0.2.3: Added options
    - added support for verbose flags
    - added support for no-interaction flag (on by default)
    - added flag for 'noAnsi' disabling ansi (false by default)

- 0.2.2: UI/UX Changes
    - added color output support using --ansi switch
    - removed Node 0.9 support from Travis integration

- 0.2.1: Code Refactor and Travis Integration
    - added travis configuration

- 0.2.0: Code Cleanup
    - Removed calls to console.log -> gutil.log (playing nice in the playground)
    - Fixed issue with calling as dependency task (thanks @taai)

- 0.1.1: Code Cleanup
    - Removed calls to console.log -> gutil.log (playing nice in the playground)

- 0.1.0: Initial Release

## Credits

gulp-phpspec written by Mike Erickson

E-Mail: [codedungeon@gmail.com](mailto:codedungeon@gmail.com)

Twitter: [@codedungeon](http://twitter.com/codedungeon)

Webiste: [codedungeon.org](http://codedungeon.org)