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
