# NextGen LMS

Moodle 5.2 child theme of Boost. Install it from `public/theme/nextgen`.

## Build

Requires Node.js 20 or newer.

```bash
npm install
npm run build
npm run watch
```

`npm run build` writes `style/nextgen.css`. Commit that file so a site can install the theme without Node. The shell appearance also lives in `scss/shell.scss`, which Moodle compiles with the Boost preset.
The shell stylesheet also covers Moodle's fatal-error/404 panel, notifications, loading indicators, course progress, activity completion, and quiz states.
The learner dashboard presents Moodle-awarded badges and the learner's own issued certificates. Certificate PDFs, unique issue codes, verification, and issue notifications remain provided by Moodle's Certificate plugins.

## Certificates and achievements

The public course-detail certificate tab previews the PDF generated from the visible Course certificate activity's selected Tool Certificate template. It uses a generic preview learner and does not create an issued certificate. Configure the desired NextGen-branded artwork and fields in the Tool Certificate template itself; the theme does not replace Moodle's issue or verification flow.

For verified, shareable certificates:

1. In the Tool Certificate template, add a Code element and choose the QR-code display option. The QR code points to Moodle's public verification page.
2. Set the Course certificate activity to send certificates automatically. Moodle's scheduled task issues certificates to eligible learners; Moodle cron must run for scheduled issuance.
3. Configure Moodle's outgoing mail and learner notification preferences. Moodle attaches the generated PDF to its certificate-issued notification when the message is delivered.
4. Grant the Guest role `tool/certificate:verify` in the system context if verification should work without signing in. Use the dashboard's verification link or share actions to share the public verification page. Each issued certificate code is unique and verified by Moodle.

Moodle's badge criteria and award process remain unchanged; NextGen only displays badges already awarded to the signed-in learner.

Tailwind utilities use the `tw:` prefix, for example `tw:bg-primary`. Semantic components use `ng-` classes. Brand tokens are `--ng-primary` and the related variables. The prefix keeps Tailwind away from Bootstrap class names such as `collapse`.

Brand colours are CSS variables (`--ng-primary` and the rest). Change them in Site administration → Appearance → Themes → NextGen. A Tailwind rebuild is not required for those settings.

## Caches

After template, language, or JavaScript changes:

1. Site administration → Development → Purge all caches.
2. Or, from the Moodle directory: `php admin/cli/purge_caches.php`.

After CSS changes, rebuild Tailwind if you edited `tailwind/theme.css`, then purge caches. With theme designer mode on, SCSS in `scss/shell.scss` is recompiled on each request. Turn designer mode off before treating the theme as production-ready, and purge caches once more.

## Local install on this machine

The theme directory is joined to Moodle with:

```text
public/theme/nextgen  →  this folder
```

Then run Moodle upgrade so the plugin is registered, and select NextGen under Appearance → Themes.
