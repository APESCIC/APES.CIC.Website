# APES CIC Universal Agent Instructions

Use this file as the universal `AGENTS.md` operating profile for APES CIC repositories and related website codebases maintained by the Association of Protecting Exotic Species CIC.

This file is written for Codex, VS Code based agents, GitHub Copilot Chat, repository assistants, and similar AI coding agents. It combines Codex, VS Code, GitHub, local preview, issue tracking, branch, pull request, validation, Cloudron LAMP, UK GDPR, and APES CIC documentation rules into one shared standard.

Agents must load this guidance alongside any local `AGENTS.md`, `INSTRUCTIONS.md`, `README.md`, `CONTRIBUTING.md`, issue, pull request, workspace file, package file, Composer file, build file, configuration file, task file, or task specific instruction.

If local guidance conflicts with this profile, do not weaken APES CIC compliance, issue tracking, branch protection, pull request review, validation, versioning, changelog, Change Log Hub, SEO, sitemap, footer, error page, public folder, local preview, Cloudron LAMP, UK GDPR, safety, or documentation requirements without explicit user approval.

This file is for agent behaviour only. It must not contain private credentials, production secrets, environment values, database passwords, SMTP passwords, OAuth secrets, API keys, private keys, or generated credential files.

## 1. Core workflow rule

For APES CIC repositories, use this rule every time:

```text
No issue decision, no branch decision, no work on main, no merge without pull request review and explicit merge approval.
```

Default universal workflow order:

```text
Understand request -> Ask only the right grouped questions -> Issue decision -> Branch decision -> Open or inspect workspace -> Inspect repository -> Start or reuse local preview -> Plan -> User approval -> Implementation -> Work updates -> Validation -> Diff review -> Commit approval -> Commit -> Publish branch decision -> Pull request decision -> Pull request review -> Merge decision -> Branch cleanup decision -> Pull latest main decision -> Completion update
```

This protects `main`, gives each update a clear audit trail, supports review, and makes work easier to audit, undo, continue, or hand over.

Do not skip a stage unless the user explicitly approves the skip, or the task is clearly trivial and the agent records the safe assumption.

## 2. Supported agent contexts

This guidance applies to:

1. Codex working in a GitHub repository.
2. Codex working in planning mode or implementation mode.
3. VS Code based agents using Source Control, integrated terminal, workspace search, tasks, extensions, browser preview, GitHub integration, or agent chat.
4. GitHub Copilot Chat or similar coding agents working inside a local repository.
5. Repository assistants preparing issues, branch plans, pull request text, release notes, validation summaries, or implementation handoffs.

When a rule is tool specific, follow the relevant tool section. All common safety, branch, issue, validation, Cloudron LAMP, documentation, UK GDPR, and pull request rules apply in every tool.

## 3. Universal operating rules

Before planning or editing, the agent must:

1. Confirm the active repository.
2. Confirm the current branch.
3. Check whether the current branch is protected, shared, production, staging, release, `main`, or `master`.
4. Check for uncommitted changes before making changes.
5. Never overwrite user work.
6. Read relevant repository guidance before editing.
7. Read relevant source files before changing them.
8. Use workspace search, repository search, or file search before editing files.
9. Prefer repository scripts, documented commands, VS Code tasks, and existing tooling over inventing new commands.
10. Keep implementation inside the approved scope.
11. Stop and ask before expanding scope.
12. Provide changed file summaries and validation results before any commit.
13. Never commit private configuration or private data.
14. Never deploy automatically.
15. Never edit `main`, `master`, `production`, `staging`, release branches, or shared long lived branches directly.
16. Ask decision questions at the correct workflow point, not randomly.
17. Give the user clear options, including a recommended option and a custom option, whenever asking them to choose.

If the agent is unsure whether a change is safe, pause and explain the options.

## 4. Clear user choice standard

Every user question that asks for a decision must be practical, ordered, and easy to answer.

When asking the user to choose, the agent must:

1. Explain why the decision is being asked now.
2. Ask at the correct workflow stage.
3. Give clear numbered options.
4. Mark the recommended option where one is safe to recommend.
5. Include a custom option.
6. Avoid asking the same question twice when the user has already answered it.
7. Avoid asking future stage questions too early unless grouping them prevents interruption.
8. Record the answer in the plan, work notes, issue, pull request, or final summary where relevant.

Use this default option shape:

```text
Please choose one option:

1. Recommended: [safe default and reason].
2. [Alternative option and when to use it].
3. [Defer or do nothing option and consequence].
4. Custom: tell me exactly what you want instead.
```

Do not ask vague questions such as `What do you want me to do?` when clear choices can be provided.

Do not ask branch, pull request, merge, or cleanup questions before repository inspection unless the user is explicitly setting workflow preferences at the start.

## 5. VS Code specific operating rules

When working in VS Code, the agent must:

1. Confirm the active workspace and repository before planning.
2. Check Source Control before making changes.
3. Use workspace search before editing.
4. Use the integrated terminal only for commands that are needed and safe.
5. Prefer VS Code tasks and documented repository commands where available.
6. Never install VS Code extensions, global tools, packages, dependencies, formatters, linters, or CLIs without approval.
7. Never change VS Code user settings, workspace settings, extension settings, recommended extensions, task files, or launch profiles unless the task requires it and the user approves it.
8. Never pull, reset, rebase, stash, force push, delete files, delete branches, or run database destructive commands without explicit approval.
9. Show the diff, validation results, risks, and recommended next step before state changing Git actions where practical.
10. For website repositories, open the website in the VS Code browser preview, Simple Browser, or approved local view at `http://127.0.0.1:8080/` where preview support exists.
11. For website repositories, confirm direct `file:///` access for browser served static pages and public assets, and record any justified server required blocker.

## 6. Codex specific operating rules

When working in Codex, the agent must:

1. Start in planning mode unless the user has explicitly approved implementation.
2. Confirm the current branch before planning.
3. Confirm the related GitHub issue number or recorded issue deferral.
4. Read repository guidance, package files, configuration files, source files, and task relevant files before planning.
5. Identify the website stack, limitations, website type, and hosting target.
6. Start or reuse the local preview server for website repositories before planning implementation or editing files, where preview support exists, using `http://127.0.0.1:8080/` unless repository guidance requires another local HTTP URL.
7. Use the Codex browser or available preview tool to inspect the current site state at the local preview URL where supported.
8. Confirm direct `file:///` access for browser served static pages and public assets, and record any justified server required blocker.
9. Do not edit files until the plan is approved.
10. During implementation, work only on the approved task branch.
11. After implementation, provide a concise handoff with changed files, validation, risks, release record status, GitHub status, and next steps.

## 7. User checkpoints

Ask the user before these state changing actions unless the user already clearly requested or approved the action:

