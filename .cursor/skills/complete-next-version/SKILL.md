---
name: complete-next-version
description: Complete the next version of this website end to end - run the live preview gate, find the next milestone with php scripts/milestone-version next, create or resume its milestone/vX-Y-Z branch, commit every issue on it behind one draft PR, then hand over to the release-version skill for merge, release and deploy. Use when the user says "complete the next version", "next version", "next milestone", "start the milestone" or "resume the milestone".
---

# Complete the next version

Follows `.cursor/rules/version-shipping.mdc`. Run it from one plan; when a new chat resumes a milestone, carry on with that plan.

## Steps

1. **Live preview gate** (`.cursor/rules/laragon-local-dev.mdc`): `powershell -NoProfile -ExecutionPolicy Bypass -File scripts\preview.ps1`, then open the printed URL in Cursor's browser, confirm it loaded and say "Preview ready, starting work". Exit 3 means a decision: ask with AskQuestion. Exit 1 means a blocker: report it.
2. **Find the version.** `php scripts/milestone-version next`. Stop and report, without guessing, if it fails, prints a warning about a milestone you were asked to work on, or names a different milestone than the user asked for.
3. **Branch.** Use the printed milestone branch. Create it from `origin/main` if it is missing (`git switch -c <branch> origin/main`, `git push -u origin HEAD`), otherwise switch to it and merge `origin/main`. Merge `origin/dependencies` with a merge commit. Stop on any conflict. Call `SetActiveBranch`.
4. **Issues.** List the milestone's issues (`gh issue list --milestone "<title>" --state open`). Post the Started comment on each (`Add-IssueUpdate.ps1`).
5. **Draft PR.** After the first commit, open one draft PR into `main` titled with the printed "Milestone PR" title, with `Closes #<n>` for every issue.
6. **Work every issue in order** on the milestone branch. Small Conventional Commits with `Refs #<n>`, push when useful, check every changed page in the preview (desktop and mobile), keep the SEO, sitemap, footer and error-page rules in `AGENTS.md`. Post the Committed comment when an issue is done; leave it open. Don't ask between issues; stop only for a failure or blocker the plan does not settle.
7. **Hand over** to the `release-version` skill once every issue is committed.

## Never

- Create per-issue branches or open a second PR into `main`.
- Bump the version for one issue.
- Use GitHub's milestone number in any name.
