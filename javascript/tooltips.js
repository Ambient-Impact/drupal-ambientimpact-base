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

  this.addBehaviour(
    'AmbientImpactTooltips',
    'ambientimpact-tooltips',
    '.layout-container',
    function(context, settings) {

      // Restore any title attributes that were left as data attributes as can
      // occur when restoring from RefreshLess' cache.
      //
      // @todo Remove when we can reliably detach before caching.
      $(this).find('[data-original-title]').each(async (i, element) => {

        const $this = $(element);

        await fastdom.mutate(() => {

          $this.attr('title', $this.attr('data-original-title')).removeAttr(
            'data-original-title',
          );

        });

      });

      $(this).prop(
        'AmbientImpactTooltips',
        new aiTooltip.Tooltips(this, {target: '[title]'}),
      );

    },
    function(context, settings, trigger) {

      $(this).prop('AmbientImpactTooltips')?.destroy();

      $(this).removeProp('AmbientImpactTooltips');

    }
  );

});
});
