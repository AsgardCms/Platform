/*jshint node:true */

'use strict';

var map   = require('map-stream'),
	gutil = require('gulp-util'),
	os    = require('os'),
	exec  = require('child_process').exec;

module.exports = function(command, opt){
	var counter = 0;
	var skipCmd = '';

	if (typeof command === 'object') {
		throw new Error('Invalid PHPSpec Binary');
	}

	// if path to phpspec bin not supplied, use default vendor/bin path
	if(! command) {
		command = './vendor/bin/phpspec run';
		if (os.platform() === 'win32') {
			command = '.\\vendor\\bin\\phpspec run';
		}
	}

	// create default opt object if no options supplied
	if ( ! opt) { opt = {}; }

	// assign default options if one is not supplied
	if (typeof opt.testSuite === 'undefined') { opt.testSuite = ''; }
	if (typeof opt.verbose === 'undefined') { opt.verbose = ''; }
	if (typeof opt.silent === 'undefined') { opt.silent = false; }
	if (typeof opt.debug === 'undefined') { opt.debug = false; }
	if (typeof opt.testClass === 'undefined') { opt.testClass = ''; }
	if (typeof opt.clear === 'undefined') { opt.clear = false; }
	if (typeof opt.flags === 'undefined') { opt.flags = ''; }
	if (typeof opt.notify === 'undefined') { opt.notify = false; }
	if (typeof opt.noInteraction === 'undefined') { opt.noInteraction = true; }
	if (typeof opt.noAnsi === 'undefined') { opt.noAnsi = false; }
	if (typeof opt.quiet === 'undefined') { opt.quiet = false; }
	if (typeof opt.formatter === 'undefined') { opt.formatter = ''; }

	return map(function (file, cb) {

		// construct command
		var cmd = opt.clear ? 'clear && ' + command : command;

		// assign default class and/or test suite
		if (opt.testSuite) { cmd += ' ' + opt.testSuite; }
		if (opt.testClass) { cmd += ' ' + opt.testClass; }
		if (opt.verbose) { cmd += ' -' + opt.verbose; }
		if (opt.formatter) { cmd += ' -f' + opt.formatter; }
		if (opt.quiet) { cmd += ' --quiet'; }
		if (opt.noInteraction) { cmd += ' --no-interaction'; }
		cmd += opt.noAnsi ? ' --no-ansi' : ' --ansi';

		if(counter === 0) {
			counter++;

			cmd += skipCmd + ' ' + opt.flags;

			cmd.trim(); // clean up any space remnants

			if (opt.debug) {
				gutil.log(gutil.colors.yellow('\n       *** Debug Cmd: ' + cmd + '***\n'));
			}

			exec(cmd, function (error, stdout, stderr) {

				if (!opt.silent && stderr) {
					gutil.log(stderr);
				}

				if (stdout) {
					stdout = stdout.trim(); // Trim trailing cr-lf
				}

				if (!opt.silent && stdout) {
					gutil.log(stdout);
				}

				if(opt.debug && error) {
					gutil.log(error);
				}

				if (opt.notify) {
					cb(error, file);
				}

			});
		}
	});

};