1. Creating a GitHub issue.
2. Updating a GitHub issue.
3. Linking work to an existing GitHub issue.
4. Recording that no issue will be used.
5. Creating a branch.
6. Starting implementation.
7. Expanding scope beyond the approved plan.
8. Installing tools, extensions, packages, dependencies, formatters, linters, CLIs, or development servers.
9. Changing workspace, editor, extension, task, launch, or recommended extension settings.
10. Committing changes.
11. Pushing or publishing a branch.
12. Opening a pull request.
13. Marking a pull request ready for review.
14. Merging a pull request.
15. Enabling auto merge.
16. Deleting a remote branch.
17. Pulling latest `main` locally.
18. Deleting a local branch.
19. Closing an issue manually.
20. Running destructive commands such as reset, force push, rebase, stash, file deletion, database migration, database reset, or irreversible cleanup.

If the user has already provided the answer, record it in the plan and do not ask again.

If the user asks the agent to proceed without every answer, use the safest compliant default:

1. Ask for an issue decision only when the task is significant enough to need an audit trail.
2. Create a new task branch from the default branch when work will change repository files.
3. Defer issue tracking only where no issue details exist and record the reason.
4. Produce a plan before implementation.
5. Use a draft pull request when review is still needed.
6. Run repository default validation checks where available.
7. Do not merge or delete branches without explicit approval.
8. Do not install tools or dependencies without approval.
9. Do not change private configuration.

## 8. Required planning prompt

When starting a new GitHub task and the user has not already provided the details, ask one grouped prompt before implementation. Ask only what is needed for the stage and the task.

```text
Before I start, please choose:

1. Update type: feature, bug fix, clean up, migration, refactor, documentation, security, compliance, hosting, urgent hotfix, or custom.
2. Website type: static website, PHP website, MySQL database website, content management system, custom portal, small web application, migration, merge, documentation only, or custom.
3. Issue decision:
   1. Recommended for non trivial work: create a new GitHub issue.
   2. Use an existing issue and provide the issue number.
   3. Update an existing issue and provide the issue number.
   4. Do not record an issue for this task and record the reason.
   5. Custom issue option.
4. Branch decision:
   1. Recommended: create a new task branch from main.
   2. Create a new task branch from another approved base branch.
   3. Use an existing task branch after confirming it is safe.
   4. Do not create a branch because this is planning or text only.
   5. Custom branch option.
5. Pull request decision:
   1. Recommended for repository changes: open a draft PR after the first clean commit and validation.
   2. Open a ready for review PR after validation is complete.
   3. Prepare PR text only.
   4. No PR yet because the work is not ready or the user only wants local changes.
   5. Custom PR option.
6. Scope: files, folders, routes, or areas that are in scope.
7. Non scope: files, folders, routes, or areas that must not be changed.
8. Environment: any Cloudron, MySQL, Redis, email, LDAP, rewrite, private configuration, Windows, Linux, deployment, or local development setting to consider.
9. Local preview server: command to start or reuse before planning implementation or editing files, defaulting to http://127.0.0.1:8080/ where supported, plus file:/// compatibility checks for browser served static files.
10. Validation: repository default checks, named checks, manual checks, browser preview, file:/// static page check, or validation deferred with a reason.
```

After the user answers, produce a plan only. Do not edit files until the user approves the plan.

When creating a new website, merging websites, or migrating an existing website, agents must ask what type of website setup the user wants unless the type is already clear from the user request. The selected website type must control the stack, folder structure, runtime assumptions, routing, validation, documentation, and hosting notes.

## 9. Required planning response

Before editing, the agent must answer with this structure:

```text
Issue decision:
Branch decision:
Update type:
Website type:
Hosting target:
Local preview server:
Environment notes:
Files likely to change:
Implementation plan:
Work update plan:
Risks:
Validation:
Questions before implementation:
```

The plan must confirm:

1. Current branch.
2. Related issue number, planned issue creation, planned issue update, or recorded issue deferral.
3. Selected website type or documentation only status.
4. Target hosting where relevant.
5. Local preview URL or blocker.
6. Direct file:/// access status or justified blocker for browser served static files.
7. Private configuration protection.
8. Expected files to change.
9. Checks to run.
10. Documentation, changelog, version, public folder, SEO, sitemap, footer, error page, and release impact where relevant.
11. GitHub issue, branch, pull request, merge, and cleanup expectations.
12. When progress updates will be provided if working with an issue.

## 10. Issue workflow

Create, link, update, or explicitly defer a GitHub issue before starting real work when the task is non trivial, public facing, operationally relevant, compliance relevant, release relevant, hosting relevant, migration relevant, or likely to need an audit trail.

At the issue decision stage, provide these options when appropriate:

```text
Issue options:

1. Recommended: create a new GitHub issue for this work.
2. Use an existing issue and provide the issue number.
3. Update an existing issue and provide the issue number.
4. Do not record an issue for this task, with a short reason.
5. Custom: tell me another issue workflow.
```

Create an issue when:

1. The change is non trivial.
2. The change affects public pages, forms, portals, workflows, hosting, compliance, security, data, releases, or governance.
3. The task may need audit trail, review, handover, or follow up.
4. Multiple commits or a pull request are likely.
5. The task has acceptance criteria or testing requirements.

Updating an issue is appropriate when:

1. The issue already exists and covers the task.
2. The issue scope needs clarifying before implementation.
3. Work has started and progress needs recording.
4. Files changed, validation results, blockers, or follow up tasks need recording.
5. The issue should remain the central work log.

Choosing not to record an issue is acceptable when:

1. The task is trivial.
2. The task is planning only.
3. The user only wants wording, advice, or a local note.
4. The work is exploratory and no repository change will be made.
5. The user explicitly chooses no issue and the reason is recorded.

When working with an issue, provide issue updates at sensible points:

1. After the plan is approved, summarise the agreed scope and acceptance criteria.
2. After the branch is created, record the branch name.
3. During work, post or prepare updates when meaningful progress, blockers, scope changes, or validation results occur.
4. Before opening a pull request, record changed files and testing completed.
5. After pull request creation, link the pull request.
6. After merge, record completion, merge status, branch cleanup status, release record status, and remaining follow up work.
7. Do not spam the issue with low value updates for tiny edits.

The issue must define:

1. Title.
2. Type.
3. Website type or documentation only status.
4. Reason.
5. Scope.
6. Non scope.
7. Requirements.
8. Acceptance criteria.
9. Testing.
10. Release impact.
11. Hosting impact.
12. Local preview impact.
13. Public folder impact where relevant.
14. Metadata such as labels, milestone, assignees, project, priority, and status where available.

Issue body template:

```text
## Summary
Describe the change needed.

## Reason
Explain why this work is needed.

## Website type
State the selected website type and why it fits the repository.

## Scope
The agent may change:
1.
2.
3.

The agent must not change:
1.
2.
3.

## Requirements
1.
2.
3.

## Acceptance criteria
1. The change works locally where applicable.
2. The change does not break existing pages or workflows.
3. The selected website type is followed.
4. No private configuration or private data is committed.
5. The pull request explains what changed.
6. The branch can be safely merged into main.

## Testing required
1.
2.
3.
```

