#node-sass-middleware

Connect middleware for [node-sass](https://github.com/sass/node-sass)

[![Build Status](https://travis-ci.org/sass/node-sass-middleware.svg?branch=master&style=flat)](https://travis-ci.org/sass/node-sass-middleware)
[![npm version](https://badge.fury.io/js/node-sass-middleware.svg)](http://badge.fury.io/js/node-sass-middleware)
[![Dependency Status](https://david-dm.org/sass/node-sass-middleware.svg?theme=shields.io)](https://david-dm.org/sass/node-sass-middleware)
[![devDependency Status](https://david-dm.org/sass/node-sass-middleware/dev-status.svg?theme=shields.io)](https://david-dm.org/sass/node-sass-middleware#info=devDependencies)
[![Gitter chat](http://img.shields.io/badge/gitter-sass/node--sass-brightgreen.svg)](https://gitter.im/sass/node-sass)

## Install

    npm install node-sass-middleware

## Usage

Recompile `.scss` files automatically for connect and express based http servers

```javascript
var connect = require('connect')
var sassMiddleware = require('node-sass-middleware')
var server = connect.createServer(
  sassMiddleware({
      src: __dirname
    , dest: __dirname + '/public'
    , debug: true
    , outputStyle: 'compressed'
    , prefix:  '/prefix'
  }),
  connect.static('/prefix', __dirname + '/public')
);
```

Heavily inspired by <https://github.com/LearnBoost/stylus>

## Example App

There is an example connect app here: <https://github.com/sass/node-sass-example>

## Contributors

We <3 our contributors! A special thanks to all those who have clocked in some dev time on this project, we really appreciate your hard work. You can find [a full list of those people here.](https://github.com/sass/node-sass-middleware/graphs/contributors)

### Note on Patches/Pull Requests

 * Fork the project.
 * Make your feature addition or bug fix.
 * Add documentation if necessary.
 * Add tests for it. This is important so I don't break it in a future version unintentionally.
 * Send a pull request. Bonus points for topic branches.

## Copyright

Copyright (c) 2013 Andrew Nesbitt. See [LICENSE](https://github.com/sass/node-sass-middleware/blob/master/LICENSE) for details.
