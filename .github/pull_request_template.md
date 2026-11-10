<!-- Title: v<version>: <milestone name>. One pull request per milestone, merged once as a merge commit. -->

## Milestone

`vX.Y.Z <milestone name>`

## Summary

-

## Issues

Closes #

## Checks

- [ ] `php scripts/check-site.php` passes
- [ ] Changed pages checked in the Laragon preview (`scripts\preview.ps1`), desktop and mobile
- [ ] SEO, sitemap, footer and error pages updated where pages changed
- [ ] `php scripts/milestone-version bump vX.Y.Z` and the change log entry are the last commit (`chore: release vX.Y.Z`)
- [ ] No secrets, credentials or personal data

## Deploy

After the ship gate: tag `vX.Y.Z` on the merge commit, then `deploy/cloudron/deploy.ps1 -Tag vX.Y.Z`.
