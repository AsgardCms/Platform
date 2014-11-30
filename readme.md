# Asgard/Platform

This is the core repository for the Asgard CMS. The admin is fully translatable in English and French by default.

![](https://i.cloudup.com/WcLe-ORql4.thumb.png)

## Requirements

* PHP 5.4+

During the installation process you'll be prompted to either install [Cartalyst Sentinel](https://cartalyst.com/manual/sentinel/1.0?utm_source=asgard-cms&utm_medium=readme&utm_campaign=asgard-cms) or [Cartalyst Sentry](https://cartalyst.com/manual/sentry/2.1?utm_source=asgard-cms&utm_medium=readme&utm_campaign=asgard-cms).

If you choose to use Sentinel, you have to have access to Cartalyst's Arsenal. If you want to subscribe to Sentinel please check out their [Pricing plans](https://cartalyst.com/pricing?utm_source=asgard-cms&utm_medium=readme&utm_campaign=asgard-cms).

Since Cartalyst Sentinel isn't free, you can't use it for all projects, check out their [license page](https://cartalyst.com/license?utm_source=asgard-cms&utm_medium=readme&utm_campaign=asgard-cms) to know exactly what's permitted.

After the installation process you can also implement other User/Role drivers if needed. Refer to the documentation to know how.

## Getting started

### While in beta:

While AsgardCMS is in its beta period the above installation won't be available.

You have to clone this repository manually, run `composer install` and lastly `php artisan asgard:install` to start the installation process.

Please note that the current state of the CMS doesn't have the front-end yet. What you can test though, is the complete backend interface.

### Install Platform

```
composer create-project asgard/platform your-project-name --prefer-dist --stability=dev
```

### Run composer install

Run the usual `composer install` to get the dependencies.


## Run the install command

Now run `php artisan asgard:install` command to perform to start the installation process.

This install command will perform the following actions:

- Setup database information
- Running migrations
- Running seeds
- Publishing assets
- Create a first admin account


### Enjoy

You can now login on `/backend` with your email and password asked during the install command.


## Documentation

You can read the documentation on its specific [github repository](https://github.com/AsgardCms/Documentation).




## License (MIT)

Copyright (c) 2013 [Nicolas Widart](http://www.nicolaswidart.com) , n.widart@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
