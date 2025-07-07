// We want all behaviours to be detached from a page before its cached in
// addition to 'unload' by default. Note that most behaviours don't need to
// detach on 'refreshless:cached-snapshot' so that's not included here.
// Individual behaviours can opt in to that if needed.
//
// Also not that there's no point wrapping this in a component, as that may
// cause this to be delayed and not apply to all components. We also don't need
// nor want to extend the RefreshLess library, as that may also delay setting
// this, and it isn't currently possible to extend declaratively only if both
// libraries are attached. With JavaScript aggregation, this ends up pretty
// tiny and should have zero side effects.
AmbientImpact.defaults.detachTriggers.push('refreshless:before-cache');
