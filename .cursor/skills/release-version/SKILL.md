---
name: release-version
description: Ship a completed website version - set the version once with php scripts/milestone-version bump, write the change log entries, mark the milestone PR ready, get CI green, ask the ship-gate question, then merge once (merge commit), tag vX.Y.Z (release.yml publishes the Release), deploy that tag to Cloudron with evidence and close out. Use when every issue in the milestone is committed, or when the user asks to merge, release, tag or deploy the milestone.
---

# Release a version

Follows `.cursor/rules/version-shipping.mdc` and the `ship-gate` and `cloudron-deploy` skills.

## Steps

1. **Sync.** Run `scripts\preview.ps1` (it merges `origin/main` into the milestone branch).
2. **Set the version once.** `php scripts/milestone-version bump vX.Y.Z`. It refuses a version that is not the next milestone's, and sets `VERSION`, `public/VERSION` and every footer listed in `scripts/MilestoneVersion/fields.json`. Check the footers in the preview.
3. **Last commit.** Add `## [X.Y.Z] - YYYY-MM-DD` to `CHANGELOG.md` and `public/CHANGELOG.md` and the new entry to the Change Log Hub page, then commit `chore: release vX.Y.Z`. Push.
4. **Ready.** `gh pr ready <pr>`, then `gh pr checks <pr> --watch --fail-fast`. Fix failures on the same branch.
5. **Check the names.** `php scripts/milestone-version check-release vX.Y.Z "<release title>"`, using the "Release title" from `php scripts/milestone-version next`.
6. **Ship gate.** Summarise the milestone, PR, checks, merge method, tag and deploy target, then ask with exactly: a. Merge, b. Release, c. Deploy, d. Merge, release and deploy, e. Stop here. Run only what was chosen; after each single step ask again with what remains.
7. **Merge.** `gh pr merge <pr> --merge --delete-branch`.
8. **Release.** Tag the merge commit; `release.yml` checks the tag and publishes the Release:

   ```powershell
   git fetch origin; git tag -a vX.Y.Z -m "vX.Y.Z" <merge-sha>; git push origin vX.Y.Z
   gh run watch (gh run list --workflow release.yml --limit 1 --json databaseId --jq '.[0].databaseId') --exit-status
   ```

9. **Deploy.** `deploy/cloudron/deploy.ps1 -Tag vX.Y.Z` (backup, upload the tag's `public/`, swap, verify `/VERSION`, roll back on failure). Evidence: its output and `https://www.apes.org.uk/VERSION`.
10. **Close out.** `Close-IssueTree.ps1` per issue and `Close-Milestone.ps1`, then merge `origin/main` into `dependencies`. Post the completion message with the PR, merge commit, issues, release and deploy evidence.

## Never

- Squash or rebase the milestone PR, tag anything but the merge commit, or deploy a branch.
- Rewrite a past tag, Release or change log entry.
- Report a step without its command output.
