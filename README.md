# Link Robins Auto Verify

A [Flarum 2.0](https://flarum.org) extension that activates new accounts the moment they register, so members can post straight away without clicking a confirmation link in their email. Includes an admin toggle to pause it (falling back to Flarum's normal email confirmation) without disabling the extension.

Handy when your forum has no outgoing mail configured, when confirmation emails keep landing in spam, or when you simply want a friction-free signup and rely on other tools for spam control.

## What it does

- **Instant activation.** A new account is marked email-confirmed at registration, so the "please confirm your email" step is skipped and the member can log in and post immediately.
- **Admin kill-switch.** A single checkbox on the extension's settings page turns auto-verification off, restoring Flarum's normal email confirmation. Useful during a spam wave without having to disable the whole extension. On by default.
- **New accounts only.** The activation fires only for brand-new registrations. Editing an existing unconfirmed user (for example an admin renaming them) never sneaks that account past confirmation.

## How it works

The extension listens for Flarum's user `Saving` event and, for a not-yet-persisted user, calls `activate()` on them, which is what Flarum's own email-confirmation flow does once a link is clicked. It changes nothing else about registration: usernames, passwords, permissions, and every other extension's signup logic behave exactly as before.

When the admin toggle (`linkrobins-auto-verify.enabled`) is off, the listener returns early and does nothing, so Flarum sends its confirmation email and holds the account unconfirmed as usual.

## A note on spam

Skipping email confirmation removes one barrier that slows down automated signups. That is the point when your mail is unreliable, but on an open forum it is worth pairing with another line of defence, for example Flarum's post approval, a registration question / CAPTCHA extension, or manual account approval. The kill-switch is there so you can fall back to email confirmation instantly if you ever come under a spam wave.

## Installation

```sh
composer require linkrobins/auto-verify
php flarum cache:clear
```

Then enable it from the admin extensions panel. No migrations.

## Configuration

One setting, on the extension's page in the admin panel:

| Setting | Default | What it does |
|---|---|---|
| `linkrobins-auto-verify.enabled` | on | When on, new accounts are activated immediately. Turn off to fall back to Flarum's normal email confirmation without disabling the extension. |

## Compatibility

- Flarum 2.0 (tested through 2.0.0-rc.5)
- PHP 8.3+
- No dependencies beyond `flarum/core`

## License

MIT
