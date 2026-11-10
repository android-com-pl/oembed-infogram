# oEmbed Infogram

Small WordPress plugin for Infogram oEmbed, a `core/embed` variation, and AMP support.

- Target WordPress 7.0+ and PHP 7.4+. Use pnpm and Composer.
- Optional agent skills are listed in `skills-lock.json` and can be restored with `pnx skills experimental_install`.
- Follow existing formatting and WordPress PHP conventions. Use the `oembed-infogram` text domain.
- Keep changes focused. Do not reformat unrelated code or automatically format Markdown.
- Build editor assets with `pnpm build`. `build/` is gitignored but included in WordPress.org releases.
- Run `pnpm format:check` and `composer analyse:phpcs` for relevant changes. No automated test suite is configured.
- Use `.wp-env.json` for local WordPress. For embed changes, check pasted URLs, the editor variation, and AMP output with explicit and missing dimensions. Check unrelated embeds still work.
- The WordPress Playground blueprint is `.wordpress-org/blueprints/blueprint.json`.
- Start commit titles with a [gitmoji](https://gitmoji.dev/) and a short imperative summary. List emojis with `gitmoji -l` or `pnx gitmoji-cli -l`.
- Follow `SECURITY.md` for vulnerability reports. Never commit credentials.
- External publication requires explicit authorization. Publishing a release deploys to WordPress.org.
