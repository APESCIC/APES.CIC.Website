# Laragon profile

This website is its own Laragon project. The values below are in `profile.json`, which `scripts/laragon-setup.ps1` and `scripts/preview.ps1` read. Laragon is the only supported local set-up.

| Item | Value |
| --- | --- |
| Project folder | `C:\laragon\www\apes-cic-website`, a junction to this clone |
| Site | `http://apes-cic-website.test` (Laragon's Auto Virtual Hosts, from the folder name) |
| Document root | `public/`, as on Cloudron |
| Web server | Laragon's Apache 2.4 on port 80 |
| PHP | Laragon's PHP 8.4, the NTS build, through FastCGI (`mod_fcgid`). Only `.php` files go through PHP; HTML, CSS and images are served by Apache directly |
| Database | None. There is no demo seeder, because the site stores no records |
| Live reload | Browsersync on `http://localhost:3104` (UI on 3114), proxying `http://apes-cic-website.test` and reloading on any change under `public/` |
| Node | Laragon's Node 22, for Browsersync only. The deployed site needs no Node |

## Why the NTS build and FastCGI

Cloudron's LAMP app runs PHP 8.4. Laragon's Apache runs PHP through FastCGI (`C:\laragon\etc\apache2\fcgid.conf`), which needs the non-thread-safe build. Each site's PHP runs in its own process, so one project's settings can't leak into another. `php_value` lines in `.htaccess` don't work under FastCGI: keep them inside `<IfModule php_module>` for Cloudron, and put any local equivalent in `public/.user.ini`.

## Set up

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File scripts\laragon-setup.ps1
```

It is safe to run twice and only touches this project: it creates the junction, checks the PHP build and the FastCGI handler, runs `npm ci` if `node_modules/` is missing, and checks the site serves this clone. If Laragon has not created the virtual host yet, click **Menu > Apache > Reload** in Laragon and run it again.

## Daily loop

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File scripts\preview.ps1
```

It pulls the latest changes, starts Browsersync and checks `http://apes-cic-website.test/VERSION` matches this checkout. Open `http://localhost:3104`.
