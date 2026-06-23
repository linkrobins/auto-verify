import app from 'flarum/admin/app';

// Expose the kill-switch as an admin checkbox so auto-verification can be paused
// (falling back to email confirmation) without disabling the extension.
app.initializers.add('linkrobins-auto-verify', () => {
  app.registry.for('linkrobins-auto-verify').registerSetting({
    setting: 'linkrobins-auto-verify.enabled',
    type: 'boolean',
    label: app.translator.trans('linkrobins-auto-verify.admin.settings.enabled_label'),
    help: app.translator.trans('linkrobins-auto-verify.admin.settings.enabled_help'),
  });
});
