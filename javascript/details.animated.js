// -----------------------------------------------------------------------------
//   Ambient.Impact - Base - Details element with animation
// -----------------------------------------------------------------------------

AmbientImpact.on(['detailsAnimated'], function(aiDetailsAnimated) {
AmbientImpact.addComponent('baseThemeDetailsAnimated', function(
  baseDetailsAnimated, $,
) {

  'use strict';

  this.addBehaviour(
    'AmbientImpactDetailsAnimated',
    // This is the same once() name as the demo to avoid attaching this twice.
    'ambientimpact-details-animated',
    '.region-content details:not(.details--demo-not-animated)',
    function(context, settings) {

      $(this).prop(
        'AmbientImpactDetailsAnimated',
        new aiDetailsAnimated.DetailsAnimated(this),
      );

    },
    function(context, settings, trigger) {

      /**
       * Reference to the HTML element being detached from.
       *
       * @type {HTMLElement}
       */
      const that = this;

      $(this).prop('AmbientImpactDetailsAnimated').destroy().then(function() {

        $(that).removeProp('AmbientImpactDetailsAnimated');

      });

    }
  );

});
});
