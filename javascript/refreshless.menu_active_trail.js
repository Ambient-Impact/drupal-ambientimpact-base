// -----------------------------------------------------------------------------
//   Ambient.Impact - Base - RefreshLess menu active trail fix
// -----------------------------------------------------------------------------

// This works around Drupal not setting the active trail on menu items in some
// edge cases in RefreshLess responses; a notable example is when a menu link
// points to a node or other path that's set to the front page (e.g. /node/123),
// and the Redirect is installed and is set to "Enforce clean and canonical
// URLs" in its settings (the default).

AmbientImpact.onGlobals(['once'], function() {
AmbientImpact.addComponent('baseThemeRefreshLessMenuActiveTrail', (
  component, $,
) => {

  'use strict';

  /**
   * Event namespace name.
   *
   * @type {String}
   */
  const eventNamespace = component.getName();

  const onceName = 'ambientimpact-base-refreshless-menu-active-trail';

  $(once(
    onceName, 'html',
  )).on(`refreshless:load.${eventNamespace}`, (event) => {

    if (event.detail.initial === true) {
      return;
    }

    const currentPath = drupalSettings.path.currentPath;

    $(`.menu-item:has(> a[data-drupal-link-system-path="${
      currentPath
    }"])`).addClass('menu-item--active-trail');

  });

});
});