Creating or drafting an issue is an issue filing action only. Do not treat issue creation as approval to start implementation, create a branch, assign yourself, post a work started update, change issue status, edit files, commit, push, open a pull request, or begin work unless the user explicitly asks for that next action.

When starting from an existing issue, read it before planning and identify missing scope, acceptance criteria, website type, branch expectations, release impact, hosting impact, local preview requirements, validation requirements, metadata alignment, and blockers.

When updating an issue, include a `Files changed` section where files have changed. List each changed file path with a short note. If no files changed, say so explicitly.

Do not close an issue until it has a completion note covering changed files, validation, release record status, hosting status, local preview status, remaining limitations, and follow up work.

## 11. Branch workflow

Create a branch for every repository update unless the work is planning only, issue text only, PR review only, or the user explicitly chooses not to make repository changes.

Do not work directly on `main`.

All repository updates must be done on a new task specific branch. Do not edit `main`, `master`, `production`, `staging`, release branches, or shared long lived branches directly.

Ask about branch creation after the issue decision and repository inspection, but before implementation.

Branch creation options:

```text
Branch options:

1. Recommended: create a new task branch from main.
2. Create a new task branch from another approved base branch.
3. Use an existing task branch after checking it is safe.
4. Do not create a branch because this is planning, review, or text only.
5. Custom: provide a branch name and base branch.
```

Create a branch when:

1. Any repository file will be changed.
2. The task needs validation, commit, or pull request review.
3. The work relates to an issue.
4. The task may require more than one change.
5. The change must be auditable or reversible.

Do not create a branch when:

1. No repository file will be changed.
2. The user only wants advice, a plan, or issue text.
3. The task is only to review an existing pull request or issue.
4. The user explicitly chooses to defer implementation.

Branch naming structure:

```text
type/short-description
```

Preferred branch types:

1. `feature/` for new functionality.
2. `fix/` for bug repairs.
3. `cleanup/` for removing unused files or improving structure.
4. `docs/` for documentation only.
5. `migration/` for larger structural changes.
6. `refactor/` for improving code without changing behaviour.
7. `security/` for security fixes.
8. `hotfix/` for urgent production fixes.
9. `hosting/` for hosting and platform guidance.

Before creating a branch in VS Code, Codex, GitHub Desktop, or a local clone, check:

1. Current branch.
2. Working tree state.
3. Whether local changes are present.
4. Whether the base branch needs pulling.
5. Whether the requested branch already exists locally or remotely.

Ask about publishing the branch after a clean commit exists and before opening a pull request.

Publish branch options:

```text
Publish branch options:

1. Recommended: publish the task branch so a pull request can be opened.
2. Keep the branch local for now.
3. Prepare the push command only.
4. Stop before publishing.
5. Custom: provide another publishing instruction.
```

Publish a branch when:

1. A pull request will be opened.
2. Collaboration, review, CI, or handover is needed.
3. The user asked for changes to be pushed.
4. The work needs to be visible on GitHub.

Do not publish a branch when:

1. The diff has not been reviewed.
2. Validation has not run or been deferred.
3. The branch contains private configuration or unrelated changes.
4. The user only wants local work.
5. The user has not approved publishing where approval is required.

Ask about branch deletion only after merge or when abandoning a branch.

Branch deletion options:

```text
Branch cleanup options:

1. Recommended after merge: delete the remote task branch.
2. Keep the remote branch for now.
3. Delete the local branch after pulling latest main and confirming it is safe.
4. Keep the local branch for now.
5. Custom: provide another cleanup instruction.
```

Delete a remote branch only when:

1. The pull request is merged or safely closed.
2. There is no unmerged work to preserve.
3. The user approves deletion or repository settings automatically delete merged branches.

Do not delete a branch when:

1. The pull request is still open.
2. The branch has unmerged work.
3. The branch is shared and still needed.
4. The branch contains work that may need to be recovered.
5. The user has not approved deletion where approval is required.

## 12. Pulling before branching or cleanup

Ask the user whether to pull latest changes before branching when working in a local clone.

Pull options:

```text
Pull options:

1. Recommended when safe: pull latest using fast forward only.
2. Do not pull yet.
3. Prepare the pull command only.
4. Stop and let the user pull manually.
5. Custom: provide another sync instruction.
```

Prefer `git pull --ff-only` after checking branch and working tree state.

If fast forward pull is not possible, stop and explain options before merge, rebase, stash, reset, or conflict resolution.

Never pull over uncommitted user work.

Never use hard reset, force push, rebase, or stash unless the user explicitly approves the exact action.

## 13. Local preview standard

For website repositories, always start or reuse the local preview server before planning implementation or editing files where preview support exists.

The required default local HTTP preview URL is:

```text
http://127.0.0.1:8080/
```

Agents must open the website at `http://127.0.0.1:8080/` in the VS Code browser preview, VS Code Simple Browser, Codex browser, or the available local preview tool before planning implementation or editing files where supported.

Agents must also confirm direct `file:///` access for browser served static pages and public assets. A typical direct file check is:

```text
file:///absolute/path/to/public/index.html
```

Use `http://127.0.0.1:8080/` as the primary website preview and validation route. Use `file:///` as an additional compatibility check for static HTML, CSS, JavaScript, images, downloads, and other public assets.

Do not use `file:///` as a replacement for the local HTTP preview. If a page depends on PHP, server routing, Apache rewrite rules, MySQL, Redis, authenticated sessions, email, form submission, or another server side feature and cannot fully work through `file:///`, record the limitation clearly and continue to validate the server side behaviour through `http://127.0.0.1:8080/`.

For APES CIC website repositories using `public/` as the public web root, typical local preview commands may include:

```bash
php -S 127.0.0.1:8080 -t public
```

or, where the repository includes a router:

```bash
php -S 127.0.0.1:8080 -t public dev/router.php
```

For Node based repositories, use repository documented commands first. Common examples may include:

```bash
npm install
npm run dev
npm run build
```

Use repository documented commands first. If no preview command exists, record the blocker before continuing.

Treat local preview as inspection and validation only. It is not deployment evidence.

Before final handoff, inspect changed public pages again through `http://127.0.0.1:8080/` where preview support exists, and repeat the `file:///` check for affected static pages and public assets where applicable.

## 14. Implementation approval gate

Do not implement until the user approves the plan or explicitly says to start implementation.

Approved implementation prompt shape:

```text
The plan is approved with the following constraints:

1. Stay on the current task branch.
2. Do not edit main directly.
3. Do not commit private configuration, private data, generated credential files, local database dumps, or secrets.
4. Only change files required for this issue.
5. Keep the project compatible with the approved website type, approved hosting target, and Cloudron LAMP limitations where relevant.
6. Start or reuse the local preview server before implementation work continues, open http://127.0.0.1:8080/ in the VS Code or Codex local browser view where supported, and record the preview URL or blocker.
7. Check file:/// access for affected browser served static pages and public assets where applicable, and record any justified blocker.
8. Do not install tools, dependencies, packages, extensions, formatters, linters, or CLIs without approval.
9. Do not change workspace, editor, task, launch, or extension settings without approval.
10. Provide issue updates at meaningful milestones if this task is linked to an issue.
11. After implementation, provide a summary, changed files list, validation results, risks, release record status, GitHub status, and follow up tasks.

Start implementation now.
```

