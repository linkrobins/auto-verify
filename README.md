# Link Robins Auto Verify

A [Flarum 2](https://flarum.org) extension that activates new accounts the moment they register, so members can post straight away without clicking a confirmation link in their email. An admin switch pauses it (falling back to Flarum's normal email confirmation) without disabling the extension.

Handy when your forum has no outgoing mail configured, when confirmation emails keep landing in spam, or when you simply want a friction-free signup and rely on other tools for spam control.

## Features

- **Instant activation.** A new account is marked email-confirmed at registration, so the "please confirm your email" step is skipped and the member can log in and post immediately.
- **Admin kill-switch.** One checkbox on the extension's settings page turns auto-verification off and restores Flarum's normal email confirmation. Useful during a spam wave, with no need to disable the whole extension. On by default.
- **New accounts only.** The activation fires only for brand-new registrations. Saving an existing unconfirmed user (for example an admin renaming them) never sneaks that account past confirmation, and accounts that registered while the switch was off stay unconfirmed until they confirm.
- **Nothing else changes.** Usernames, passwords, permissions and every other extension's signup logic behave exactly as before.

## Settings

Admin, then Extensions, then Link Robins Auto Verify.

| Setting | Default | What it does |
|---|---|---|
| Auto-verify new accounts | On | When on, new accounts are activated immediately and email confirmation is skipped. Turn off to fall back to Flarum's normal email confirmation without disabling the extension. |

The extension adds no permissions and no database tables.

## How it works

The extension listens for Flarum's user `Saving` event and, for a user that has not been saved yet, activates the account, which is what Flarum's own email-confirmation flow does once a link is clicked. When the setting is off, the listener does nothing, so Flarum sends its confirmation email and holds the account unconfirmed as usual.

## A note on spam

Skipping email confirmation removes one barrier that slows down automated signups. That is the point when your mail is unreliable, but on an open forum it is worth pairing with another line of defense, for example approval for new members' first posts, a registration question or CAPTCHA extension, or manual account approval. Because the switch is one click, you can fall back to email confirmation instantly if you come under a spam wave.

## Requirements

- Flarum 2.x
- PHP 8.3 or newer
- No dependencies beyond `flarum/core`

## Installation

```sh
composer require linkrobins/auto-verify
```

Then toggle it on in the admin panel's **Extensions** page. There are no migrations.

## Updating

```sh
composer update linkrobins/auto-verify
php flarum cache:clear
```

## License

MIT
