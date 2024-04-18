// -----------------------------------------------------------------------------
//   Ambient.Impact - Base - Tooltips
// -----------------------------------------------------------------------------

AmbientImpact.onGlobals([
  'tippy.defaultProps',
], function() {
AmbientImpact.on(['tooltip'], function(aiTooltip) {
AmbientImpact.addComponent('baseThemeTooltips', function(baseTooltips, $) {

  'use strict';

  // Set default properties for all tooltips, including those created via other
  // means - such as in a theme that uses this base theme.
  tippy.setDefaultProps({
    animation: 'shift-away',
    // Our custom Material theme; not to be confused with the one that ships
    // with Tippy.js which is not loaded.
    theme: 'material',
  });

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