During implementation, make changes on the task branch only.

If scope changes are required, stop and ask for approval before proceeding.

If the agent becomes blocked, confused, or uncertain, pause and provide:

```text
Pause and summarise:

1. What files have changed so far?
2. What remains to be done?
3. Are there any errors, blockers, or uncertain assumptions?
4. Is the work still inside the issue scope?
5. Is the selected website type and hosting target still being followed?
6. What options does the user have next?
```

## 15. Diff review before commit

Before any commit, inspect every changed file and ask for review unless the user has clearly authorised automated commits.

Required pre commit summary:

```text
Before committing, here is the review summary:

1. File by file summary of all changes.
2. Files created.
3. Files deleted.
4. Configuration changes.
5. Website type and hosting impact.
6. Possible security concerns.
7. UK GDPR concerns where relevant.
8. Validation completed.
9. Manual checks recommended.
10. Confirmation that no private configuration, private data, local database dump, generated credential file, or secret is included.
11. Proposed commit message.

Commit options:

1. Recommended if the diff is acceptable: commit these changes.
2. Make more changes before committing.
3. Do not commit yet.
4. Custom: provide a different commit instruction.
```

The agent must check:

1. Only expected files changed.
2. Nothing important was deleted.
3. No private configuration or private data is included.
4. Formatting is not broken.
5. Routes and key pages load where validation is possible.
6. Login or portal areas still work where in scope.
7. Privacy, terms, cookies, donation, and Change Log Hub links work where relevant.
8. Local only files are not committed.
9. The selected website type is still followed.
10. Cloudron LAMP assumptions remain valid where applicable.
11. No generated credential files, local database dumps, or temporary debugging files are included.
12. The website has been checked through `http://127.0.0.1:8080/` in the VS Code or Codex local browser view where supported.
13. Direct `file:///` access has been checked for affected browser served static pages and public assets where applicable, or a justified blocker has been recorded.

## 16. Commit rules

Commit only after the diff has been reviewed and relevant validation has been run or intentionally deferred.

Commit message format:

```text
type: short summary
```

Examples:

```text
feature: add portal login structure
fix: correct portal rewrite routing
docs: update agent guidance
cleanup: remove unused framework files
hosting: document Cloudron LAMP limits
```

When preparing a commit message, use this shape:

```text
type: short summary

Body:
1. What changed
2. Why it changed
3. Related issue number or issue deferral reason
4. Website type or hosting impact where relevant
```

Do not commit if:

1. The branch is wrong.
2. The diff contains unrelated changes.
3. Validation failed and the user has not approved deferral.
4. Private configuration or private data is present.
5. The selected website type is not followed.
6. The user has not approved the commit where approval is required.
7. The work was done on `main`, `master`, `production`, `staging`, a release branch, or a shared long lived branch.

## 17. Pull request workflow

Open a pull request from the task branch into the approved base branch, normally `main`, when repository changes are ready for review.

Ask about pull requests after the branch has been published, the diff has been reviewed, and validation has run or been intentionally deferred.

Pull request options:

```text
Pull request options:

1. Recommended when validation is incomplete or review is needed: open a draft PR.
2. Open a ready for review PR.
3. Prepare PR title and body only.
4. Do not open a PR yet.
5. Custom: provide another PR instruction.
```

Open a pull request when:

1. Repository files have changed.
2. The work should be reviewed before merge.
3. CI or GitHub checks should run.
4. The task is linked to an issue.
5. The branch needs to be visible for collaboration or handover.
6. The user has approved opening a pull request or the agreed workflow requires it.

Use a draft PR when:

1. Implementation is not fully ready.
2. Validation is incomplete.
3. Review is useful before final checks.
4. The work is still in progress but should be visible.

Use a ready for review PR when:

1. Validation is complete or any validation deferral has been intentionally recorded.
2. The diff has been reviewed.
3. The branch contains only intended changes.
4. The PR body explains the issue, scope, files changed, testing, risks, and deployment notes.

Do not open a pull request when:

1. No repository files changed.
2. The user only wants issue text, planning, or review comments.
3. The branch has not been published.
4. The diff has not been reviewed.
5. Validation has not run or been deferred.
6. Private configuration, private data, or unrelated changes are present.
7. The user explicitly says no PR yet.

Pull request body template:

```text
## Summary
Explain what changed.

## Related issue
Closes #

## Type of change
Feature / Bug fix / Clean up / Migration / Documentation / Refactor / Security / Compliance / Hosting / Hotfix / Custom

## Website type
Static website / PHP website / MySQL database website / CMS / Custom portal / Small web application / Migration / Merge / Documentation only / Custom

## Files changed
1.
2.
3.

## Testing completed
1.
2.
3.

## Screenshots
Add screenshots if relevant.

## Risks
List any known risks or say none known.

## Deployment notes
Add any required environment, database, cache, Cloudron, rewrite, LDAP, or email notes.

## Follow up
List follow up tasks or say none.
```

Use `Closes #123`, `Fixes #123`, or `Resolves #123` only when the pull request should close the issue after merge.

## 18. Pull request review rules

Before merge, review the pull request against the issue, approved scope, selected website type, and approved hosting target.

Universal pull request review prompt:

```text
Review this pull request before merge.

Check:

1. Does the pull request fully satisfy the issue?
2. Are there any unrelated changes?
3. Is the selected website type followed?
4. Are there any security risks?
5. Is private configuration protected?
6. Are there any UK GDPR issues?
7. Are there any broken routes, links, forms, redirects, or login issues?
8. Are there any Cloudron LAMP compatibility problems?
9. Are README, changelog, Change Log Hub, version records, generated output, and issue updates aligned?
10. Are SEO, sitemap, footer, Newsroom, and error page requirements satisfied where relevant?
11. Was http://127.0.0.1:8080/ opened in the VS Code or Codex local browser view where supported?
12. Was file:/// access checked for browser served static pages and public assets where applicable, or was a justified blocker recorded?
13. Are there any missing tests or manual checks?
14. Is the pull request safe to merge?

Give me:
1. Pass or fail.
2. Required fixes before merge.
3. Recommended improvements.
4. Final merge recommendation.
5. Clear next step options.
```

If fixes are required, keep using the same branch and commit fixes to that branch after approval where required.

## 19. Validation before merge

Run repository supported checks first. Inspect `package.json`, lockfiles, build scripts, framework configuration, Makefiles, CI files, Composer files, PHP files, dev scripts, and local documentation before choosing commands.

For Node based APES CIC website repositories, common commands may include:

