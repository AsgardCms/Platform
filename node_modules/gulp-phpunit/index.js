/*jshint node:true */

'use strict';

var map   = require('map-stream'),
	gutil   = require('gulp-util'),
	os      = require('os'),
	exec    = require('child_process').exec;

module.exports = function(command, opt) {
	// Assign default options if one is not supplied
	opt = opt || {};
	opt = {

		// general settings
		silent:             opt.silent              || false,
		debug:              opt.debug               || false,
		clear:              opt.clear               || false,
		dryRun:             opt.dryRun               || false,

		// code coverage options
		coverageClover:     opt.coverageClover      || '',
		coverageCrap4j:     opt.coverageCrap4j      || '',
		coverageHtml:       opt.coverageHtml        || '',
		coveragePhp:        opt.coveragePhp         || '',
		coverageText:       opt.coverageText        || '',
		coverageXml:        opt.coverageXml         || '',

		// logging options
		logJunit:           opt.logJunit            || '',
		logTap:             opt.logTap              || '',
		logJson:            opt.logJson             || '',
		testdoxHtml:        opt.testdoxHtml         || '',
		testdoxText:        opt.testdoxText         || '',

		// test selection options
		filter:             opt.filter              || '',
		testClass:          opt.testClass           || '',
		testSuite:          opt.testSuite           || '',
		group:              opt.group               || '',
		excludeGroup:       opt.excludeGroup        || '',
		listGroups:         opt.listGroups          || '',
		testSuffix:         opt.testSuffix          || '',

		// test execution options
		reportUselessTests: opt.reportUselessTests || false,
		strictCoverage:     opt.strictCoverage     || false,
		disallowTestOutput: opt.disallowTestOutput || false,
		enforceTimeLimit:   opt.enforceTimeLimit   || false,
		disallowTodoTests:  opt.disallowTestOutput || false,
		strict:             opt.strict             || false,

		processIsolation:   opt.processIsolation   || false,
		noGlobalsBackup:    opt.noGlobalsBackup    || false,
		staticBackup:       opt.staticBackup       || false,

		colors:             opt.colors             || true,
		stderr:             opt.stderr             || false,
		stopOnError:        opt.stopOnError        || false,
		stopOnFailure:      opt.stopOnFailure      || false,
		stopOnRisky:        opt.stopOnRisky        || false,
		stopOnSkipped:      opt.stopOnSkipped      || false,
		stopOnIncomplete:   opt.stopOnIncomplete   || false,
		verbose:            opt.verbose            || false,

		loader:             opt.loader             || '',
		repeat:             opt.repeat             || '',
		tap:                opt.tap                || false,
		testdox:            opt.testdox            || false,
		printer:            opt.printer            || '',

		// configuration options
		bootstrap:          opt.bootstrap          || '',
		configurationFile:  opt.configurationFile  || false,
		noConfiguration:    opt.noConfiguration    || false,
		includePath:        opt.includePath        || ''

	};

	// If path to phpunit bin not supplied, use default vendor/bin path
	if (!command) {
		command = './vendor/bin/phpunit';

		// Use the backslashes on Windows
		if (os.platform() === 'win32') {
			command = command.replace(/[/]/g, '\\');
		}
	} else if (typeof command !== 'string') {
		throw new gutil.PluginError('gulp-phpunit', 'Invalid PHPUnit Binary');
	}

	var launched = false;

	return map( function(file, cb) {
		// First file triggers the command, so other files does not matter
		if (launched) {
			return cb(null, file);
		}
		launched = true;

		var cmd = opt.clear ? 'clear && ' + command : command;

		// add remaining switches

		/* code coverage */
		if(opt.coverageClover)      { cmd += ' --coverage-clover=' + opt.coverageClover; }
		if(opt.coverageCrap4j)      { cmd += ' --coverage-crap4j=' + opt.coverageCrap4j; }
		if(opt.coverageHtml)        { cmd += ' --coverage-html=' + opt.coverageHtml; }
		if(opt.coveragePhp)         { cmd += ' --coverage-php=' + opt.coveragePhp; }
		if(opt.coverageText)        { cmd += ' --coverage-text=' + opt.coverageText; }
		if(opt.coverageXml)         { cmd += ' --coverage-xml=' + opt.coverageXml; }

		/* logging options */
		if(opt.logJunit)            { cmd += ' --log-junit=' + opt.logJunit; }
		if(opt.logTap)              { cmd += ' --log-tap=' + opt.logTap; }
		if(opt.logJson)             { cmd += ' --log-json=' + opt.logJson; }
		if(opt.testdoxHtml)         { cmd += ' --testdox-html=' + opt.testdoxHtml; }
		if(opt.testdoxText)         { cmd += ' --testdox-text=' + opt.testdoxText; }

		/* test selection */
		if(opt.filter)              { cmd += ' --filter=' + opt.filter; }
		if(opt.group)               { cmd += ' --group=' + opt.group; }
		if(opt.excludeGroup)        { cmd += ' --exclude-group=' + opt.excludeGroup; }
		if(opt.listGroups)          { cmd += ' --list-groups=' + opt.listGroups; }
		if(opt.testSuffix)          { cmd += ' --test-suffix=' + opt.testSuffix; }

		/* test execution options */
		if(opt.reportUselessTests)  { cmd += ' --report-useless-tests'; }
		if(opt.strictCoverage)      { cmd += ' --strict-coverage'; }
		if(opt.disallowTestOutput)  { cmd += ' --disallow-test-output'; }
		if(opt.enforceTimeLimit)    { cmd += ' --enforce-time-limit'; }
		if(opt.disallowTodoTests)   { cmd += ' --disallow-todo-tests'; }
		if(opt.strict)              { cmd += ' --strict'; }
		if(opt.processIsolation)    { cmd += ' --process-isolation'; }
		if(opt.noGlobalsBackup)     { cmd += ' --no-globals-backup'; }
		if(opt.staticBackup)        { cmd += ' --static-backup'; }
		if(opt.colors)              { cmd += ' --colors'; }
		if(opt.stderr)              { cmd += ' --stderr'; }
		if(opt.stopOnError)         { cmd += ' --stop-on-error'; }
		if(opt.stopOnFailure)       { cmd += ' --stop-on-failure'; }
		if(opt.stopOnRisky)         { cmd += ' --stop-on-risky'; }
		if(opt.stopOnSkipped)       { cmd += ' --stop-on-skipped'; }
		if(opt.stopOnIncomplete)    { cmd += ' --stop-on-incomplete'; }
		if(opt.loader)              { cmd += ' --loader=' + opt.loader; }
		if(opt.repeat)              { cmd += ' --repeat=' + opt.repeat; }
		if(opt.tap)                 { cmd += ' --tap'; }
		if(opt.testdox)             { cmd += ' --testdox'; }
		if(opt.printer)             { cmd += ' --printer=' + opt.printer; }
		if (opt.debug)              { cmd += ' --debug'; }

		/* configuration options */
		if(opt.bootstrap)           { cmd += ' --bootstrap=' + opt.bootstrap; }
		if (opt.noConfiguration)    { cmd += ' --no-configuration'; }
		if (opt.includePath)        { cmd += ' --include-path=' + opt.includePath; }


		/* ---- FINALLY add testClass, Suite or Configuration -- */

		// after options and switches are added, then add either testClass or testSuite

		// Priority:
		// - configuration file
		// - testSuite
		// - testClass

		var skip;

		if ((opt.configurationFile) && (! skip) && (!opt.noConfiguration)){
			cmd += ' -c ' + opt.configurationFile;
			skip = true;
		}

		if ((opt.testSuite) && (! skip)) {
			cmd += ' ' + opt.testSuite;
			skip = true;
		}

		if ((opt.testClass) && (! skip)) {
			cmd += ' ' + opt.testClass;
			skip = true;
		}

		// append debug code if switch enabled
		if ((opt.debug) || (opt.dryRun)) {
			if(opt.dryRun) {
				gutil.log(gutil.colors.green('\n\n       *** Dry Run Cmd: ' + cmd  + ' ***\n'));
			} else {
				gutil.log(gutil.colors.yellow('\n\n       *** Debug Cmd: ' + cmd  + ' ***\n'));
			}
		}

		/* -- EXECUTE -- */
		if( ! opt.dryRun ) {
			exec(cmd, function (error, stdout, stderr) {
				if (!opt.silent && stderr) {
					gutil.log(stderr);
				}

				if (!opt.silent) {
					// Trim trailing cr-lf
					stdout = stdout.trim();

					if (stdout) {
						gutil.log(stdout);
					}
				}

				// call user callback if ano error occurs
				if (error) {
					if (opt.debug) {
						gutil.log(error);
					}
					cb(error, file);
				} else {
					cb(null, file);
				}

			});
		}

	});
};
