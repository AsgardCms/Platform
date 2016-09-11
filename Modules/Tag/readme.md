# Tag Module

[![Latest Version](https://img.shields.io/github/release/asgardcms/tag.svg?style=flat-square)](https://github.com/asgardcms/tag/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/AsgardCms/Tag/master.svg?style=flat-square)](https://travis-ci.org/AsgardCms/Tag)
[![Scrutinizer Coverage](https://img.shields.io/scrutinizer/coverage/g/AsgardCms/Tag.svg?maxAge=86400&style=flat-square)](https://scrutinizer-ci.com/g/AsgardCms/Tag/?branch=master)
[![Quality block](https://img.shields.io/scrutinizer/g/asgardcms/tag.svg?style=flat-square)](https://scrutinizer-ci.com/g/asgardcms/tag)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/0919e4aa-8e6c-43f0-860d-7626cddaf498.svg)](https://insight.sensiolabs.com/projects/0919e4aa-8e6c-43f0-860d-7626cddaf498)


[![Slack](http://slack.asgardcms.com/badge.svg)](http://slack.asgardcms.com/)


| Branch | Travis-ci |
| ---------------- | --------------- |
| master  | [![Build Status](https://travis-ci.org/AsgardCms/Tag.svg?branch=master)](https://travis-ci.org/AsgardCms/Tag)  |


An AsgardCMS module which enabled tagging of any entity with ease.

## Installation

### Composer

```
composer require asgardcms/tag-module
```

### Migrations

Run the migrations for the tag module

```
php artisan module:migrate tag
```

### Permissions

Go to the Admin role, and give yourself the permissions for the Tag Module.


## Usage

Any of you entities can have tags attached to it. To enable this your entity needs to implement an interface, use a trait and that's it.

### 1. Add interface & trait on desired entity

Your entity needs to implement the `Modules\Tag\Contracts\TaggableInterface` interface.

In order for your entity to satisfy this interface it needs to use the following traits:

- `Modules\Core\Traits\NamespacedEntity`
- `Modules\Tag\Traits\TaggableTrait`

Tags are organised by namespace. This is used in order to get the tags for a specific namespace on the display of the field. It also creates tags for that namespace if tags need to be created.
 
By default the `TaggableTrait` will use the full namespace of your entity. However, you can specify a nicer / shorter namespace to use by using the static `$entityNamespace` property on your entity.
 
Example:
 
``` php
protected static $entityNamespace = 'asgardcms/media';
```
 
### 2. Defining a new namespace to use for tags
 
In your module Service Provider, `boot()` method, you now need to add the namespace it's going to use. This can be done using the `TagManager` interface.

``` php
$this->app[TagManager::class]->registerNamespace(new File());
```

And with this, the Tag Module is aware of the new namespace.

### 3. Display the tag field on your views

By using a custom blade directive you can include the tags field on your views. 

- The first argument is the namespace to get the tags for.
- (optional) Second argument is the entity to fetch the tags for (pre-filling the input if tags are present for given entity).
- (optional) Third and last argument can be a view to use. This will override the default tags view with its input field.

```` php
@tags('asgardcms/media', $file)
````

### 4. Store tags

In your repositories you need to call the `setTags()` method to persist the tags on your entity.

``` php
$file->setTags(array_get($data, 'tags'));
```

And that's all on how to use tags for your entities.
 
## Convenience methods

### Scope: `withTag()`

Get all the entities with one of the given tag(s). Optionally specify the column on which to perform the search operation, defaults to the `slug` column.

Example in your repository :

``` php
// Get all files with either of the 2 tags
$files = $this->file->withTag(['your-first-tag', 'some-other-tag'])->get();
```

### Scope: `whereTag()`

Get all the entities with the given tag(s). Optionally specify the column on which to perform the search operation, defaults to `slug` column.

Example in your repository :

``` php
// Get all files with all given tags
$files = $this->file->whereTag(['your-first-tag', 'some-other-tag'])->get();

// Get all files with the given tag
$files = $this->file->whereTag('your-first-tag')->get();
```

### `allTags()`: Get all the tags for the entity

You can fetch all the tags for an entity by using the `allTags()` method.

``` php
$tags = $file->allTags();
```