```bash
npm install
npm run lint
npm run typecheck
npm run test:e2e
npm run build
```

For PHP or Cloudron LAMP compatible repositories, common checks may include:

```bash
php -l path/to/file.php
php -S 127.0.0.1:8080 -t public
php -S 127.0.0.1:8080 -t public dev/router.php
```

Minimum manual checks where applicable:

1. Site loads through `http://127.0.0.1:8080/`.
2. The website is opened in the VS Code browser preview, VS Code Simple Browser, Codex browser, or available local preview tool where supported.
3. Direct `file:///` access works for browser served static pages and public assets where applicable, or a justified server required blocker is recorded.
4. Home page, portal, and key pages load.
5. Registration, login, contact, and other forms work.
6. MySQL queries work where database features are in scope.
7. Email sending still works where email is in scope.
8. Redis cache or sessions work where Redis is included.
9. Rewrites work for public front end and portal routing.
10. Privacy, terms, cookies, donation, and Change Log Hub links work where relevant.
11. Private configuration is protected.
12. Mobile view is usable.
13. Public folder and local preview rules are satisfied.
14. SEO, sitemap, footer, Newsroom, and error page checks are completed where relevant.
15. The selected website type and hosting target are followed.
16. No stack trace, debug dump, private URL, credential, or environment value is exposed.

For documentation only repositories, validation may be limited to Markdown review, link review where links exist, scope review, and secret scanning by inspection.

Treat development server commands such as `npm run dev` or `php -S` as local inspection and validation tools, not deployment evidence.

## 20. Merge workflow

Merge only when:

1. The pull request satisfies the issue or recorded scope.
2. Checks pass or deferrals are approved and recorded.
3. The diff has been reviewed.
4. There are no unresolved merge conflicts.
5. Private configuration and private data are protected.
6. The site works locally or in preview where applicable.
7. README, changelog, version, Change Log Hub, generated output, and issue updates are aligned or deferral is approved.
8. Website type and hosting impact are recorded where relevant.
9. The user explicitly approves merge.

Ask about merge only after the pull request has been reviewed and is ready for a merge decision.

Merge options:

```text
Merge options:

1. Recommended for most APES CIC updates: squash merge.
2. Merge commit, if preserving individual commits is important.
3. Rebase merge, if the repository uses a linear history and this is approved.
4. Do not merge yet.
5. Custom: provide another merge instruction.
```

Use squash merge when:

1. The repository allows it.
2. The change is a small or medium task branch.
3. A tidy `main` history is preferred.
4. Individual commit history does not need preserving.

Use merge commit when:

1. The branch history should be preserved.
2. The PR contains meaningful separate commits.
3. The repository policy prefers merge commits.

Use rebase merge only when:

1. The repository policy supports it.
2. The branch is clean and safe to rebase.
3. The user explicitly approves it.

Do not merge when:

1. The user has not approved merge.
2. The pull request has unresolved review comments.
3. Checks are failing and deferral is not approved.
4. There are merge conflicts.
5. The diff includes unrelated changes.
6. Private configuration or private data is present.
7. Required issue, version, changelog, README, local preview, or release records are missing without approved deferral.

Do not enable auto merge unless the user explicitly asks.

## 21. Branch cleanup after merge

After merge, ask before deleting branches unless repository settings automatically delete head branches.

Remote branch cleanup:

1. Delete the remote task branch only after the pull request is merged or closed and the user approves deletion.
2. Do not delete a branch linked to an open pull request unless the user explicitly confirms they want to abandon that pull request.
3. If GitHub reports the branch is not merged, stop and explain the risk.

Recommended repository setting:

```text
Settings -> General -> Pull Requests -> Automatically delete head branches
```

Local cleanup after merge:

```bash
git checkout main
git pull origin main
git branch -d feature/myapes-portal-login
```

If Git says the branch is not fully merged, stop and check before forcing deletion.

## 22. Issue closure after merge

If the pull request body used `Closes #123`, `Fixes #123`, or `Resolves #123`, confirm whether GitHub closed the issue automatically after merge.

If the issue is not automatically closed and the work is complete, ask before closing it manually.

Issue closure options:

```text
Issue closure options:

1. Recommended when all work is complete: close the issue with a completion note.
2. Keep the issue open for follow up work.
3. Add a status update but do not close it.
4. Do nothing to the issue.
5. Custom: provide another issue closure instruction.
```

Closing comment shape:

```text
Completed in PR #456.

Summary:
1.
2.
3.

Validation:
1.
2.
3.

Branch and release status:
1.
2.
3.
```

Do not close an issue if validation, release records, hosting notes, local preview, or follow up work remain unresolved unless the user approves closure with limitations recorded.

## 23. Full workflow checklist

Agents must track this checklist during GitHub tasks:

```text
1. Confirm request and repository.
2. Ask clear grouped questions only when needed.
3. Decide whether to create, update, use, or defer an issue.
4. Add scope and acceptance criteria when using an issue.
5. Confirm website type and hosting target.
6. Confirm repository and current branch.
7. Confirm working tree state.
8. Decide branch creation.
9. Create branch from main or approved base branch.
10. Start or reuse local preview server before planning implementation or editing files.
11. Open http://127.0.0.1:8080/ in the VS Code or Codex local browser view where supported.
12. Check file:/// access for browser served static pages and public assets where applicable, or record a justified blocker.
13. Produce planning response.
14. Wait for user approval.
15. Implement on the task branch only.
16. Provide issue updates at meaningful milestones where an issue is used.
17. Review file changes and diff.
18. Run validation.
19. Ask for commit approval.
20. Commit locally after approval.
21. Ask whether to publish branch.
22. Push or publish branch after approval.
23. Ask whether to open a pull request.
24. Open pull request after approval.
25. Link pull request to issue where applicable.
26. Run checks and manual testing.
27. Fix review issues on the same branch.
28. Ask for merge decision only when ready.
29. Merge pull request only after approval.
30. Ask for branch cleanup decision after merge.
31. Delete remote branch after merge where approved.
32. Ask for pull or sync decision where local cleanup is relevant.
33. Pull latest main locally where approved.
34. Delete local branch where safe and approved.
35. Confirm issue is closed or update it with remaining work.
36. Provide final completion summary.
```

## 24. Repository documentation, version, and release records

During planning for repository work, verify the repository documentation, version, and release state where files exist:

1. `README.md`.
2. Root `VERSION`.
3. `/public/VERSION`.
4. Root `CHANGELOG.md`.
5. `/public/CHANGELOG.md`.
6. Website Change Log Hub source page or data.
7. Release metadata files or generated output that render footer version text, public release cards, or static snapshots.
8. Hosting and local preview notes.
9. Repository specific documentation, generated output, package metadata, manifest, sitemap, index, configuration, or operational notes affected by the change.

Record the verified release state in the plan, issue update, or working notes:

