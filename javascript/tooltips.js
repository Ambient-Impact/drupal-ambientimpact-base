// -----------------------------------------------------------------------------
//   Ambient.Impact - Base - Tooltips
// -----------------------------------------------------------------------------

AmbientImpact.on(['fastdom', 'tooltip'], function(aiFastDom, aiTooltip) {
AmbientImpact.addComponent('baseThemeTooltips', (baseTooltips, $) => {

  'use strict';

  /**
   * FastDom instance.
   *
   * @type {FastDom}
   */
  const fastdom = aiFastDom.getInstance();

  /**
   * DOM property name where we save the tooltips instance to.
   *
   * @type {String}
   */
  const tooltipsPropName = 'AmbientImpactTooltips';

  this.addBehaviour(
    'AmbientImpactTooltips',
    'ambientimpact-tooltips',
    '.layout-container',
    // We want to ignore 'refreshless:before-cache' but we do want to be
    // notified when displaying a cached snapshot.
    ['unload', 'refreshless:cached-snapshot'],
    function(context, settings) {

      $(this).prop(
        tooltipsPropName,
        new aiTooltip.Tooltips(this, {target:
          // Ignore elements within a singleton container.
          '[title]:not([data-tooltips-singleton] *)',
        }),
      );

    },
    function(context, settings, trigger) {

      // Remove any cached element and return if displaying a cached snapshot.
      if (trigger === 'refreshless:cached-snapshot') {

        // Restore any title attributes that were left as data attributes as can
        // occur when restoring from RefreshLess' cache.
        $(this).find(
          '[data-original-title]:not([data-tooltips-singleton] *)',
        ).each(async (i, element) => {

          const $this = $(element);

          await fastdom.mutate(() => {

            $this.attr('title', $this.attr('data-original-title')).removeAttr(
              'data-original-title',
            );

            // Find the Tooltip itself, if it exists, and remove it as well.
            $(`#${$this.attr('aria-describedby')}`).remove();

            $this.removeAttr('aria-describedby');

          });

        });

        return;

      }

      $(this).prop(tooltipsPropName)?.destroy();

      $(this).removeProp(tooltipsPropName);

    }
  );

});
});
