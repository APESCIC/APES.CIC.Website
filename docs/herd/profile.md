# Laravel Herd profile

This website is served locally by Laravel Herd. The values below are in `profile.json`, which `scripts/herd-setup.ps1` and `scripts/preview.ps1` read. Herd is the only supported local set-up.

| Item | Value |
| --- | --- |
| Site | `http://apes-cic-website.test`, the repo linked in Herd as `apes-cic-website` (`herd link`) |
| Document root | `public/` (Herd serves `public/` when it exists) |
| Web server | Herd's nginx on port 80 |
| `.htaccess` | Applied by the root `LocalValetDriver.php` (see below) |
| PHP | Herd's PHP 8.4, isolated to this site (`herd isolate 8.4`), the same version as Cloudron. Only `.php` files and routed pages go through PHP |
| Database | None |
| Node | Herd's Node 22 (from its nvm folder), for Browsersync only. The deployed site needs no Node |
| Live reload | Browsersync on `http://localhost:3104` (UI on 3114), proxying the Herd site and reloading on save |

## Why a local driver

Cloudron's LAMP app serves the site with Apache, which reads `public/.htaccess`. Herd uses nginx, which ignores it. `LocalValetDriver.php` in the repo root is loaded by Herd for this site only: on every request it reads `public/.htaccess` and applies its forbidden paths (`[F]`, served as `public/403.html` with a 403) and its redirects (`[R=301]`, with Apache's query-string handling). Everything else goes to `public/index.php`, which renders the pages and the branded 404.

- `public/.htaccess` stays the only place the rules live. Add or change a rule there and the preview follows it.
- The driver understands `RewriteCond %{HTTP_HOST}` (so the beta-host rule never fires locally), `$1` back-references and `%{REQUEST_URI}` in targets. Rules with other conditions are skipped.
- The `X-Content-Type-Options` and `Referrer-Policy` headers reach PHP responses only; nginx drops them from static files.
- `php_value` lines in `.htaccess` do nothing on Herd: keep them inside `<IfModule php_module>` for Cloudron, and put any local equivalent in `public/.user.ini`.
- The driver sits outside `public/`, so `deploy/cloudron/deploy.ps1` never uploads it.

## Set up or repair

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File scripts\herd-setup.ps1
```

It is safe to run twice and only touches this site: it links the repo in Herd if needed, isolates it to PHP 8.4, runs `npm ci` if `node_modules/` is missing, and checks the site serves this clone. If Herd is not running, start the Herd app and run it again.

Then run the live preview gate:

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File scripts\preview.ps1
```