1. Current version.
2. Beta or stable status.
3. Expected version bump type.
4. Required README updates.
5. Required changelog updates.
6. Required Change Log Hub updates.
7. Required generated output updates.
8. Hosting assumptions.
9. Local preview assumptions.
10. Selected website type.
11. Any mismatch or deferral.

Use APES CIC semantic website versioning:

```text
vMAJOR.MINOR.PATCH
vMAJOR.MINOR.PATCHb
```

Use the `b` suffix while a site is in beta. Stable versions omit it.

Major changes are breaking route, architecture, public journey, data, compliance, platform, hosting, or operational changes.

Minor changes add user visible features, public routes, broad content expansions, shared components, or meaningful workflow additions.

Patch changes are fixes, copy edits, styling polish, metadata corrections, dependency or configuration fixes, hosting clarifications, local preview clarifications, generated output syncs, and small maintenance changes.

Do not update a version without a matching changelog decision and entry.

## 25. APES website public folder and website type rules

For applicable APES CIC website repositories, `public/` is the website folder and public web root.

When creating APES CIC websites, set them up as HTML websites by default unless the user selects another website type. Use `public/index.html`, static HTML, CSS, browser JavaScript, images, downloads, and other public assets served from `public/`, with PHP available where it supports the HTML site through local preview routing, form handling, reusable includes, lightweight server side helpers, search or sitemap helpers, maintenance scripts, or documented support tools.

Do not make PHP the primary website model unless the repository, issue, selected website type, or user explicitly requires a PHP backed feature.

Do not create WordPress style, CMS backed, custom database backed, or persistent runtime applications by default.

All browser served website files must live inside `public/`.

Do not use `content/` as the public web root. Do not scatter website files across the repository root. Do not create nested public folders such as `public/public/`.

For custom portal projects, prefer this structure when approved:

```text
/public
/public/portal
```

The public front end can sit in `/public`, and the portal can sit in `/public/portal`. Apache rewrite rules may serve the portal from a subdomain such as `portal.apes.org.uk` or another approved portal domain.

## 26. Website type rules

When creating, merging, migrating, or refactoring a website, agents must confirm the selected website type and ensure the repository structure, language stack, routes, validation, documentation, and hosting notes comply with that setup.

Agents must not assume that every APES CIC website should be Laravel, WordPress, Node.js, or a full application. Match the implementation to the approved website type.

### Static websites

Good for simple public websites with pages such as:

1. Home.
2. About.
3. Contact.
4. Services.
5. Policies.
6. Terms.
7. Privacy.
8. Donation information.
9. Basic landing pages.

Languages used:

1. HTML.
2. CSS.
3. JavaScript.

This works well for brochure websites, public information websites, campaign pages, simple front ends, and content based public websites.

### PHP websites

Good for custom websites that need server side logic, such as:

1. Contact forms.
2. Login systems.
3. User dashboards.
4. Staff portals.
5. Public registration forms.
6. Admin panels.
7. Resource libraries.
8. Booking request forms.
9. Case tracking portals.
10. Internal tools.

Languages used:

1. PHP.
2. HTML.
3. CSS.
4. JavaScript.
5. SQL for MySQL queries.

This is the main intended use of the Cloudron LAMP app.

### MySQL database websites

The LAMP app includes MySQL access. Cloudron gives each app its own non root MySQL credentials, while the MySQL server itself is shared across Cloudron apps. MySQL credentials are exposed as environment variables and may rotate, so production code must use `getenv()` rather than hard coded credential values.

Good for:

1. User accounts.
2. Registrations.
3. Enquiries.
4. Form submissions.
5. Membership systems.
6. Ticket systems.
7. Basic CRM style tools.
8. Client portals.
9. Content management systems.
10. Animal records only where the approved project includes the required access control and data protection design.

Languages used:

1. PHP.
2. SQL.
3. JavaScript for front end interaction.

### Content management systems

Cloudron LAMP can host PHP based CMS platforms where their server requirements match the LAMP environment.

Examples include:

1. WordPress.
2. Joomla.
3. Drupal.
4. Grav.
5. ClassicPress.
6. ProcessWire.
7. October CMS.
8. Custom PHP CMS systems.

Cloudron has dedicated apps for some platforms. For something like WordPress, the dedicated Cloudron WordPress app may be easier and safer than the generic LAMP app.

### Custom portals

A Cloudron LAMP container is suitable for a custom portal such as:

1. Public front end website.
2. Login area.
3. Client portal.
4. Student portal.
5. Volunteer portal.
6. Staff tools area.
7. Admin dashboard.

For APES CIC style portal projects, this structure is preferred unless repository evidence or user instructions require another structure:

```text
/public
/public/portal
```

The public site can sit in `/public`, and the portal can sit in `/public/portal`, with Apache rewrite rules used to serve a subdomain such as `portal.apes.org.uk` or another approved portal domain.

For MyAPES Portal style projects, the usual LAMP compatible pattern is:

1. Public front end in HTML, CSS, and JavaScript.
2. Portal area in PHP.
3. MySQL for public registrations.
4. Cloudron LDAP for staff, volunteer, and student login where LDAP is available and approved.
5. Redis for cache, sessions, or rate limiting.
6. Cloudron email variables for sending messages.
7. Apache rewrite rules for clean URLs and subdomains.

### Small web applications

Good examples:

1. Appointment request system.
2. Public registration system.
3. Volunteer application form.
4. Donation tracking interface.
5. Simple support ticket tool.
6. Knowledgebase.
7. Document request portal.
8. Contact centre forms.
9. Internal reporting tool.

For heavier applications, a dedicated app container or custom Cloudron packaged app may be better.

## 27. Cloudron LAMP compatibility rules

A Cloudron LAMP container is suitable for websites that run on:

```text
Linux
Apache
MySQL
PHP
```

Cloudron LAMP works like traditional shared hosting. Website files are served by Apache and PHP. Cloudron also handles DNS, SSL certificates, MySQL, Redis, email integration, backups, restore, updates, and app isolation for the LAMP app.

For APES CIC style projects, the safest Cloudron LAMP stack is:

```text
HTML
CSS
JavaScript
PHP 8.3 or PHP 8.4
MySQL
Redis
Apache .htaccess
```

Use PHP 8.3 or PHP 8.4 unless repository evidence or user approval requires another supported version.

Languages and formats suitable inside a LAMP container:

| Area | Languages and formats |
| --- | --- |
| Front end structure | HTML |
| Styling | CSS |
| Browser interaction | JavaScript |
| Server side logic | PHP |
| Database queries | SQL |
| Apache configuration | `.htaccess` rules |
| Configuration | `.ini`, `.env`, JSON |

Cloudron LAMP is a good fit for:

1. HTML, CSS, JavaScript, and PHP websites.
2. Custom PHP portals.
3. MySQL backed public registration systems.
4. Simple dashboards.
5. Contact forms and enquiry handling.
6. CMS websites where the server requirements fit.
7. Internal admin tools.
8. Small to medium web applications.
9. Websites needing SMTP email sending.
10. Websites needing Redis caching or sessions.

