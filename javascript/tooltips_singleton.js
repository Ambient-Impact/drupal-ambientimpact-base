// -----------------------------------------------------------------------------
//   Ambient.Impact - Base - Tooltips singleton component
// -----------------------------------------------------------------------------

AmbientImpact.onGlobals(['tippy.createSingleton'], () => {
AmbientImpact.on(['fastdom', 'tooltip'], (aiFastDom, aiTooltip) => {
AmbientImpact.addComponent('baseThemeTooltipsSingleton', function(
  component, $,
) {

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

  /**
   * DOM property name where we save the singleton instance to.
   *
   * @type {String}
   */
  const singletonPropName = `${tooltipsPropName}Singleton`;

  this.addBehaviour(
    'AmbientImpactTooltipsSingleton',
    'ambientimpact-tooltips-singleton',
    '[data-tooltips-singleton]',
    // We want to ignore 'refreshless:before-cache' but we do want to be
    // notified when displaying a cached snapshot.
    ['unload', 'refreshless:cached-snapshot'],
    async function(context, settings) {

      // This is necessary to delay attaching until the async detach has cleaned
      // up after restoring from RefreshLess' cache. This must await the
      // FastDom mutate specifically as this will put us in the queue after the
      // the detach has queued in the mutate queue.
      await fastdom.mutate(() => {});

      $(this).prop(
        tooltipsPropName,
        new aiTooltip.Tooltip($(this).find('[title]')),
      );

      $(this).prop(
        singletonPropName,
        tippy.createSingleton(
          $(this).prop(tooltipsPropName).tippy,
        ),
      );

    },
    async function(context, settings, trigger) {

      // Restore any title attributes that were left as data attributes as can
      // occur when restoring from RefreshLess' cache and then return.
      if (trigger === 'refreshless:cached-snapshot') {

        const $items = $(this).find('[data-original-title]');

        await fastdom.mutate(() => {

          $items.each((i, element) => {

            const $this = $(element);

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

      $(this).prop(singletonPropName)?.destroy();

      $(this).removeProp(singletonPropName);

      $(this).prop(tooltipsPropName)?.destroy();

      $(this).removeProp(tooltipsPropName);

    }
  );

});
});
});
