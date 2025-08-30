'use strict';

const path = require('path');
const {pathToFileURL} = require('url');

/** @type {String} The base theme prefix. */
const baseThemePrefix = 'base:';

const baseStylesheetsPath = path.join(
  // @todo Get the package path from Yarn's PnP API so we don't need to export
  //   "./package.json"?
  path.dirname(require.resolve('drupal-ambientimpact-base/package.json')),
  'stylesheets',
);

/**
 * Sass importer to resolve base theme stylesheet paths.
 *
 * @param {String} url
 *   The path to import or use.
 *
 * @param {String} prev
 *   The previously resolved path.
 *
 * @param {Function} done
 *   Callback to invoke on async completion.
 *
 * @return {Promise|null}
 *
 * @see https://github.com/sass/node-sass#importer--v200---experimental
 *   Importer callback documentation.
 */
function baseThemeImporter(url, prev, done) {

  // If we didn't match, return null so that this is passed on to the next
  // importer to try.
  if (url.indexOf(baseThemePrefix) !== 0) {
    return null;
  }

  return done({
    file: path.relative(path.dirname(prev), path.join(
      baseStylesheetsPath, url.substring(baseThemePrefix.length),
    )),
  });

};

module.exports = baseThemeImporter;