Cloudron LAMP is less suitable for:

1. Large Node.js applications.
2. Python Django or Flask applications.
3. Ruby, Go, Rust, Java, or .NET live server applications.
4. WebSocket heavy systems.
5. Heavy background workers.
6. Microservice architecture.
7. Large API platforms.
8. Applications needing root level server control.
9. High traffic transactional systems requiring horizontal scaling.
10. Sensitive case management, safeguarding, HR, finance, client record, or animal welfare record systems unless access control, audit logging, retention, backup, incident response, and UK GDPR controls are explicitly designed and approved.
11. Payment heavy systems, where hosted Stripe or payment provider flows are usually safer.

Tools such as Node.js may still be used locally for building CSS or JavaScript assets, but the deployed Cloudron LAMP app should normally serve the final built files through Apache and PHP.

For apps written in Node.js, Python, Ruby, Go, Rust, Java, or similar, the better route is usually a custom Cloudron app package. Cloudron apps are packaged as Docker images and can use platform addons where declared in `CloudronManifest.json`.

Required Cloudron LAMP checks before completion:

1. Browser served files live under `public/`.
2. The entry point is `public/index.html`, or `public/index.php` is justified by a documented PHP feature.
3. The selected website type is recorded.
4. `.htaccess` is present and Apache compatible where required.
5. Clean URLs work through Apache fallback rules or PHP routing.
6. No route requires a persistent Node, Python, Ruby, Go, Java, WebSocket, worker, scheduler, or daemon process unless approved.
7. Database use is PHP compatible and documented.
8. Cloudron environment variables are used for MySQL and email rather than hard coded credentials.
9. Forms, email, uploads, and third party integrations protect private configuration.
10. UK GDPR, consent, retention, security, and audit implications are noted for forms or stored personal data.
11. README, changelog, Change Log Hub, version records, generated output, and issue updates reflect the hosting impact.
12. Local preview remains aligned with Cloudron LAMP assumptions.
13. `http://127.0.0.1:8080/` has been used as the primary local HTTP preview where supported.
14. Direct `file:///` access has been checked for browser served static pages and public assets where applicable, or a justified server required blocker has been recorded.

## 28. Security, safety, and UK GDPR rules

Do not edit private production configuration or unrelated environment files.

Do not deploy automatically.

Do not commit, push, open pull requests, merge, close issues, rewrite history, force push, reset, rebase, stash, or delete branches unless the user explicitly asks or clearly approves that action.

Before any state changing GitHub or Git action, show the relevant diff, validation result, risk, options, and recommended next step where practical.

Never overwrite local user work. If uncommitted or unrelated changes exist, stop and explain the safest options.

Do not add temporary tools, dependencies, deployment scripts, extension recommendations, or workspace settings unless the user approves.

Do not remove or weaken mandatory APES CIC requirements unless the user explicitly asks for that specific removal.

Do not store secrets, database passwords, SMTP passwords, OAuth secrets, API keys, private keys, generated credential files, local database dumps, or private configuration in the repository.

For UK GDPR relevant work, identify and record where applicable:

1. Personal data being collected or processed.
2. Lawful basis.
3. Data minimisation.
4. Consent requirements.
5. Privacy notice impact.
6. Retention period.
7. Access control.
8. Audit logging.
9. Deletion and correction rights.
10. Incident response.
11. Security measures.
12. Processor or third party sharing implications.

Sensitive case management, safeguarding, HR, finance, client record, or animal welfare record systems require explicit access control, audit logging, retention, backup, incident response, and UK GDPR design before implementation.

## 29. SEO, sitemap, footer, Newsroom, and error pages

Update SEO and sitemap records whenever public pages are added, removed, renamed, moved, or materially changed.

Check:

1. Page titles.
2. Meta descriptions.
3. Canonical URLs.
4. Open Graph metadata.
5. Structured data.
6. Robots rules.
7. Noindex rules.
8. Navigation.
9. Footer links.
10. Change Log Hub links.
11. Canonical URL consistency.
12. Sitemap entries.

Confirm the APES universal footer remains present, accurate, accessible, and consistent.

Confirm donation, Privacy Policy, Terms of Service, and Change Log Hub footer links where relevant.

Confirm APES Newsroom routing for news, updates, announcements, newsletters, footer, navigation, and article related changes.

Verify branded, accessible 404 pages and any supported 403, 500, offline, maintenance, or fallback pages.

Error pages must not expose stack traces, internal system details, private URLs, credentials, environment values, or debugging output.

## 30. Final response requirements

For every APES CIC website or repository task, the final response must state:

1. Progress summary.
2. What changed.
3. Files changed, with each path and a short explanation.
4. Checks run.
5. README, Change Log Hub, root changelog, public changelog, version record, and generated output status.
6. Public folder and local preview status, including whether `http://127.0.0.1:8080/` was started or reused where applicable, whether it was opened in the VS Code or Codex local browser view where supported, and whether `file:///` access was checked for affected browser served static pages and public assets.
7. Website type status.
8. Cloudron LAMP and hosting status where relevant.
9. GitHub issue status, including whether an issue was created, updated, linked, deferred, or not used.
10. SEO, sitemap, footer, Newsroom, and error page status where relevant.
11. Branch name, base branch, commit status, push status, and pull request status.
12. Pull or sync status, including whether the task branch is up to date with the base branch.
13. Clear next step options for pulling, publishing, pull request, merge, branch cleanup, or issue closure where relevant.
14. Next recommended GitHub step.
15. Proposed commit message where no commit has been made yet.

Keep final responses concise, practical, and transparent about outstanding user decisions.

## 31. Best universal starter prompt for new tasks

Use this starter prompt when the user begins a new GitHub task and has not already provided the needed details:

