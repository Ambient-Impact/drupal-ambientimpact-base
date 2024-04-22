// -----------------------------------------------------------------------------
//   Ambient.Impact - Base - Social links
// -----------------------------------------------------------------------------

AmbientImpact.onGlobals([
  'tippy.createSingleton',
], function() {
AmbientImpact.on(['tooltip'], function(aiTooltip) {
AmbientImpact.addComponent(
  'baseThemeSocialLinks',
function(baseThemeSocialLinks, $) {

  'use strict';

  this.addBehaviour(
    'AmbientImpactSocialLinksTooltips',
    'ambientimpact-social-links-tooltips',
    '.ambientimpact-social-links',
    function(context, settings) {

      $(this).prop(
        'AmbientImpactSocialLinksTooltips',
        new aiTooltip.Tooltip($(this).find(
          '.ambientimpact-social-links__network-link',
        )),
      );

      $(this).prop(
        'AmbientImpactSocialLinksTooltipsSingleton',
        tippy.createSingleton(
          $(this).prop('AmbientImpactSocialLinksTooltips').tippy,
        ),
      );

    },
    function(context, settings, trigger) {

      $(this).prop('AmbientImpactSocialLinksTooltipsSingleton').destroy();

      $(this).removeProp('AmbientImpactSocialLinksTooltipsSingleton');

      $(this).prop('AmbientImpactSocialLinksTooltips').destroy();

      $(this).removeProp('AmbientImpactSocialLinksTooltips');

    }
  );

});
});
});
