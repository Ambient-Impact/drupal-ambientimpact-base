// -----------------------------------------------------------------------------
//   Ambient.Impact - Base - Tooltips
// -----------------------------------------------------------------------------

AmbientImpact.onGlobals([
  'tippy.defaultProps',
], function() {
AmbientImpact.on(['tooltip'], function(aiTooltip) {
AmbientImpact.addComponent('baseThemeTooltips', function(baseTooltips, $) {

  'use strict';

  // Set the default theme as our material theme. This should be done here so it
  // applies to all tooltips created via other means, e.g. the tooltip component
  // demo.
  tippy.setDefaultProps({theme: 'material'});

  this.addBehaviour(
    'AmbientImpactTooltips',
    'ambientimpact-tooltips',
    '.layout-container',
    function(context, settings) {

      $(this).prop(
        'AmbientImpactTooltips',
        new aiTooltip.Tooltips(this),
      );

    },
    function(context, settings, trigger) {

      $(this).prop('AmbientImpactTooltips').destroy();

      $(this).removeProp('AmbientImpactTooltips');

    }
  );

});
});
});