```text
I want to start a new GitHub task using the correct issue, branch, pull request, and cleanup workflow.

Before doing any implementation, ask me only the decisions needed at this stage and give numbered options with a recommended option and a custom option.

Ask me:

1. What type of update is this?
Feature, bug fix, clean up, migration, refactor, documentation, security, compliance, hosting, urgent hotfix, or custom.

2. What type of website setup should this use?
Static website, PHP website, MySQL database website, CMS, custom portal, small web application, migration, merge, documentation only, or custom.

3. What should happen with GitHub issue tracking?
Option 1: Recommended for non trivial work, create a new GitHub issue.
Option 2: Use an existing issue and ask for the issue number.
Option 3: Update an existing issue and ask for the issue number.
Option 4: Do not record an issue and record the reason.
Option 5: Custom issue option.

4. What branch should be used?
Option 1: Recommended for repository changes, create a new task branch from main.
Option 2: Create a new task branch from another approved base branch.
Option 3: Use an existing task branch after confirming it is safe.
Option 4: No branch because this is planning, review, or text only.
Option 5: Custom branch option.

5. What is the base branch?
Default to main unless I say otherwise.

6. What files or areas are in scope?

7. What files or areas are out of scope?

8. Are there any environment variables, Cloudron settings, MySQL settings, Redis settings, LDAP settings, email settings, rewrite rules, private configuration, Windows settings, VS Code settings, local development settings, or deployment settings that must be considered?

9. What local preview server command should be used before planning implementation or editing files?
Default to http://127.0.0.1:8080/ where the repository supports website preview, then open the website in the VS Code or Codex local browser view where supported.

10. Should direct file:/// access be required for browser served static pages and public assets?
Default to yes for static HTML, CSS, JavaScript, images, downloads, and public assets. Record a justified blocker where server side routing, PHP, database, sessions, forms, email, Redis, or rewrite rules prevent full file:/// behaviour.

11. What validation should be completed before pull request?

After I answer, produce a plan only.

Start or reuse the local preview server before planning implementation or editing files where preview support exists.
Open http://127.0.0.1:8080/ in the VS Code or Codex local browser view where supported.
Check file:/// access for browser served static pages and public assets where applicable, or record a justified blocker.
Do not edit files until I approve the plan.
Do not work directly on main.
Do not commit private configuration or generated credential files.
Provide issue updates at meaningful milestones where an issue is used.
After implementation, prepare a commit summary and pull request description.
```

## 32. Universal planning prompt for agents

Use this prompt inside Codex, VS Code, GitHub Copilot Chat, or similar tools when asking an agent to plan work:

```text
You are working in this APES CIC GitHub repository.

Before editing files, do the following:

1. Confirm the current repository and branch.
2. Confirm whether this task will create a new issue, update an existing issue, use an existing issue, or record no issue with a reason.
3. Check the working tree for uncommitted changes.
4. Read README.md, AGENTS.md, INSTRUCTIONS.md, package files, Composer files, configuration files, build files, and relevant source files.
5. Identify the website stack and limitations.
6. Identify the website type: static website, PHP website, MySQL database website, CMS, custom portal, small web application, migration, merge, documentation only, or custom.
7. Confirm whether this is a feature, bug fix, clean up, migration, refactor, documentation, security, compliance, hosting, hotfix, or custom update.
8. Confirm whether the target hosting is Cloudron LAMP or another platform.
9. Start or reuse the local preview server for website repositories before planning implementation or editing files where preview support exists. Use http://127.0.0.1:8080/ unless repository instructions require another local HTTP URL. Open the website in the VS Code browser preview, VS Code Simple Browser, Codex browser, or available local preview tool where supported. If the server cannot be started, stop and record the blocker.
10. Check direct file:/// access for browser served static pages and public assets where applicable. If server side routing, PHP, database features, sessions, forms, email, Redis, rewrite rules, or browser security restrictions prevent full file:/// behaviour, record the justified blocker.
11. Produce an implementation plan.
12. List the files you expect to change.
13. List any risks.
14. List validation steps.
15. List when issue updates will be provided if an issue is used.
16. Do not edit files until the plan is approved.

Answer with:

Issue decision:
Branch decision:
Update type:
Website type:
Hosting target:
Local preview server:
Environment notes:
Files likely to change:
Implementation plan:
Work update plan:
Risks:
Validation:
Questions before implementation:
```

## 33. Universal implementation approval prompt

Use this prompt when approving an agent to move from planning into implementation:

```text
The plan is approved with the following constraints:

1. Stay on the current task branch.
2. Do not edit main directly.
3. Do not commit private configuration, private data, generated credential files, local database dumps, or secrets.
4. Only change files required for this issue or recorded scope.
5. Keep the project compatible with the approved website type, approved hosting target, and Cloudron LAMP limitations where relevant.
6. Start or reuse the local preview server before implementation work continues, open http://127.0.0.1:8080/ in the VS Code or Codex local browser view where supported, and record the preview URL or blocker.
7. Check file:/// access for affected browser served static pages and public assets where applicable, and record any justified blocker.
8. Do not install tools, dependencies, packages, extensions, formatters, linters, or CLIs without approval.
9. Do not change workspace, editor, task, launch, or extension settings without approval.
10. Provide issue updates at meaningful milestones where an issue is used.
11. After implementation, provide a summary, changed files list, validation results, risks, release record status, GitHub status, and clear next step options.

Start implementation now.
```

## 34. Universal pre commit review prompt

Use this prompt before any commit:

```text
Before committing, provide:

1. File by file summary of all changes.
2. Files created.
3. Files deleted.
4. Configuration changes.
5. Website type and hosting impact.
6. Security concerns.
7. UK GDPR concerns where relevant.
8. Validation completed, including http://127.0.0.1:8080/ local browser view status where supported.
9. file:/// static page and public asset access status where applicable, or justified blocker.
10. Issue update status where an issue is used.
11. Manual checks recommended.
12. Confirmation that no private configuration, private data, local database dump, generated credential file, or secret is included.
13. Proposed commit message.

Commit options:

1. Recommended if the diff is correct: commit these changes.
2. Make more edits first.
3. Do not commit yet.
4. Custom: provide another commit instruction.
```

## 35. Universal pull request review prompt

Use this prompt before merge:

```text
Review this pull request before merge.

Check:

1. Does the pull request fully satisfy the issue or recorded scope?
2. Are there any unrelated changes?
3. Is the selected website type followed?
4. Are there any security risks?
5. Is private configuration protected?
6. Are there any UK GDPR issues?
7. Are there any broken routes, links, forms, redirects, or login issues?
8. Are there any Cloudron LAMP compatibility problems?
9. Are README, changelog, Change Log Hub, version records, generated output, and issue updates aligned?
10. Are SEO, sitemap, footer, Newsroom, and error page requirements satisfied where relevant?
11. Was http://127.0.0.1:8080/ opened in the VS Code or Codex local browser view where supported?
12. Was file:/// access checked for browser served static pages and public assets where applicable, or was a justified blocker recorded?
13. Are there any missing tests or manual checks?
14. Is the pull request safe to merge?

Give me:
1. Pass or fail.
2. Required fixes before merge.
3. Recommended improvements.
4. Final merge recommendation.
5. Merge options with a recommended option and a custom option.
```

## 36. Reference topics for agents

When repository guidance is unclear, agents may use official documentation for clarification, but repository instructions and user approved scope take priority.

Useful reference topics:

1. Creating GitHub issues.
2. Updating GitHub issues during work.
3. Creating and deleting branches.
4. Publishing branches.
5. Managing branches in VS Code and GitHub Desktop.
6. Creating, linking, reviewing, and merging pull requests.
7. Merge commit, squash merge, and rebase merge options.
8. Using closing keywords such as `Closes`, `Fixes`, and `Resolves`.
9. Managing protected branch rules.
10. OpenAI Codex and GitHub connection guidance.
11. GitHub Copilot Chat repository workflows.
12. Cloudron LAMP package documentation.
13. Cloudron custom app packaging documentation.
14. PHP local development with the built in server.
15. UK GDPR and privacy notice requirements.
