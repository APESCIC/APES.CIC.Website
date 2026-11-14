# Contributing to APES CIC Website

Thank you for helping. This page is the short version; the rules agents and people follow are in [`AGENTS.md`](AGENTS.md), [`.cursor/rules/version-shipping.mdc`](.cursor/rules/version-shipping.mdc) and [`.cursor/rules/herd-local-dev.mdc`](.cursor/rules/herd-local-dev.mdc).

## Local preview

Laravel Herd is the supported local set-up.

1. Run `powershell -NoProfile -ExecutionPolicy Bypass -File scripts\herd-setup.ps1` once. It links your clone in Herd as `apes-cic-website` on PHP 8.4, so Herd serves `http://apes-cic-website.test` from `public/`. The root `LocalValetDriver.php` applies the `public/.htaccess` redirects and forbidden paths, which Herd's nginx would otherwise ignore.
2. Before each change, run `powershell -NoProfile -ExecutionPolicy Bypass -File scripts\preview.ps1`. It pulls the latest changes and starts Browsersync on `http://localhost:3104`, which reloads the page when you save.
3. Check every page you change on desktop and mobile, and run `php scripts/check-site.php`.

## How work ships

- Work happens on the milestone's branch, `milestone/vX-Y-Z-<slug>`, behind one draft pull request into `main`. There are no per-issue branches or pull requests.
- Commit messages follow Conventional Commits and name the issue: `fix: correct the donate link (Refs #123)`.
- Names carry the milestone's version, never GitHub's milestone number. `php scripts/milestone-version next` prints the branch, PR title and Release title; CI checks the PR title and branch name.
- The version is set once per milestone with `php scripts/milestone-version bump vX.Y.Z`, with the `## [X.Y.Z] - YYYY-MM-DD` change log entry, as the last commit.
- The pull request merges once, as a merge commit, after Bambie's ship-gate answer. The tag `vX.Y.Z` publishes the Release, and that tag is deployed with `deploy/cloudron/deploy.ps1 -Tag vX.Y.Z`.
- A hotfix may skip these stages only with Bambie's written approval, recorded on the issue.

## Checks on every pull request

`ci` (site checks, PHP lint, ESLint where configured), `milestone-version-check`, `dependency-review` and CodeQL. They are all free GitHub features.

## Data and secrets

Never commit passwords, keys, tokens, `.env` files or personal data. Example content uses `example.test` addresses.
