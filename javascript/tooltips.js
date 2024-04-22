// -----------------------------------------------------------------------------
//   Ambient.Impact - Base - Tooltips
// -----------------------------------------------------------------------------

AmbientImpact.on(['tooltip'], function(aiTooltip) {
AmbientImpact.addComponent('baseThemeTooltips', function(baseTooltips, $) {

  'use strict';

  this.addBehaviour(
    'AmbientImpactTooltips',
    'ambientimpact-tooltips',
    '.layout-container',
    function(context, settings) {

      $(this).prop(
        'AmbientImpactTooltips',
        new aiTooltip.Tooltips(this, {target: '[title]'}),
      );

    },
    function(context, settings, trigger) {

      $(this).prop('AmbientImpactTooltips').destroy();

      $(this).removeProp('AmbientImpactTooltips');

    }
  );

});
});
