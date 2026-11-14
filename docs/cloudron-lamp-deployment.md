# Cloudron LAMP deployment

- Target hostname: `www.apes.org.uk`
- Cloudron app ID: `cfeb1a50-eb8d-4cfd-ac84-a0606c6051ec`
- Target public webroot: `/app/data/public`
- Apache override path: `/app/data/apache/app.conf`
- PHP: 8.4, set by `/app/data/PHP_VERSION`
- MySQL, SMTP and phpMyAdmin: not used by this site

## Source-of-truth rendering workflow

- Shared PHP under `public/index.php` and `public/includes/` is the canonical source of truth.
- Local preview runs in Laravel Herd (`http://apes-cic-website.test`). Herd uses nginx, so the root `LocalValetDriver.php` applies the `public/.htaccess` redirects and forbidden paths locally; Cloudron's Apache reads the same file directly. See `docs/herd/profile.md`.
- Optional static snapshots: `powershell -ExecutionPolicy Bypass -File .\scripts\export-static-site.ps1` writes `dist/static-export`, which is never copied back into `public/` or deployed.

Do not publish `docs/`, root governance files, local logs or private notes. Only `public/` is deployed.

## Deploying a release

Work ships once per milestone (`.cursor/rules/version-shipping.mdc`). After the ship gate, with the release tag published:

```powershell
deploy/cloudron/deploy.ps1 -Tag vX.Y.Z
```

The script:

1. checks the tag is on `main`, has a published Release, and that its `public/VERSION` matches;
2. takes a Cloudron backup;
3. packs `public/` at the tag with `git archive` and uploads the single archive with `cloudron push`;
4. unpacks it beside `/app/data/public`, stops if the live site has files the tag does not (rerun with `-AllowRemovals` once they are confirmed as unwanted), then swaps the folders;
5. checks `https://www.apes.org.uk/VERSION` serves the tag's version, and swaps the previous folder back if not.

It prints the time each stage took. The previous site stays in `/app/data/public.previous` until the next deploy.

After the deploy, visit the homepage, Services hub, Donate page, Contact page, Change Log Hub, policy routes, one legacy news redirect and one missing route, or run `scripts\validate-public-site.ps1 -BaseUri https://www.apes.org.uk`.

## Rollback

1. Swap back to the previous folder: `cloudron exec --app cfeb1a50-eb8d-4cfd-ac84-a0606c6051ec -- sh -c 'cd /app/data && mv public public.failed && mv public.previous public'`, or deploy the previous tag with `deploy.ps1 -Tag`.
2. If that is not enough, restore the backup the deploy took.
3. Re-check the homepage, donate page, contact page, change-log route and policy routes.
