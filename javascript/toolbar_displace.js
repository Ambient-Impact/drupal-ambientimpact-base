// -----------------------------------------------------------------------------
//   Ambient.Impact - Base - Toolbar displacement fixes
// -----------------------------------------------------------------------------

// This removes the inline scroll-padding-top that the Toolbar module adds to
// the <html> element because it prevents customizing the value in stylesheets
// unless you use !important, which is not ideal.
//
// Note that the Gin Toolbar extends the core Toolbar, so even if you don't
// require the core Toolbar module, it's still in use via Gin.
//
// @see Drupal.toolbar.ToolbarVisualView.updateToolbarHeight()

AmbientImpact.on(['fastdom'], function(aiFastDom) {
AmbientImpact.addComponent('baseThemeToolbarDisplace', function(
  baseThemeToolbarDisplace, $,
) {

  'use strict';

  /**
   * Event namespace name.
   *
   * @type {String}
   */
  const eventNamespace = this.getName();

  /**
   * FastDom instance.
   *
   * @type {FastDom}
   */
  const fastdom = aiFastDom.getInstance();

  this.addBehaviour(
    'baseThemeToolbarDisplace',
    'base-theme-toolbar-displace',
    'html',
    function(context, settings) {

      $(document).on(`drupalViewportOffsetChange.${eventNamespace}`, function(
        event, offsets,
      ) {

        fastdom.mutate(function() {
          $('html').css('scroll-padding-top', '');
        });

      });

    },
    function(context, settings, trigger) {

      $(document).off(`drupalViewportOffsetChange.${eventNamespace}`);

    }
  );

});
});
