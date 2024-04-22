// -----------------------------------------------------------------------------
//   Ambient.Impact - Base - Tooltip configuration
// -----------------------------------------------------------------------------

AmbientImpact.onGlobals([
  'tippy.setDefaultProps',
], function() {

  'use strict';

  // Set global default properties for all tooltips.
  //
  // @see https://atomiks.github.io/tippyjs/v6/all-props/
  tippy.setDefaultProps({
    animation: 'shift-away',
    // This seems to be only way to get Tippy.js to not output any inline
    // max-width at all so that we can instead set this via a stylesheet. Any
    // value apparently works here.
    maxWidth: '',
    moveTransition: 'transform 0.15s ease-out',
    target: '[title]',
    // Our custom Material theme; not to be confused with the one that ships
    // with Tippy.js which is not loaded.
    theme: 'material',
  });

});
