This respository contains a reusable Drupal base theme.

**Warning**: while this is generally production-ready, it's not guaranteed to
maintain a stable API and may occasionally contain bugs, being a
work-in-progress. Stable releases may be provided at a later date.

----

# Requirements

* [Drupal 10](https://www.drupal.org/download)

* PHP 8

* [Composer](https://getcomposer.org/)

* [Yarn](https://yarnpkg.com/) 3 or 4

## Drupal dependencies

Before attempting to install this, you must add the Composer repositories as
described in the installation instructions for these dependencies:

* The [`ambientimpact_core`](https://github.com/Ambient-Impact/drupal-ambientimpact-core) and [`ambientimpact_ux`](https://github.com/Ambient-Impact/drupal-ambientimpact-ux) modules.

## Front-end dependencies

To build front-end assets for this project, [Node.js](https://nodejs.org/) and
[Yarn](https://yarnpkg.com/) are required.

----

# Installation

## Composer

### Set up

Ensure that you have your Drupal installation set up with the correct Composer
installer types such as those provided by [the `drupal/recommended-project`
template](https://www.drupal.org/docs/develop/using-composer/starting-a-site-using-drupal-composer-project-templates#s-drupalrecommended-project).
If you're starting from scratch, simply requiring that template and following
[the Drupal.org Composer
documentation](https://www.drupal.org/docs/develop/using-composer/starting-a-site-using-drupal-composer-project-templates)
should get you up and running.

### Repository

In your root `composer.json`, add the following to the `"repositories"` section:

```json
"drupal/ambientimpact_base": {
  "type": "vcs",
  "url": "https://github.com/Ambient-Impact/drupal-ambientimpact-base.git"
}
```

### Installing

Once you've completed all of the above, run `composer require
"drupal/ambientimpact_base:^7.0@dev"` in the root of your project to have
Composer install this and its required dependencies for you.

## Front-end assets

To build front-end assets for this project, you'll need to install
[Node.js](https://nodejs.org/) and [Yarn](https://yarnpkg.com/).

This package makes use of [Yarn
Workspaces](https://yarnpkg.com/features/workspaces) and references other local
workspace dependencies. In the `package.json` in the root of your Drupal
project, you'll need to add the following:

```json
"workspaces": [
  "<web directory>/themes/custom/*"
],
```

where `<web directory>` is your public Drupal directory name, `web` by default.
Once those are defined, add the following to the `"dependencies"` section of
your top-level `package.json`:

```json
"drupal-ambientimpact-base": "workspace:^7"
```

Then run `yarn install` and let Yarn do the rest.

### Optional: install yarn.BUILD

While not required, we recommend installing [yarn.BUILD](https://yarn.build/) to
make building all of the front-end assets even easier.

----

# Building front-end assets

We use [Webpack](https://webpack.js.org/) and [Symfony Webpack
Encore](https://symfony.com/doc/current/frontend.html) to automate most of the
build process. These will have been installed for you if you followed the Yarn
installation instructions above.

If you have [yarn.BUILD](https://yarn.build/) installed, you can run:

```
yarn build
```

from the root of your Drupal site. If you want to build just this package, run:

```
yarn workspace drupal-ambientimpact-site run build
```

----

# Major breaking changes

The following major version bumps indicate breaking changes:

* 3.x - Now require [the 3.x branch of modules](https://gitlab.com/Ambient.Impact/drupal-modules), which now require Drupal 9. All development is now against that major version of Drupal.

* 4.x - Refactored to use [Sass modules](https://sass-lang.com/blog/the-module-system-is-launched); all development is now against this and will no longer compile using the old ```@import``` directive.

* 5.x - Front-end package manager is now [Yarn](https://yarnpkg.com/); front-end build process ported to [Webpack](https://webpack.js.org/).

* 6.x - Requires Drupal 9.5 or [Drupal 10](https://www.drupal.org/project/drupal/releases/10.0.0).

* 7.x:

  * Requires Drupal 10.

  * Removed `.nvmrc` file as Node.js is stable enough nowadays to no longer warrant this.

  * Major rework of stylesheets:

    * They now use [CSS custom properties](https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties) to configure various values rather than Sass variables.

    * A lot of partials have been converted into standalone stylesheets and libraries which are attached to the appropriate elements, render arrays, etc. This allows for more fine-grained and efficient serving of styles that are actually used and less that are not used on a page or a site.
