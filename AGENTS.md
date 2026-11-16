# Agent instructions for APES CIC Website

Cursor's user rules and skills set how work is done here (branches, issues, pull requests, merge, release, deploy, ship gate, decisions and safety). This file describes this repository and fills the named slots below. If anything here disagrees with them, Cursor's rules win and this file is corrected in the same milestone.

Cursor/agent layout standard: https://github.com/apescic-director/Cursor/blob/main/docs/cursor-agent-standards.md

The owner is Bambie Murphy, Director. Write UK English (organisation, behaviour, colour, licence) in the site, docs, issues and pull requests. Never print, paste or commit passwords, secrets, tokens, keys, `.env` values or Cloudron credentials (including the app's `/app/data/credentials.txt`). Secrets live only in APES Vault (Bitwarden).

## About the app

APES CIC Website (`https://www.apes.org.uk`) is the public website of the Association of Protecting Exotic Species CIC (APES CIC): rescue, rehoming, services, donations, policies and the Change Log Hub, with news handed off to APES Newsroom.

- Repository: `APESCIC/APES.CIC.Website`. Work only in this repository.
- Every Cloudron app is production. Read-only commands (status, logs) run freely; anything else follows Cursor's `cloudron-ops` and `cloudron-deploy` skills. Don't change DNS or Cloudflare.
- Security checks use GitHub's free features only: Dependabot (into the `dependencies` branch), dependency review and CodeQL. Don't add paid or third-party scanners.
- Development is tracked only in GitHub issues, sub-issues and `vMAJOR.MINOR.PATCH` milestones — never Linear.

## Stack and architecture

- PHP-first website served from `public/` (PHP 8.4 on Cloudron). No database, no Composer packages; Node is used locally for Browsersync only.
- `public/index.php` renders every route from `public/includes/site-data.php` through `render-page.php`; `apes_version()` reads `VERSION` for the footer.
- `public/.htaccess` holds the clean-URL fallback, the 301 redirects, the forbidden paths and the error documents. It is the only place those rules live: Cloudron's Apache applies it, and locally `LocalValetDriver.php` applies it under Herd.
- `scripts\export-static-site.ps1` writes optional static snapshots to `dist/static-export` (never deployed).
- Selected website type: PHP website (public pages rendered by PHP; no database).

## Local development and preview

Laravel Herd is the only supported local stack. There is no database and no demo seeder. Run the live preview gate before any change (steps in [`herd-local-dev.mdc`](.cursor/rules/herd-local-dev.mdc)); profile: [docs/herd/profile.md](docs/herd/profile.md).

| Item | Value |
| --- | --- |
| Clone | `C:\Users\BambieMurphy\Documents\GitHub\APES.CIC.Website` |
| Herd site | `apes-cic-website` (linked; PHP 8.4 isolated, as on Cloudron) |
| Preview URL | `http://apes-cic-website.test` (not secured; Browsersync proxies it) |
| Preview command | `scripts\preview.ps1` (starts `npm run dev`) |
| Live reload | Browsersync on `http://localhost:3104` (UI 3114), no Vite |
| HMR port | None (Browsersync live reload on 3104) |
| Set up or repair | `scripts\herd-setup.ps1` |
| Env file | None |
| Database | None |
| Mail | None (the site sends no mail) |

### Local development with Laravel Herd

This project runs on Laravel Herd. Run `herd site:info` for the URL, PHP version and connected services.

- Use `herd php` so commands use this site's PHP version.
- Use `herd status` if something doesn't respond; `herd list` shows every command (`--json` for structured output).
- Herd's nginx ignores `.htaccess`; root `LocalValetDriver.php` applies `public/.htaccess` locally (never deployed).
- Put Herd's Node 22 first on `PATH` for Browsersync (see the Herd rule).

## Tests and checks

| Check | Command |
| --- | --- |
| Site checks (VERSION, pages, sitemap, 404) | `herd php scripts/check-site.php` (also run by CI) |
| Routes, redirects, 403, 404 and footer against Herd | `powershell -NoProfile -ExecutionPolicy Bypass -File scripts\validate-public-site.ps1` |
| PHP lint | `herd php -l <file>` (CI lints every tracked `.php` file) |
| CI workflows | `.github/workflows/ci.yml` (`ci`), `milestone-version-check`, `dependency-review`, CodeQL |

## Workflow slots

Shipping follows Cursor's `github-milestone-completion` and `ship-gate` skills. Repo flow is in [`version-shipping.mdc`](.cursor/rules/version-shipping.mdc); agents run [`complete-next-version`](.cursor/skills/complete-next-version/SKILL.md) and [`release-version`](.cursor/skills/release-version/SKILL.md), or `/complete-next-version`. Public-site change checks: [`website-change-standards`](.cursor/skills/website-change-standards/SKILL.md).

| Slot | Value |
| --- | --- |
| Milestone branch pattern | `milestone/vX-Y-Z-<slug>`, printed by `herd php scripts/milestone-version next` (CI checks it) |
| PR and Release titles | `v<version>: <milestone name>` and `v<version> <milestone name>`, printed by the same command |
| Version files | `VERSION`, `public/VERSION` and the fields in `scripts/MilestoneVersion/fields.json`, set once by `herd php scripts/milestone-version bump vX.Y.Z` |
| Release records | `## [X.Y.Z] - YYYY-MM-DD` in `CHANGELOG.md`, `public/CHANGELOG.md` and the Change Log Hub (`public/includes/site-data.php`) |
| Release tooling | Pushing the `vX.Y.Z` tag runs `release.yml`, which publishes the GitHub Release |
| Area labels | None |
| Issue title prefix | `[Type] Short title`, assignee `apescic-director` |

## Deploy and health

| Item | Value |
| --- | --- |
| Target | Cloudron LAMP app |
| Cloudron app ID | `cfeb1a50-eb8d-4cfd-ac84-a0606c6051ec` |
| Domain | `https://www.apes.org.uk` |
| Web root | `/app/data/public` (PHP 8.4, set by `/app/data/PHP_VERSION`) |
| Deploy command | `deploy/cloudron/deploy.ps1 -Tag vX.Y.Z`: backup, upload the tag's `public/`, swap, check `/VERSION`, roll back on failure |
| Deploys | The release tag (never a branch) |
| Auto-deploy couplings | None: publishing the Release does not deploy |
| Health URL | `https://www.apes.org.uk/VERSION` |
| Version field | The whole response body (for example `4.1.2`) |
| Secrets live in | APES Vault (never commit secrets) |

## Decisions and gotchas

- 2026-11-14: Laravel Herd Pro is the local development stack (Bambie, [#34](https://github.com/APESCIC/APES.CIC.Website/issues/34)). `public/.htaccess` stays the single source of the rules, applied locally by `LocalValetDriver.php`.
- Herd hands static files to nginx directly, so the `X-Content-Type-Options` and `Referrer-Policy` headers only appear on PHP responses locally. Cloudron's Apache sends them on every response.
- Checked-in static snapshots (`public/index.html`, `public/<route>/index.html`) are served ahead of the PHP pages, by Apache's `DirectoryIndex index.html index.php` and the same way by Herd ([#32](https://github.com/APESCIC/APES.CIC.Website/issues/32)). Until they go, `validate-public-site.ps1` stops at its snapshot checks.
- `scripts/milestone-version next` still prints per-issue "Issue branch" and "PR title" lines; ignore them, as all work goes on the milestone branch ([#36](https://github.com/APESCIC/APES.CIC.Website/issues/36)).

## Where to find things

- Herd profile and local driver notes: [docs/herd/profile.md](docs/herd/profile.md).
- Cloudron deployment: [docs/cloudron-lamp-deployment.md](docs/cloudron-lamp-deployment.md) and `deploy/cloudron/`.
- Release history: [CHANGELOG.md](CHANGELOG.md), [public/CHANGELOG.md](public/CHANGELOG.md) and the Change Log Hub (`/change-log-hub/`).
- Contributor summary: [CONTRIBUTING.md](CONTRIBUTING.md).
- Website change standards (changelog, footer, SEO, Cloudron LAMP checks): [`.cursor/skills/website-change-standards/SKILL.md`](.cursor/skills/website-change-standards/SKILL.md).
