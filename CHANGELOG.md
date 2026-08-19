# Change Log Hub

Track every major release for this website, including updates, fixes, compliance changes, and user-facing improvements.

## [v4.0.1b] - 2026-08-19

<span class="pill pill-version">Version v4.0.1b</span>
<span class="pill pill-status">Beta</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-type">Fixed</span>

### Summary

Updated public contact details to the Morris Street address and 01744 374 015, and reduced crawler use of the public email by removing it from JSON-LD and marking mailto links as nofollow.

### Detailed changes

- Replaced the public CIC telephone number `0300 302 0998` with `01744 374 015` in shared PHP contact helpers, contact copy, leftover public HTML snapshots, and README organisation details.
- Updated the public postal address and registered office to `40 Morris Street, St Helens, Merseyside, WA9 3EN`.
- Kept `info@apes.org.uk` visible and clickable for visitors, added `rel="nofollow"` to those mailto links, and removed the Organisation schema email field so crawlers are not given a structured harvest target.
- Left `legal@apes.org.uk`, Shelter and Clinic 0300 lines, and the veterinary emergency number unchanged.
- Synchronised version files, README, root changelog, public changelog mirror and Change Log Hub release metadata.

### Affected areas

- Website: www.apes.org.uk
- Page or route: all public routes via shared header, sidebar and footer; `/contact/`, `/contact-centre/`, `/policies/privacy/`, `/policies/terms-of-service/`, Change Log Hub and leftover public HTML snapshots
- Files changed: shared PHP contact constants and render helpers, contact and policy copy, public HTML snapshots, VERSION files, README, root CHANGELOG, public CHANGELOG and Change Log Hub source
- User groups affected: public visitors, supporters, staff and partners using published contact details
- Public impact: visitors now see the current Morris Street address and 01744 number; the public email remains usable but is not advertised in structured data
- Internal impact: contact details now come from shared PHP helpers, with leftover HTML snapshots aligned so Apache DirectoryIndex cannot serve the old 0300 number

### Version decision

- Previous version: v4.0.0b
- New version: v4.0.1b
- Version type: patch beta
- Reason for version bump: user-visible contact-detail correction without route, architecture or hosting changes.

### Validation

- Checks run: PHP lint on changed includes, local HTTP preview of home, contact, privacy, terms and footer, JSON-LD email absence check, mailto nofollow check, and public-site validation where PHP is available
- Manual checks completed: contact copy review, Shelter/Clinic 0300 retention review, footer-required link review and Cloudron LAMP compatibility review
- Known limitations: leftover HTML snapshots remain in `public/` because Apache DirectoryIndex prefers `index.html`; they were updated in place rather than deleted
- Rollback notes: restore the previous contact constants, schema email field, HTML snapshots and version records if the previous 0300 number or Cross House registered office must return.

## [v4.0.0b] - 2026-06-11

<span class="pill pill-version">Version v4.0.0b</span>
<span class="pill pill-status">Beta</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-type">Removed</span>
<span class="pill pill-compliance">Compliance</span>

### Summary

Restored the website to a PHP-first architecture with shared rendering, removed checked-in static route snapshots and static-only page-owned bundles, and realigned preview, validation, and release records around the shared PHP runtime.

### Detailed changes

- Re-established `public/index.php`, `public/includes/`, and `public/theme/` as the canonical source of truth for public routes, metadata, redirects, search rendering, footer links, and branded error handling.
- Removed checked-in route snapshots under `public/**/index.html`, removed obsolete generated bundles such as `public/page.css`, `public/page.js`, and the extra branded `403/404/500` CSS and JS files, and deleted static-only preview and asset-generation helpers that were no longer part of the supported workflow.
- Moved the optional static export helper out of the public web root to `scripts/export-static-site.php`, keeping static export as an optional maintainer tool that writes to `dist/static-export` instead of repopulating `public/`.
- Updated the local preview path, PHP-first validation flow, version records, Change Log Hub metadata source, README notes, Cloudron deployment notes, validation notes, rollback notes, and site inventory to match the restored PHP-first structure.

### Affected areas

- Website: www.apes.org.uk
- Page or route: whole public website, Change Log Hub, preview and validation tooling, Cloudron staging/package workflow, branded fallback error pages, and repository release records
- Files changed: shared PHP runtime, local preview and validation scripts, public web-root structure, VERSION files, root and public changelogs, README, Cloudron deployment notes, validation report, rollback notes, and route inventory documentation
- User groups affected: all public visitors plus APES maintainers previewing, validating, packaging, and reviewing the website
- Public impact: public routes, redirects, SEO records, footer-required links, and external handoff routes remain available while the website once again serves from one shared PHP source of truth
- Internal impact: maintainers now update the site through the shared PHP renderer and theme instead of maintaining duplicate checked-in route snapshots

### Version decision

- Previous version: v3.0.0b
- New version: v4.0.0b
- Version type: major beta
- Reason for version bump: architecture-level migration from static-first checked-in route snapshots back to a PHP-first shared runtime with public-structure cleanup and workflow changes

### Validation

- Checks run: PHP-first repository structure validation and static-source cleanup checks through `powershell -ExecutionPolicy Bypass -File .\scripts\validate-public-site.ps1`
- Manual checks completed: preview/runtime command review, Cloudron public-root structure review, footer-required link source review, Change Log Hub source review, and version-record consistency review
- Known limitations: this implementation environment does not currently expose a working local PHP binary, so live PHP lint and local HTTP runtime checks still need to be run in a PHP-enabled environment before deployment
- Rollback notes: restore the removed checked-in static route snapshots and static-only helpers if needed, reset the version and release records to `v3.0.0b`, and redeploy the previous approved public bundle if the PHP-first runtime must be rolled back

## [v3.0.0b] - 2026-06-11

<span class="pill pill-version">Version v3.0.0b</span>
<span class="pill pill-status">Beta</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-type">Removed</span>
<span class="pill pill-compliance">Compliance</span>

### Summary

Migrated the public website to a static-first HTML/CSS/JS architecture with page-owned assets, a browser-side search index, and no public dependency on the previous shared PHP or shared theme runtime.

### Detailed changes

- Generated dedicated `page.css` and `page.js` files for every public route and branded error page so each page now ships with its own local styling and browser behavior entrypoints.
- Replaced the public shared-theme runtime by removing `public/theme/`, `public/includes/`, `public/scripts/export-static-site.php`, the legacy compatibility shims, and the public PHP entry points.
- Added a generated static search index at `public/assets/data/search-index.json` and browser-side search rendering on `/search/` so search no longer depends on PHP rendering.
- Reworked local preview and validation around a static Node-backed preview server, preserved legacy redirects and branded error handling, and kept footer-required links, sitemap, robots, and APES Newsroom routing intact.

### Affected areas

- Website: www.apes.org.uk
- Page or route: whole public website, `/search/`, Services hub route-finder behavior, branded error pages, Change Log Hub, root and public release records, README preview notes, local preview helpers and validation tooling
- Files changed: public HTML pages, page-owned CSS and JS assets, static search index, `.htaccess`, `dev/router.php`, preview and validation scripts, VERSION files, root CHANGELOG and public CHANGELOG
- User groups affected: all public visitors plus APES maintainers editing, previewing and validating the site
- Public impact: routes, content, hubs, route finders, footer links and external service handoffs are preserved while the site no longer relies on the previous shared runtime architecture
- Internal impact: maintainers can now change individual pages directly without editing a shared theme or shared PHP rendering layer first

### Version decision

- Previous version: v2.9.7
- New version: v3.0.0b
- Version type: major beta
- Reason for version bump: architecture-level migration to a static page-owned runtime with public-file cleanup and preview/validation workflow replacement

### Validation

- Checks run: `powershell -ExecutionPolicy Bypass -File .\scripts\validate-public-site.ps1`
- Manual checks completed: representative route review, redirect review, branded 403/404 review, page-owned asset generation review, footer-required link review and version-file consistency review
- Known limitations: repository documentation and historical release notes still contain references to the previous shared PHP/theme architecture and need a wider editorial pass before the migration can be treated as stable
- Rollback notes: restore the removed public PHP/theme files, revert the page-owned asset generation, restore the previous preview/validation helpers and version records, then regenerate the public bundle from the prior architecture if needed

## [v2.9.7] - 2026-06-10

<span class="pill pill-version">Version v2.9.7</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>

### Summary

Removed the opening `/volunteer/` reminder paragraph so the page now starts directly with the approved volunteer application guidance.

### Detailed changes

- Removed the opening volunteer reminder paragraph from the shared site data so the route begins with the approved application panel instead of the unpaid-volunteer explainer.
- Extended the volunteer regression check so validation now fails if the removed introductory copy reappears in the exported volunteer snapshot.
- Kept the volunteer application route, SEO metadata, footer-required links, APES Newsroom routing, public-folder structure and Cloudron LAMP assumptions unchanged.
- Regenerated the public static snapshots and synchronised the version files, README, root changelog, public changelog mirror and Change Log Hub release metadata.

### Affected areas

- Website: www.apes.org.uk
- Page or route: `/volunteer/`, Change Log Hub, root and public release records, README current release, validation script and regenerated public HTML snapshots
- Files changed: volunteer page source content, validation script, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated public HTML snapshots
- User groups affected: prospective volunteers, student-placement applicants, supporters, staff and partners using the volunteering route
- Public impact: volunteer-page visitors now land directly on the application guidance without the extra introductory reminder paragraph
- Internal impact: the copy change remains isolated to the volunteer route while adding a lightweight guard against the removed paragraph returning

### Version decision

- Previous version: v2.9.6
- New version: v2.9.7
- Version type: patch stable
- Reason for version bump: user-visible volunteer copy removal without route, content-model or hosting changes.

### Validation

- Checks run: volunteer regression check, local PHP preview review, PHP syntax checks where available, public-site validation script, static export regeneration and release-metadata consistency review
- Manual checks completed: volunteer page source review, footer-required link review, Change Log Hub release review and Cloudron LAMP compatibility review
- Known limitations: in-app browser screenshot validation was unavailable in this session, so final visual QA relied on local preview startup, source inspection and regenerated output review
- Rollback notes: restore the removed volunteer intro paragraph in shared site data, keep the regression check aligned with the intended copy, then rerun the PHP static export to restore the previous public snapshots if needed.

## [v2.9.6] - 2026-06-10

<span class="pill pill-version">Version v2.9.6</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>

### Summary

Restored balanced inset spacing around the first `/volunteer/` content card so the opening application panel and volunteer role cards have breathing room on every side.

### Detailed changes

- Added a volunteer-only first-section layout override so the opening `/volunteer/` content card now uses a grid gap and slightly larger inset padding without changing shared card spacing rules.
- Added a lightweight regression check for the volunteer spacing rule to the repository validation flow so this route-scoped inset is harder to remove accidentally.
- Kept volunteer content, SEO metadata, footer-required links, APES Newsroom routing, public-folder structure and Cloudron LAMP assumptions unchanged.
- Regenerated the public static snapshots and synchronised the version files, README, root changelog, public changelog mirror and Change Log Hub release metadata.

### Affected areas

- Website: www.apes.org.uk
- Page or route: `/volunteer/`, Change Log Hub, root and public release records, README current release, validation script and regenerated public HTML snapshots
- Files changed: volunteer-scoped shared CSS, validation scripts, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots
- User groups affected: prospective volunteers, student-placement applicants, supporters, staff and partners using the volunteering route
- Public impact: volunteer-page visitors now see consistent inset spacing around the first information block and its role cards on desktop layouts
- Internal impact: the fix remains isolated to the volunteer route while adding a small regression guard to validation

### Version decision

- Previous version: v2.9.5
- New version: v2.9.6
- Version type: patch stable
- Reason for version bump: user-visible volunteer layout spacing correction without route, content-model or hosting changes.

### Validation

- Checks run: volunteer spacing regression check, local PHP preview review, PHP syntax checks where available, public-site validation script, static export regeneration and release-metadata consistency review
- Manual checks completed: volunteer page source/layout review, footer-required link review, Change Log Hub release review and Cloudron LAMP compatibility review
- Known limitations: in-app browser screenshot validation was unavailable in this session, so final visual QA relied on local preview startup, source inspection and regenerated output review
- Rollback notes: restore the volunteer first-section layout override, validation script, version files and release records, then rerun the PHP static export to restore the previous public snapshots if needed.

## [v2.9.5] - 2026-06-10

<span class="pill pill-version">Version v2.9.5</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>

### Summary

Tightened the `/volunteer/` page layout so the desktop sidebar no longer leaves a large empty card, the main content has a better column balance and the volunteer panels use more controlled spacing.

### Detailed changes

- Added volunteer-only desktop layout overrides so the main content column is wider and the sidebar column is slimmer without changing shared sitewide card spacing.
- Removed the volunteer sidebar wrapper's stretched card appearance so the smaller sidebar panels sit directly on the page instead of inside a tall empty container.
- Reduced padding and internal gaps for the volunteer application panel, the "Before you apply" panel and the lower split note cards to better match the screenshot feedback.
- Kept the volunteer route content, links, SEO metadata, APES Newsroom routing, footer-required links and Cloudron LAMP assumptions unchanged.
- Regenerated the public static snapshots and synchronised the version files, README, root changelog, public changelog mirror and Change Log Hub release metadata.

### Affected areas

- Website: www.apes.org.uk
- Page or route: `/volunteer/`, Change Log Hub, root and public release records, README current release and regenerated public HTML snapshots
- Files changed: volunteer-scoped shared CSS, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots
- User groups affected: prospective volunteers, student-placement applicants, supporters, staff and partners using the volunteering route
- Public impact: volunteer-page visitors now get a tighter desktop layout with less empty sidebar space and more balanced content panels
- Internal impact: the volunteer spacing correction stays isolated to that route instead of broadening the shared card system again

### Version decision

- Previous version: v2.9.4
- New version: v2.9.5
- Version type: patch stable
- Reason for version bump: user-visible volunteer-page layout correction without route, content-model or hosting changes.

### Validation

- Checks run: local PHP preview review, PHP syntax checks where available, public-site validation script, static export regeneration and release-metadata consistency review
- Manual checks completed: volunteer page source/layout review, footer-required link review, Change Log Hub release review and Cloudron LAMP compatibility review
- Known limitations: in-app browser screenshot validation was unavailable in this session, so final visual QA relied on local preview startup, source inspection and regenerated output review
- Rollback notes: restore the volunteer page layout CSS overrides, version files and release records, then rerun the PHP static export to restore the prior public snapshots if needed.

## [v2.9.4] - 2026-06-10

<span class="pill pill-version">Version v2.9.4</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>

### Summary

Expanded shared card spacing across the website so content, sidebar, sponsor, metric and footer cards feel less cramped on desktop and stacked mobile layouts.

### Detailed changes

- Increased shared grid gaps for reusable card layouts so stacked and multi-column card groups keep more consistent breathing room.
- Enlarged the base card padding and normalized internal card spacing for shared content-card patterns without changing public HTML structure or routes.
- Aligned refreshed sidebar, release and footer card spacing so heavier card surfaces no longer visually compress their content.
- Added responsive spacing tuning below the main grid-collapse breakpoints so single-column card stacks remain comfortably separated.
- Regenerated the public static snapshots so the exported site reflects the updated shared spacing and release metadata consistently.

### Affected areas

- Website: www.apes.org.uk
- Page or route: volunteer page card stacks, shared content cards, service cards, sidebar mini-panels, footer cards, Change Log Hub, root and public release records, README and generated public HTML snapshots
- Files changed: shared CSS, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots
- User groups affected: supporters, adopters, service users, volunteers, partners and general public visitors
- Public impact: shared cards now have clearer spacing and improved readability across desktop, tablet and mobile views
- Internal impact: the shared card system is more consistent, reducing the need for page-specific spacing overrides

### Version decision

- Previous version: v2.9.3
- New version: v2.9.4
- Version type: patch stable
- Reason for version bump: user-visible shared layout spacing fix without route, content-model or hosting changes.

### Validation

- Checks run: shared CSS diff review, PHP syntax checks where available, public-site validation script, static export regeneration and release-metadata consistency review
- Manual checks completed: volunteer page card spacing review, shared service/sidebar/footer card source review, footer-required link review, Change Log Hub release review and Cloudron LAMP compatibility review
- Known limitations: final QA in this session relies on local preview and regenerated output review rather than exhaustive route-by-route browser screenshots
- Rollback notes: restore the previous shared CSS, version files and release records, then rerun the PHP static export to restore the prior public snapshots if needed.

## [v2.9.3] - 2026-06-10

<span class="pill pill-version">Version v2.9.3</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>

### Summary

Added direct `file://` rendering support for exported static HTML snapshots while preserving the APES `public/` web-root and Cloudron LAMP hosting assumptions.

### Detailed changes

- Updated the static HTML exporter so generated snapshots rewrite internal root-relative assets, links and route-finder data to page-depth-aware relative paths.
- Tightened local validation helpers so PHP preview startup handles workspace paths with spaces and redirect assertions work reliably in Windows PowerShell.
- Preserved PHP source rendering, canonical URLs, Open Graph metadata, JSON-LD and hosted Cloudron paths so production output still uses the approved web-root model.
- Regenerated the public static snapshots so `public/index.html` can be opened directly from disk with styling, images, navigation and route-finder links intact.
- Updated README preview guidance to clarify that HTTP/PHP preview remains preferred, while exported static snapshots now support direct `file://` inspection.

### Affected areas

- Website: www.apes.org.uk
- Page or route: exported static snapshots, homepage file preview, internal static navigation, route-finder links, Change Log Hub, root and public release records, README and generated public HTML snapshots
- Files changed: static exporter, PHP preview and validation helpers, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots
- User groups affected: APES maintainers previewing exported snapshots locally in Codex, VS Code or a browser without a running local server
- Public impact: no public route, content, SEO metadata or production hosting behavior change is intended.
- Internal impact: maintainers can inspect the generated public bundle from disk while retaining HTTP preview and Cloudron LAMP parity for deployment checks.

### Version decision

- Previous version: v2.9.2
- New version: v2.9.3
- Version type: patch stable
- Reason for version bump: local static snapshot rendering fix without public route expansion, content change or production architecture change.

### Validation

- Checks run: PHP syntax checks where available, static export regeneration, generated-link inspection for homepage and nested routes, route-finder data inspection, local HTTP preview smoke checks and release-metadata consistency review
- Manual checks completed: direct `file://` homepage preview, stylesheet/logo rendering review, navigation link review, route-finder interaction review, footer-required link review, Change Log Hub release review and Cloudron LAMP compatibility review
- Known limitations: `file://` preview is for local inspection of exported static snapshots only; HTTP/PHP preview remains the preferred validation path for route and server behavior.
- Rollback notes: restore the previous static exporter, PHP preview helper, validation helper, version files and release records, then rerun the PHP static export to restore root-relative generated snapshots.

## [v2.9.2] - 2026-06-10

<span class="pill pill-version">Version v2.9.2</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-compliance">Compliance</span>

### Summary

Applied the APES rendering standard across the whole website by adding a shared PHP preview router, reusable deployment helpers and aligned Cloudron validation workflows.

### Detailed changes

- Added repo-root PHP preview routing through `dev/router.php` plus the task `APES: Preview PHP source site` so the shared PHP source can be tested over HTTP instead of relying on `file://` or static-only previews.
- Added reusable PowerShell helpers for PHP preview, static export, full-site validation, Cloudron staging and ZIP packaging while auto-detecting common Windows PHP installs such as XAMPP.
- Added GitHub Actions workflows to validate the shared PHP/public output and build a Cloudron public-bundle artifact so the documented deployment path now exists in the repository.
- Preserved `public/` as the approved APES web-root equivalent while keeping Apache redirects, sitemap, robots, branded error pages, footer links, README notes and release records aligned for Cloudron LAMP.

### Affected areas

- Website: www.apes.org.uk
- Page or route: whole-site preview workflow, shared PHP rendering, legacy redirect parity, deployment helpers, Change Log Hub, root and public release records, README, Cloudron docs, validation report and regenerated public HTML snapshots
- Files changed: `dev/router.php`, repo-root PowerShell helpers, GitHub workflow scaffolds, VS Code tasks, shared PHP runtime, Apache headers, VERSION files, root and public changelogs, README, deployment docs, validation report, Change Log Hub source and regenerated static HTML snapshots
- User groups affected: APES maintainers previewing, validating, packaging and publishing the website to Cloudron LAMP
- Public impact: no new public routes or information-architecture changes are intended; rendered output and release metadata stay aligned while maintainers get stronger preview and deployment parity.
- Internal impact: APES now has a consistent HTTP preview, validation, staging and packaging workflow grounded in the approved rendering standard.

### Version decision

- Previous version: v2.9.1
- New version: v2.9.2
- Version type: patch stable
- Reason for version bump: whole-site rendering, deployment and documentation alignment without public route expansion or architecture replacement.

### Validation

- Checks run: PHP lint checks for the shared runtime and router, `php public/scripts/export-static-site.php`, `powershell -ExecutionPolicy Bypass -File scripts/validate-public-site.ps1`, Cloudron staging/package helper review and release-metadata consistency review
- Manual checks completed: representative route preview review, root-relative asset 200 review, canonical and legacy-news redirect review, branded 403/404 handling review, footer-required link review, Change Log Hub release review, sitemap/robots review and Cloudron LAMP documentation alignment review
- Known limitations: final browser QA on the deployed Cloudron staging app and live Apache verification still require a post-upload environment outside this repo-only implementation pass.
- Rollback notes: restore the previous preview tasks and shared runtime files, remove the new repo-root scripts and workflows, restore the previous version files and release records, then rerun the shared PHP static export before redeployment.

## [v2.9.1] - 2026-06-10

<span class="pill pill-version">Version v2.9.1</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>

### Summary

Restored a reliable VS Code browser preview workflow by adding a PHP-free static preview task and Live Server public-root settings.

### Detailed changes

- Added a local PowerShell static preview helper that serves `public/` as the web root, resolves clean directory routes to `index.html`, and returns the branded static error pages when available.
- Added the VS Code task `APES: Preview public site` so maintainers can open `http://localhost:8080/` in VS Code Simple Browser without needing PHP installed.
- Set the VS Code Live Server root to `/public` so root-relative assets such as `/theme/site.css` and `/theme/js/site.js` resolve correctly during local preview.
- Kept the existing PHP export task unchanged for production static snapshot generation in PHP-enabled environments.

### Affected areas

- Website: www.apes.org.uk
- Page or route: local VS Code preview workflow, Change Log Hub, root and public release records, README and footer version display
- Files changed: VS Code settings and tasks, local preview helper, VERSION files, root CHANGELOG, public CHANGELOG, README, Change Log Hub source and generated static HTML snapshots
- User groups affected: APES maintainers previewing the website locally in VS Code
- Public impact: no public route, content, metadata, navigation or journey change is intended.
- Internal impact: maintainers can preview the exported website from the correct public web root even when PHP is not installed locally.

### Version decision

- Previous version: v2.9.0
- New version: v2.9.1
- Version type: patch stable
- Reason for version bump: local preview tooling and documentation fix without route, content, database or production hosting changes.

### Validation

- Checks run: VS Code task review, PowerShell static preview helper review, local HTTP preview smoke test, release-metadata consistency review and Cloudron LAMP compatibility review
- Manual checks completed: served-root asset path review, APES footer required-link review, Newsroom routing verification-only review, sitemap and robots verification-only review, branded 403/404/500 static-page review and VS Code preview workflow documentation review
- Known limitations: `php` is not installed in this implementation environment, so PHP syntax checks and the standard PHP static exporter still need to run in a PHP-enabled environment before deployment.
- Rollback notes: restore the previous VS Code settings and tasks, remove the local static preview helper, restore the previous version files and release records, then rerun the standard PHP export in a PHP-enabled environment if needed.

## [v2.9.0] - 2026-06-10

<span class="pill pill-version">Version v2.9.0</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Added</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-accessibility">Accessibility</span>

### Summary

Refactored the public website theme into modular CSS and browser-module JavaScript while adding a manual VS Code static export task for served-root preview workflows.

### Detailed changes

- Created the shared `/theme/` asset structure with ordered stylesheet imports for utilities, layouts, components, responsive rules and animation states.
- Moved shared browser behaviour into `/theme/js/` ES module entrypoints covering navigation, popup links, the development notice, release filters and the route finder.
- Updated the PHP renderer and footer to load `/theme/site.css` and `/theme/js/site.js` while leaving production PHP routing unchanged for Cloudron LAMP.
- Kept compatibility shims at the previous `/assets/css/site.css` and `/assets/js/site.js` paths for stale snapshots during the transition release.
- Added a manual VS Code task to run `php public/scripts/export-static-site.php` and documented the edit, export and served-root preview workflow.

### Affected areas

- Website: www.apes.org.uk
- Page or route: shared theme assets, shared PHP rendering, generated static snapshots, Change Log Hub, root and public release records, README and footer version display
- Files changed: shared CSS and JS asset structure, shared renderer and footer asset references, VS Code task configuration, VERSION files, root CHANGELOG, public CHANGELOG, README, Change Log Hub source and generated static HTML snapshots
- User groups affected: maintainers previewing the APES website locally, plus public visitors receiving the same shared styling and interactions after deployment
- Public impact: no route, content or journey change is intended; the public site should keep the same visual treatment and interaction behaviour through the new theme entrypoints.
- Internal impact: APES maintainers now have a clearer modular theme structure and a documented manual static export task for VS Code preview work.

### Version decision

- Previous version: v2.8.9
- New version: v2.9.0
- Version type: minor stable
- Reason for version bump: development workflow and shared asset-structure addition across the website without route, content, database or production hosting changes.

### Validation

- Checks run: `git pull`, modular CSS reconstruction review, asset-reference search, generated snapshot asset-path synchronisation, release-metadata consistency review, footer-link source review and Cloudron LAMP compatibility review
- Manual checks completed: PHP renderer and footer asset-reference review, VS Code task review, APES Newsroom routing verification-only review, sitemap and robots verification-only review, branded 403/404/500 source-page review and static preview workflow documentation review
- Known limitations: `php` is not installed in this implementation environment, so PHP syntax checks, the standard static exporter and full served-browser QA still need to run in a PHP-enabled environment before deployment.
- Rollback notes: restore the previous shared asset references, remove the `/theme/` entrypoints and VS Code task, restore the previous version files and release records, then rerun the standard PHP export in a PHP-enabled environment.

## [v2.8.9] - 2026-06-10

<span class="pill pill-version">Version v2.8.9</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-accessibility">Accessibility</span>

### Summary

Expanded the volunteering page into the main APES volunteer information hub, added practical rescue role categories and kept the approved Sheltermanager application form as the primary application route.

### Detailed changes

- Expanded the shared `/volunteer/` page definition with a prominent in-page application call to action that opens the approved Sheltermanager volunteer form.
- Added practical rescue volunteer role examples covering animal care, cleaning, feeding, enrichment, housekeeping, transport, fostering, adoption support, public enquiries, fundraising, education, retail, maintenance, administration, photography, student placements and specialist support.
- Confirmed public volunteering recruitment and support links continue to point to `/volunteer/`, while the operational Volunteer intranet link remains unchanged.
- Kept the volunteering page route, canonical URL, sitemap entry, robots rules, footer-required links, APES Newsroom routing and Cloudron LAMP hosting assumptions unchanged.
- Synchronised the canonical version files, README current-release notes, root changelog, public changelog mirror, Change Log Hub source and generated public snapshots to `v2.8.9`.

### Affected areas

- Website: www.apes.org.uk
- Page or route: `/volunteer/`, Change Log Hub, root and public release records, README current release and generated public HTML snapshots
- Files changed: shared site data, generated volunteer page, generated Change Log Hub, generated HTML footer version text, VERSION files, root CHANGELOG, public CHANGELOG and README
- User groups affected: prospective volunteers, student-placement applicants, supporters, staff and partners using the volunteering route
- Public impact: visitors can review common APES rescue volunteer role types and apply through the approved external form from the volunteer page.
- Internal impact: APES has one clearer volunteer information hub while preserving the existing external Sheltermanager application workflow.

### Version decision

- Previous version: v2.8.8
- New version: v2.8.9
- Version type: patch stable
- Reason for version bump: public-facing volunteering content expansion and generated-output synchronisation without route, SEO or hosting restructuring.

### Validation

- Checks run: `git pull`, volunteer-link audit, release-metadata consistency review, sitemap and robots verification-only review, footer-link source review, branded error-page footer review and Cloudron LAMP compatibility review
- Manual checks completed: volunteering hero and in-page application CTA review, public volunteering link review, canonical/title/meta/robots review for `/volunteer/`, sitemap entry review, APES Newsroom routing review and generated footer version review
- Known limitations: `php` is not installed in this implementation environment, so the standard static export and full rendered browser QA could not be completed here; generated snapshots were synchronised manually from the shared source content.
- Rollback notes: restore the previous volunteering body content, generated snapshots, version files and release records, then rerun the standard PHP export in a PHP-enabled environment if the expanded volunteering hub needs to be reversed.

## [v2.8.8] - 2026-06-10

<span class="pill pill-version">Version v2.8.8</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-accessibility">Accessibility</span>

### Summary

Replaced the volunteering-page primary hero call to action with the approved Sheltermanager volunteer application form so applicants can go straight to the correct external route.

### Detailed changes

- Updated the shared `/volunteer/` page definition so the primary hero button now opens the approved Sheltermanager volunteering form instead of sending visitors to the general contact page.
- Kept the volunteering page route, title, meta description, canonical URL, help-centre secondary action, sitemap entry, robots rules, footer-required links and APES Newsroom routing unchanged.
- Synchronised the canonical version files, README current-release notes, root changelog, public changelog mirror and Change Log Hub source to `v2.8.8`.
- Confirmed Cloudron LAMP compatibility remains unchanged because this is a shared PHP content update with no new runtime dependency or hosting requirement.
- The standard PHP static export could not be rerun in this environment because `php` is unavailable, so generated HTML snapshots and rendered footer version text still need resynchronising in a PHP-enabled environment before deployment.

### Affected areas

- Website: www.apes.org.uk
- Page or route: `/volunteer/`, Change Log Hub, root and public release records, README current release and generated public HTML snapshots once re-exported
- Files changed: shared site data, VERSION files, root CHANGELOG, public CHANGELOG and README
- User groups affected: prospective volunteers, student-placement applicants, supporters, staff and partners using the volunteering route
- Public impact: visitors can now use the main volunteering hero button to open the approved external application form directly
- Internal impact: the volunteering CTA now points to the approved Sheltermanager workflow while release records move forward to the next stable patch version

### Version decision

- Previous version: v2.8.7
- New version: v2.8.8
- Version type: patch stable
- Reason for version bump: small public-facing volunteering CTA change without any route, SEO or metadata restructuring

### Validation

- Checks run: `git pull`, volunteer page source review, release-metadata consistency review, sitemap and robots verification-only review, footer-link source review and Cloudron LAMP compatibility review
- Manual checks completed: volunteering hero-action source audit, canonical/title/meta/robots review for `/volunteer/`, sitemap entry review, footer required-link review, APES Newsroom routing review and Change Log Hub source review
- Known limitations: `php` is not installed in this implementation environment, so the standard static export, full rendered browser QA and generated HTML snapshot resynchronisation could not be completed here
- Rollback notes: restore the previous volunteering hero action, version files and release records, then rerun the standard PHP export in a PHP-enabled environment if the direct application CTA needs to be reversed

## [v2.8.7] - 2026-06-09

<span class="pill pill-version">Version v2.8.7</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-accessibility">Accessibility</span>

### Summary

Changed the shared sticky header so only the main navigation remains floating during scroll, while the top contact bar and development notice collapse until the page returns to the top.

### Detailed changes

- Added shared header scroll-state logic so the top contact bar and development notice collapse as soon as the page leaves the top, while the main navigation continues to stick in place.
- Updated the shared header CSS transitions, spacing and border handling so the compact state hides the two upper bars cleanly without leaving a persistent layout gap.
- Kept the shared header markup, desktop mega-menu structure, mobile overlay navigation pattern, footer routes and APES Newsroom routing unchanged while reusing the existing header-height offset logic.
- Synchronised the shared release metadata, README, changelog mirrors and version files to `v2.8.7`, and prepared the PHP source of truth for static snapshot regeneration once PHP is available again in a compatible environment.
- Preserved canonical URLs, sitemap entries, robots rules, footer-required links and branded error-page source files without route, label or metadata changes.

### Affected areas

- Website: www.apes.org.uk
- Page or route: shared header behaviour, shared CSS, shared JS, Change Log Hub, root and public release records, README and branded error pages once regenerated from source
- Files changed: shared CSS, shared JS, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG and README
- User groups affected: supporters, donors, volunteers, staff, partners and general public visitors using the shared sticky header across desktop, tablet and mobile layouts
- Public impact: visitors now see the contact bar and development notice disappear after scrolling away from the top while the main navigation remains available as the sticky header
- Internal impact: shared header behaviour now uses a single compact scroll state, keeping mega-menu positioning and mobile-nav offsets aligned to the visible sticky navigation height

### Version decision

- Previous version: v2.8.6
- New version: v2.8.7
- Version type: patch stable
- Reason for version bump: shared public-facing header scroll behaviour change without route, SEO or metadata restructuring

### Validation

- Checks run: shared header JS and CSS review, release-metadata consistency review, sitemap and robots verification-only review, footer-link source review and header offset logic review
- Manual checks completed: shared sticky-header source audit for compact-state transitions, desktop mega-menu anchor review, mobile overlay offset review, footer required-link review, APES Newsroom routing review and branded 403/404/500 source-page review
- Known limitations: the local PHP runtime is still unavailable in this implementation environment, so the standard static export, full rendered browser QA and generated HTML snapshot resynchronisation still need to run in a PHP-enabled environment before deployment
- Rollback notes: restore the previous shared header CSS and JS, version files and release records, then rerun the standard PHP export when PHP is available if the sticky-header compact behaviour needs to be reversed

## [v2.8.6] - 2026-06-04

<span class="pill pill-version">Version v2.8.6</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-accessibility">Accessibility</span>

### Summary

Removed the horizontal scrollbar from shared desktop mega menus by replacing viewport-based panel sizing with desktop-safe width constraints that do not overflow when the page has a vertical scrollbar.

### Detailed changes

- Updated the shared mega-menu panel sizing rules so desktop navigation no longer relies on `100vw` calculations that can include the browser scrollbar width and trigger horizontal overflow.
- Kept the current mobile overlay, accordion submenu behaviour, desktop open-state logic and shared header markup unchanged because the regression was isolated to desktop CSS width constraints.
- Synchronised the shared release metadata, README and version files to `v2.8.6`, and prepared the PHP source of truth for static snapshot regeneration once PHP is available again in a compatible environment.
- Preserved APES Newsroom routing, footer-required links, canonical URLs, sitemap entries, robots rules and branded error pages without route, label or metadata changes.
- Opened and linked the related GitHub issue for this fix as issue `#3`.

### Affected areas

- Website: www.apes.org.uk
- Page or route: shared header navigation, shared CSS, Change Log Hub, root and public release records, README and branded error pages once regenerated from source
- Files changed: shared CSS, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG and README
- User groups affected: supporters, donors, volunteers, staff, partners and general public visitors using desktop or laptop navigation
- Public impact: desktop visitors should no longer see a horizontal scrollbar when opening mega menus, while mobile and tablet navigation keeps the current overlay behaviour
- Internal impact: desktop mega-menu sizing now avoids viewport scrollbar-width drift, reducing the risk of future overflow regressions in the shared header

### Version decision

- Previous version: v2.8.5
- New version: v2.8.6
- Version type: patch stable
- Reason for version bump: shared public-facing desktop navigation overflow fix without any route, SEO or metadata restructuring

### Validation

- Checks run: shared CSS overflow review, release-metadata consistency review, sitemap and robots verification-only review, footer-link source review and issue-link verification
- Manual checks completed: desktop mega-menu width rule audit for 1280px, 1366px, 1440px and 1920px layouts by source inspection, mobile selector regression review for sub-981px rules, footer required-link review, APES Newsroom routing review and branded 403/404/500 source-page review
- Known limitations: the local PHP runtime is still unavailable in this implementation environment, so the standard static export, full rendered browser QA and generated HTML snapshot resynchronisation still need to run in a PHP-enabled environment before deployment
- Rollback notes: restore the previous shared CSS width rules, version files and release records, then rerun the standard PHP export when PHP is available if the desktop mega-menu overflow fix needs to be reversed

## [v2.8.5] - 2026-06-04

<span class="pill pill-version">Version v2.8.5</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-accessibility">Accessibility</span>

### Summary

Restored all shared mobile menu items by separating the desktop and mobile navigation list layout rules, so the mobile overlay once again shows the full APES menu without regressing the desktop horizontal header.

### Detailed changes

- Removed the shared desktop flex layout from the base navigation list selector and scoped the horizontal nav row explicitly to `@media (min-width: 981px)`.
- Reworked the shared mobile navigation list below `981px` to use an explicit single-column grid with full-width top-level items, preserving scrollable panel behaviour and submenu expansion space.
- Added mobile-specific visibility and sizing safeguards for top-level list items, grouped menu sections and the donate CTA so later desktop-oriented rules cannot collapse the opened mobile panel down to only the first visible item.
- Synchronised the shared release metadata, README, version files and generated website snapshots to `v2.8.5`, manually updating the exported HTML because the standard PHP static export script is still unavailable in this shell.
- Preserved APES Newsroom routing, footer-required links, canonical URLs, sitemap entries, robots rules and branded error pages without route, label or metadata changes.
- Checked for a related GitHub issue and found no explicit linked issue in the current repo context, so no issue update was posted during this implementation pass.

### Affected areas

- Website: www.apes.org.uk
- Page or route: shared header navigation, shared CSS, Change Log Hub, root and public release records, README, branded error pages and generated public HTML snapshots
- Files changed: shared CSS, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated or manually synchronised static HTML snapshots
- User groups affected: supporters, donors, volunteers, staff, partners and general public visitors using mobile, tablet or desktop navigation
- Public impact: mobile visitors can now see and use the full top-level menu and grouped submenu sections again while desktop visitors keep the intended horizontal navigation bar
- Internal impact: desktop and mobile navigation layout rules are now more clearly isolated, reducing the risk of future breakpoint cascade regressions in the shared header

### Version decision

- Previous version: v2.8.4
- New version: v2.8.5
- Version type: patch stable
- Reason for version bump: shared public-facing mobile navigation visibility hotfix without any route, SEO or metadata restructuring

### Validation

- Checks run: shared CSS regression review, release-metadata consistency review, footer-link consistency review and generated HTML sync review
- Manual checks completed: mobile nav selector audit for 390px, 430px and 768px breakpoints, desktop nav selector audit for 1366px and 1920px layouts, footer required-link review, APES Newsroom routing review, sitemap/canonical/robots verification-only review and branded 403/404/500 source-page review
- Known limitations: the local PHP runtime was unavailable in this implementation pass, so the standard PHP static export script and final browser-based interaction QA still need to run in a PHP-enabled environment before deployment
- Rollback notes: restore the previous shared CSS, version files and release records, then rerun the standard PHP export or manual snapshot sync if the mobile navigation hotfix needs to be reversed

## [v2.8.4] - 2026-06-04

<span class="pill pill-version">Version v2.8.4</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-accessibility">Accessibility</span>

### Summary

Restored the shared development notice so new visitors see the site-under-development warning, can dismiss it, and do not get it again on the same browser after dismissal.

### Detailed changes

- Re-enabled the shared APES development header notice and first-visit popup with refreshed wording that explains the new site is still under development and some features or buttons may not work yet.
- Separated the shared header and popup notice controls in the PHP source of truth so APES can manage the persistent banner and first-visit modal intentionally without relying on one generic switch.
- Changed popup dismissal persistence from session storage to local storage so dismissal survives refreshes, navigation and browser restarts in the same browser profile, while keeping a safe in-memory fallback when storage is unavailable.
- Synchronised the shared release metadata, README, version files and generated website snapshots to `v2.8.4`, manually updating the exported HTML because the standard PHP static export script is still unavailable in this shell.
- Preserved APES Newsroom routing, footer-required links, canonical URLs, sitemap entries, robots rules and branded error pages without route, label or metadata changes.
- Checked for a related GitHub issue and found no explicit linked issue in the current repo context, so no issue update was posted during this implementation pass.

### Affected areas

- Website: www.apes.org.uk
- Page or route: shared header, shared footer, shared notice behaviour, root and public release records, Change Log Hub, branded error pages and generated public HTML snapshots
- Files changed: shared PHP rendering, shared site data release records, shared JS, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated or manually synchronised static HTML snapshots
- User groups affected: supporters, donors, volunteers, staff, partners and general public visitors encountering the APES development notice and live-chat help prompt
- Public impact: new visitors now see the site-under-development warning, can dismiss it, and will not be shown it again on the same browser after dismissal
- Internal impact: notice visibility and dismissal behaviour are now clearer to manage in the shared shell and better aligned with the intended visitor experience

### Version decision

- Previous version: v2.8.3
- New version: v2.8.4
- Version type: patch stable
- Reason for version bump: shared public-facing notice-behaviour restoration and persistence fix without any route, SEO or metadata restructuring

### Validation

- Checks run: shared notice source review, release-metadata update review, generated HTML sync review and footer-link consistency review
- Manual checks completed: development notice configuration review, local-storage dismissal logic review, footer required-link review, APES Newsroom routing review, sitemap/canonical/robots verification-only review and branded 403/404/500 source-page review
- Known limitations: the local PHP runtime was unavailable in this implementation pass, so the standard PHP static export script and full browser-based interaction QA still need to run in a PHP-enabled environment before deployment
- Rollback notes: restore the previous shared notice configuration, JS, version files and release records, then rerun the standard PHP export or manual snapshot sync if the notice rollout needs to be reversed

## [v2.8.3] - 2026-06-04

<span class="pill pill-version">Version v2.8.3</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-type">Removed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-accessibility">Accessibility</span>

### Summary

Removed the site-wide Hellobar embed from the shared APES page shell so APES-owned popups can run without the third-party Hello Bar script loading across the public website and branded error pages.

### Detailed changes

- Removed the shared Hello Bar script include from the PHP document renderer while leaving the APES development popup, booking popup-window behaviour and Donorbox popup flows in place.
- Synchronised the shared release metadata, README, version files, Change Log Hub source and generated website snapshots to `v2.8.3` so the release record now reflects the Hellobar removal.
- Manually synchronised the exported HTML snapshots in `/public/` because no local PHP runtime was available for the standard static export script in this shell.
- Preserved APES Newsroom routing, footer-required links, canonical URLs, sitemap entries, robots rules and branded error pages without route, label or metadata changes.
- Checked for a related GitHub issue and found no explicit linked issue in the current repo context, so no issue update was posted during this implementation pass.

### Affected areas

- Website: www.apes.org.uk
- Page or route: shared PHP rendering, all generated public and error-page HTML snapshots, Change Log Hub, root and public release records, README and footer version display
- Files changed: shared PHP renderer, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README, Change Log Hub snapshot and regenerated or manually synchronised static HTML snapshots
- User groups affected: supporters, donors, volunteers, staff, partners and general public visitors encountering site-wide overlays, donation prompts or branded fallback pages
- Public impact: visitors no longer load the third-party Hello Bar script site-wide, reducing interference risk with APES-managed popup behaviour while leaving APES and Donorbox popup journeys available
- Internal impact: popup ownership is now cleaner in the shared shell, making future APES-managed popup work easier to control without a parallel Hello Bar dependency

### Version decision

- Previous version: v2.8.2
- New version: v2.8.3
- Version type: patch stable
- Reason for version bump: shared public-facing third-party popup-script removal without any route, SEO or metadata restructuring

### Validation

- Checks run: shared renderer review, generated HTML sync review, Hellobar reference search, popup wiring review and release-metadata consistency review
- Manual checks completed: Hellobar removal review on representative public and branded error-page output, development-popup source review, booking-popup script review, Donorbox popup embed review, footer required-link review, APES Newsroom routing review, sitemap/canonical/robots verification-only review and branded 403/404/500 source-page review
- Known limitations: the local PHP runtime was unavailable in this implementation pass, so the standard PHP static export script and full rendered browser QA still need to run in a PHP-enabled environment before deployment
- Rollback notes: restore the previous shared renderer, version files and release records, then rerun the standard PHP export to reintroduce the prior shell output if the Hellobar removal needs to be reversed

## [v2.8.2] - 2026-06-04

<span class="pill pill-version">Version v2.8.2</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-accessibility">Accessibility</span>

### Summary

Restored the shared desktop navigation to its intended horizontal header layout after the mobile overlay panel wrapper caused the desktop menu list to fall back to a vertical stack.

### Detailed changes

- Retargeted the shared desktop navigation flex and spacing selectors to `.primary-nav > .primary-nav__panel > ul` so the real wrapped list once again renders as a horizontal row above `981px`.
- Left the current mobile menu architecture, overlay flow, close button, accordion submenu behaviour and shared navigation JavaScript unchanged because the regression was isolated to desktop CSS selector scope.
- Synchronised the shared stylesheet, Change Log Hub release record, mirrored changelogs, README and canonical version files to `v2.8.2`, and prepared the PHP source of truth for static snapshot regeneration in a PHP-enabled environment.
- Preserved APES Newsroom routing, footer-required links, canonical URLs, sitemap entries, robots rules and branded error pages without route, label or metadata changes.
- Checked for a related GitHub issue and found no explicit linked issue in the current repo context, so no issue update was posted during this implementation pass.

### Affected areas

- Website: www.apes.org.uk
- Page or route: shared header navigation, shared CSS, Change Log Hub, root and public release records, README, branded error pages and regenerated static HTML snapshots
- Files changed: shared CSS, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots
- User groups affected: supporters, donors, volunteers, staff, partners and general public visitors using desktop, laptop, tablet or mobile navigation
- Public impact: desktop visitors now see the main navigation links aligned horizontally in the header again while the mobile menu continues to behave as the recent overlay release intended
- Internal impact: the shared desktop selector chain now matches the wrapped navigation markup introduced by the mobile menu work, reducing the risk of future desktop/mobile navigation drift

### Version decision

- Previous version: v2.8.1
- New version: v2.8.2
- Version type: patch stable
- Reason for version bump: shared public-facing navigation layout regression fix without any route, SEO or metadata restructuring

### Validation

- Checks run: shared CSS review, release-metadata consistency review and source/output diff inspection
- Manual checks completed: desktop selector review for 1366px and 1920px layouts, mobile overlay selector-regression review for 768px, 430px and 390px breakpoints, footer required-link review, APES Newsroom routing review, sitemap/canonical/robots verification-only review, and branded 403/404/500 source-page review
- Known limitations: the local PHP runtime was unavailable in this implementation pass, so PHP syntax checks, static HTML export/regeneration and full rendered browser QA still need to run in a PHP-enabled environment before deployment
- Rollback notes: restore the previous shared CSS, version files and release records, then re-export the static HTML snapshots once PHP is available if the desktop navigation fix needs to be reversed

## [v2.8.1] - 2026-06-04

<span class="pill pill-version">Version v2.8.1</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-accessibility">Accessibility</span>

### Summary

Reworked the shared APES mobile navigation into a dedicated overlay panel so direct links and accordion groups now respond reliably on touch devices without sticky donate or chat controls blocking taps.

### Detailed changes

- Rebuilt the shared mobile navigation shell around the existing header markup by adding a dedicated mobile panel, close control, backdrop hook and clearer open-state accessibility labels while preserving all existing desktop link targets and mega-menu content.
- Reworked the shared mobile navigation CSS below `980px` so the menu opens as a fixed overlay panel with independent scrolling, full-width touch targets, accordion-friendly `<details>` groups and a body scroll-lock state.
- Refined the shared navigation script so mobile open-state, desktop mega-menu positioning and close behaviour are handled separately, allowing direct links and submenu links to navigate on first tap while still supporting Escape, overlay close and page-transition resets.
- Kept APES Newsroom routing, footer-required links, canonical URLs, sitemap entries and branded error pages unchanged, then regenerated the static HTML snapshots and synchronised the canonical version plus release records to `v2.8.1`.

### Affected areas

- Website: www.apes.org.uk
- Page or route: shared header navigation, shared CSS, shared JS, Change Log Hub, root and public release records, README, branded error pages and regenerated static HTML snapshots
- Files changed: shared PHP header, shared CSS, shared JS, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots
- User groups affected: supporters, donors, volunteers, staff, partners and general public visitors using mobile or tablet navigation
- Public impact: mobile visitors can now open the menu, expand grouped sections, follow direct links and submenu links on first tap, and use the overlay without donate or chat widgets stealing interaction
- Internal impact: the shared navigation state model is now cleaner across mobile and desktop breakpoints, reducing the chance of future tap-target and overlay regressions

### Version decision

- Previous version: v2.8.0
- New version: v2.8.1
- Version type: patch stable
- Reason for version bump: shared public-facing navigation reliability, accessibility and overlay-behaviour fixes without any route or SEO structure change

### Validation

- Checks run: local PHP syntax checks, shared CSS and JS review, static HTML export and generated HTML inspection
- Manual checks completed: mobile navigation markup/state review, footer required-link review, APES Newsroom routing review, sitemap and canonical verification-only review, and branded 403/404/500 page review after regeneration
- Known limitations: live device/browser interaction with third-party Donorbox and Chatwoot widgets still requires a post-deploy touch test outside this repo-only implementation pass
- Rollback notes: restore the previous shared header, CSS, JS, version files and release records, then re-export the static HTML snapshots if the mobile navigation rollout needs to be reversed

## [v2.8.0] - 2026-06-04

<span class="pill pill-version">Version v2.8.0</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Added</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-compliance">Compliance</span>
<span class="pill pill-accessibility">Accessibility</span>

### Summary

Completed the APES launch SEO and production-cutover pass by tightening shared metadata and JSON-LD, redirecting legacy main-site news URLs into APES Newsroom, and hardening robots, sitemap and error-page handling for Cloudron LAMP.

### Detailed changes

- Added shared robots-meta support plus Organization, WebSite and breadcrumb JSON-LD through the PHP renderer while keeping `https://www.apes.org.uk` as the only canonical host in shared metadata output.
- Reworked the `/news/` page into a pure APES Newsroom handoff, removed local news-post and tag pages from the shared page model, and mapped each legacy `/news/post/...` and `/news/tag/...` route to an exact one-hop APES Newsroom successor URL in Apache.
- Disabled the production development notice, blocked public access to technical `/includes/`, `/outputs/` and `/scripts/` paths, and added branded `403.html` and `500.html` companions alongside the updated `404.html` experience.
- Regenerated the static HTML snapshots, refreshed `robots.txt` and `sitemap.xml`, synchronised the canonical version to `v2.8.0`, and updated the APES release, inventory, content-audit and redirect records for launch.

### Affected areas

- Website: www.apes.org.uk
- Page or route: shared PHP rendering, `/news/`, `/search/`, error pages, Apache routing, `robots.txt`, `sitemap.xml`, Change Log Hub, root and public release records, and regenerated static HTML snapshots
- Files changed: shared PHP rendering, shared site data, `.htaccess`, `robots.txt`, `sitemap.xml`, VERSION files, README, changelog records, documentation records and regenerated static HTML snapshots
- User groups affected: supporters, donors, volunteers, staff, partners and general public visitors
- Public impact: visitors now get cleaner canonical metadata, proper APES Newsroom routing for legacy news URLs, production-ready search indexing, and branded error handling with clearer recovery routes
- Internal impact: launch SEO rules, redirect mappings, sitemap truth and error-page handling now live in the shared source of truth and Cloudron-facing Apache configuration

### Version decision

- Previous version: v2.7.0
- New version: v2.8.0
- Version type: minor stable
- Reason for version bump: site-wide SEO, routing, error-handling and production-launch behaviour changes without a breaking public-domain move

### Validation

- Checks run: local PHP syntax checks, static HTML export, sitemap regeneration, generated HTML inspection and redirect/error-route review
- Manual checks completed: canonical metadata review, APES Newsroom redirect review, footer required-link review, search indexability review, robots/sitemap review, error-page review and changelog/version synchronisation review
- Known limitations: live Cloudron staging verification, Apache status-code confirmation in the deployed app and Google Search Console submission still require post-deploy checks outside this repo-only implementation pass
- Rollback notes: restore the previous shared PHP, site data, Apache config, robots/sitemap files, version files and release records, then re-export the static HTML snapshots if the launch SEO cutover needs to be reversed

## [v2.7.0] - 2026-06-04

<span class="pill pill-version">Version v2.7.0</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Added</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-compliance">Compliance</span>

### Summary

Added a site-wide development notice popup and persistent header message, then rebalanced the shared APES footer so every footer-card link now renders as a clearer tile-style action.

### Detailed changes

- Added a persistent development notice band near the top of the shared header and a first-open popup that explains some links and features are still in development while directing visitors to live chat for fast help.
- Wired the notice actions into the existing Chatwoot widget with a safe contact-page fallback, session-based dismissal, keyboard-accessible dialog behaviour and focus return on close.
- Redistributed the four APES footer cards into more balanced groups and made every footer-card link render as a visible tile without removing the required donation, Privacy Policy, Terms of Service, Change Log Hub or intranet routes.
- Preserved APES Newsroom routing, APES CIC identity and footer compliance rules, then synchronised the canonical version plus release records to v2.7.0 before regenerating the static HTML snapshots.

### Affected areas

- Website: www.apes.org.uk
- Page or route: shared header, shared footer, shared site data, shared CSS, shared JS, Change Log Hub, footer version display, root and public release records, and regenerated static HTML snapshots
- Files changed: shared PHP rendering, shared site data, shared CSS, shared JS, VERSION, public VERSION, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots
- User groups affected: supporters, donors, volunteers, staff, partners and general public visitors
- Public impact: visitors now see a clear development notice, get a direct route into live chat, and can use a more balanced, button-led footer across the public site
- Internal impact: the shared shell now holds the development-notice copy and behaviour in one place, and the footer grouping is easier to maintain without route-level edits

### Version decision

- Previous version: v2.6.4
- New version: v2.7.0
- Version type: minor stable
- Reason for version bump: site-wide public messaging and shared-shell footer behaviour additions without a breaking route restructure

### Validation

- Checks run: local PHP syntax checks, shared CSS and JS review, static HTML export and generated HTML inspection
- Manual checks completed: header notice review, popup/session behaviour review, footer required link and intranet attribute review, footer version alignment review, APES Newsroom route review and changelog/version synchronisation review
- Known limitations: deployed FTP validation and live browser confirmation still require a post-push check outside this repo-only implementation pass
- Rollback notes: restore the previous shared PHP, site data, CSS, JS, version files and release records, then re-export the static HTML snapshots if the notice or footer rollout needs to be reversed

## [v2.6.4] - 2026-06-03

<span class="pill pill-version">Version v2.6.4</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>

### Summary

Centred the shared APES sidebar logo card more explicitly through the shared stylesheet so the feature logo sits consistently within its support panel across the public website.

### Detailed changes

- Updated the shared `.brand-feature-panel` styling so the sidebar card, `<picture>` wrapper and logo image all centre explicitly without changing the shared sidebar markup.
- Kept the existing responsive logo sizing and card spacing while preventing route-level drift for the logo card across all pages that inherit the shared sidebar.
- Preserved the APES column-card footer, required donation, Privacy Policy, Terms of Service and Change Log Hub links, left APES Newsroom routing unchanged, and synchronised the canonical version plus release records to v2.6.4.

### Affected areas

- Website: www.apes.org.uk
- Page or route: shared sidebar logo card, shared CSS, Change Log Hub, footer version display, root and public release records, and regenerated static HTML snapshots
- Files changed: shared CSS, shared release data, VERSION, public VERSION, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots
- User groups affected: supporters, donors, volunteers, staff, partners and general public visitors
- Public impact: visitors now see the APES feature logo centred more cleanly within the shared sidebar card across the public site
- Internal impact: the shared sidebar logo card now has clearer centring rules in the main stylesheet, reducing the chance of route-specific visual drift

### Version decision

- Previous version: v2.6.3
- New version: v2.6.4
- Version type: patch stable
- Reason for version bump: low-risk public-facing layout fix to the shared sidebar logo presentation without a breaking restructure or route change

### Validation

- Checks run: shared CSS review, shared PHP data review, static HTML export and generated HTML inspection
- Manual checks completed: homepage, inner-page, Change Log Hub and 404 sidebar logo review; footer required link review; APES Newsroom route review; and changelog/version alignment review
- Known limitations: deployed FTP validation and live browser confirmation still require a post-push check outside this repo-only implementation pass
- Rollback notes: restore the previous shared CSS, version files and release records, then re-export the static HTML snapshots if the logo-card alignment change needs to be reversed

## [v2.6.3] - 2026-06-03

<span class="pill pill-version">Version v2.6.3</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Added</span>
<span class="pill pill-type">Changed</span>

### Summary

Added the requested site-wide messaging, donation and support embeds through the shared shell, while keeping the APES footer layout, required footer links and release records aligned to a new stable patch version.

### Detailed changes

- Added OneSignal, Hello Bar and Mastodon `rel="me"` verification links through the shared document shell so the integrations load centrally across the public website.
- Added a site-wide Donorbox sticky popup widget through the shared body shell, keeping the existing Donate page content and support routes unchanged.
- Consolidated the footer-side third-party integrations into one Facebook SDK loader with app ID `670420541399530` and one Chatwoot loader for the APES workspace, avoiding the duplicated legacy Facebook snippets from the supplied markup.
- Preserved the APES column-card footer, required donation, Privacy Policy, Terms of Service and Change Log Hub links, left APES Newsroom routing unchanged, and synchronised the canonical version plus release records to v2.6.3.

### Affected areas

- Website: www.apes.org.uk
- Page or route: shared document head, shared body shell, shared footer, Change Log Hub, footer version display, root and public release records, and regenerated static HTML snapshots
- Files changed: shared PHP rendering, shared footer output, VERSION, public VERSION, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots
- User groups affected: supporters, donors, volunteers, staff, partners and general public visitors
- Public impact: visitors now receive the requested site-wide notification, donation, social-verification and support-widget integrations without a visible footer redesign or route change
- Internal impact: shared shell integrations are now centralised in one maintained source of truth, reducing duplication and keeping future release management simpler

### Version decision

- Previous version: v2.6.2
- New version: v2.6.3
- Version type: patch stable
- Reason for version bump: small site-wide public integration additions and shared-shell maintenance improvements without a breaking restructure or URL change

### Validation

- Checks run: shared PHP renderer review, local PHP syntax checks, static HTML export and generated HTML inspection
- Manual checks completed: shared head/body/footer embed review, footer required link review, intranet link attribute review, APES Newsroom route review and changelog/version alignment review
- Known limitations: external widget runtime behaviour, live browser confirmation and deployed FTP validation still require a post-push check outside this repo-only implementation pass
- Rollback notes: restore the previous shared renderer, shared footer output, version files and changelog entries, then re-export the static HTML snapshots if the integration rollout needs to be reversed

## [v2.6.2] - 2026-06-03

<span class="pill pill-version">Version v2.6.2</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Added</span>
<span class="pill pill-type">Changed</span>

### Summary

Refreshed the Donate page with stronger area-of-greatest-need messaging, added a Donorbox popup donation button and donor wall, and synchronised the shared release records to the new stable patch version.

### Detailed changes

- Rewrote the Donate page body copy to explain how flexible donations support rescue, rehabilitation, housing, daily welfare costs, education and public support across APES.
- Added the requested Donorbox popup button installer and secure donation button for the approved area-of-greatest-need route, while keeping a standard link fallback if JavaScript or popups are unavailable.
- Added the requested Donorbox donor wall embed inside an APES-styled supporter section so the page shows visible community backing without changing the wider site architecture.
- Added shared styling for the Donorbox donation section and embed, preserved the required footer links and version display, kept APES Newsroom routing unchanged, and synchronised the canonical version plus changelog records to v2.6.2.

### Affected areas

- Website: www.apes.org.uk
- Page or route: Donate page, shared CSS, Change Log Hub, footer version display and regenerated static HTML snapshots
- Files changed: shared site data, shared CSS, VERSION, public VERSION, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots
- User groups affected: supporters, donors, volunteers, staff, partners and general public visitors
- Public impact: visitors now get clearer donation messaging, a popup donation flow and visible donor-wall engagement on the main Donate page
- Internal impact: the APES donation journey now has a clearer shared content source and release record for future fundraising updates

### Version decision

- Previous version: v2.6.1
- New version: v2.6.2
- Version type: patch stable
- Reason for version bump: public-facing donation content and embed improvements without a breaking restructure or route change

### Validation

- Checks run: shared PHP and CSS inspection, local PHP syntax checks, static HTML export and generated HTML inspection
- Manual checks completed: Donate page copy review, Donorbox popup button presence review, donor wall embed review, footer required link review, APES Newsroom route review and changelog/version alignment review
- Known limitations: popup, external embed behaviour and deployed FTP validation still require a live browser and post-push deployment check outside this repo-only implementation pass
- Rollback notes: restore the previous donate copy, shared CSS, version files and changelog entries, then re-export the static HTML snapshots if the release needs to be reversed

## [v2.6.1] - 2026-06-03

<span class="pill pill-version">Version v2.6.1</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-accessibility">Accessibility</span>

### Summary

Added three popup-enabled booking routes to the Bookings page and regrouped the shared footer so key public, legal and staff routes are easier to scan without changing the wider site architecture.

### Detailed changes

- Added a dedicated three-option booking chooser to the Bookings page for APES Bookings, Shelter and Rescue Bookings, and Pet Care Clinic Bookings, each pointing to the requested workspace appointment form.
- Added shared popup-window launch behaviour in the site JavaScript so the booking routes open in a centred external window when allowed, while preserving a normal new-tab fallback when popups are blocked or JavaScript is unavailable.
- Extended the shared footer data model so footer items can render as standard links, highlighted route tiles or non-link subheadings, then regrouped the footer into clearer About, services, support and policy/update/staff sections.
- Preserved the required donation, Privacy Policy, Terms of Service, Change Log Hub, APES Newsroom and intranet links, kept APES Newsroom routing unchanged, and synchronised the canonical version plus changelog records to v2.6.1.

### Affected areas

- Website: www.apes.org.uk
- Page or route: Bookings, shared footer, Change Log Hub, footer version display and regenerated static HTML snapshots
- Files changed: shared PHP rendering, shared CSS, shared JS, shared site data, VERSION, public VERSION, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots
- User groups affected: service users, supporters, volunteers, staff, partners and general public visitors
- Public impact: visitors can now choose the correct external booking form directly from the Bookings page and scan the shared footer more quickly for core service, legal and intranet routes
- Internal impact: footer presentation intent and popup booking behaviour are now managed centrally so future route and release updates stay aligned

### Version decision

- Previous version: v2.6.0
- New version: v2.6.1
- Version type: patch stable
- Reason for version bump: small public-facing booking and footer usability improvements without a breaking restructure or URL change

### Validation

- Checks run: local PHP syntax checks, shared CSS/JS/PHP inspection, static HTML export and generated HTML inspection
- Manual checks completed: Bookings chooser label and URL review, popup-launch fallback review, desktop and mobile footer grouping review, footer required link review, APES Newsroom route review and changelog/version alignment review
- Known limitations: popup and responsive verification in this environment is based on source and generated-output inspection rather than a full deployed browser pass against Cloudron hosting
- Rollback notes: restore the previous shared footer data, JS, CSS, bookings content, version files and changelog entries, then re-export the static HTML snapshots if the release needs to be reversed

## [v2.6.0] - 2026-06-03

<span class="pill pill-version">Version v2.6.0</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-accessibility">Accessibility</span>
<span class="pill pill-compliance">Compliance</span>

### Summary

Added a shared APES image system across key public routes, bringing supportive photography and illustration into the homepage, route-finder, fundraising, bookings, educational and relocation journeys while keeping release, footer and Newsroom rules aligned.

### Detailed changes

- Added six deployable APES image assets plus WebP variants inside the public asset tree and wired them through shared PHP rendering with explicit dimensions, responsive picture markup and lazy loading for non-hero placements.
- Extended the shared renderer and stylesheet so homepage hero media, route-finder illustrations and reusable feature-media sections can be enabled per page without changing public routes, footer links or form behaviour.
- Placed the new visuals on the homepage, Services hub, Bookings, Educational visits, About APES, Fundraising priorities and Help Us Move routes using conservative descriptive copy that supports the public journeys without implying named animals or live case evidence.
- Preserved APES Newsroom routing, the APES column-card footer, required donation, Privacy Policy, Terms of Service and Change Log Hub links, and synchronised the canonical version plus changelog records to v2.6.0.
- Checked for a related GitHub issue and found no explicit linked issue in the current repo context, so issue-update templates were not posted during this implementation pass.

### Affected areas

- Website: www.apes.org.uk
- Page or route: homepage hero and route finder, Services hub, Bookings, Educational visits, About APES, Fundraising priorities, Help Us Move, Change Log Hub, footer version display and regenerated static HTML snapshots
- Files changed: shared PHP rendering, shared CSS, shared site data, VERSION, public VERSION, root CHANGELOG, public CHANGELOG and regenerated static HTML snapshots
- User groups affected: supporters, adopters, service users, volunteers, partners, educators and general public visitors
- Public impact: visitors now see warmer, route-relevant visuals across key public journeys with responsive image delivery and no route or footer-link changes
- Internal impact: reusable image metadata and shared media rendering now support future APES visual placements from a central source of truth

### Version decision

- Previous version: v2.5.0
- New version: v2.6.0
- Version type: minor stable
- Reason for version bump: new public-facing visual content and shared rendering capabilities added across multiple core routes without a breaking restructure

### Validation

- Checks run: local PHP syntax checks, shared CSS/PHP inspection, WebP asset generation, static HTML export and generated HTML inspection
- Manual checks completed: homepage hero and compact route-finder layout review, Services hub route-finder and care-image review, Bookings and Educational visits image stacking review, Fundraising and Help Us Move image placement review, footer required link review, APES Newsroom route review and changelog/version alignment review
- Known limitations: validation in this environment focused on generated output and local inspection rather than a full live-browser comparison on every published route
- Rollback notes: restore the previous shared renderer, shared CSS, image references, version files and changelog entries, then re-export the static HTML snapshots if the image rollout needs to be reversed

## [v2.5.0] - 2026-06-03

<span class="pill pill-version">Version v2.5.0</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-accessibility">Accessibility</span>
<span class="pill pill-compliance">Compliance</span>

### Summary

Promoted the shared hero into a full-width site header panel, moved the previous hero-side support cards into the lower sidebar across all rendered public routes, and synchronised the release metadata to the new minor stable version.

### Detailed changes

- Updated the shared PHP page renderer so the hero panel now spans the full content width while the logo, contact and connected-service cards render in the lower page sidebar above page-specific related links.
- Reworked the shared stylesheet so the new full-width hero and lower two-column body/sidebar layout behave consistently across the homepage, inner content routes, Change Log Hub and 404 page without changing route copy or CTA destinations.
- Preserved APES Newsroom routing, footer structure, required donation, Privacy Policy, Terms of Service and Change Log Hub links, and intranet link rules while applying the shared layout shift.
- Bumped the canonical version and synchronised the website Change Log Hub, root changelog, public changelog mirror and exported static HTML snapshots to v2.5.0.

### Affected areas

- Website: www.apes.org.uk
- Page or route: homepage hero, all shared inner-page hero/sidebar layouts, Change Log Hub, 404 page, footer version display and regenerated static HTML snapshots
- Files changed: shared PHP rendering, shared CSS, shared site data, VERSION, public VERSION, root CHANGELOG, public CHANGELOG and regenerated static HTML snapshots
- User groups affected: supporters, adopters, service users, volunteers, partners and general public visitors
- Public impact: visitors now see a full-width hero followed by a clearer lower content/sidebar layout that keeps support cards available without crowding the page header
- Internal impact: the shared renderer now owns a single full-width hero pattern across all exported public routes and the release metadata is aligned at v2.5.0

### Version decision

- Previous version: v2.4.6
- New version: v2.5.0
- Version type: minor stable
- Reason for version bump: site-wide public layout change across the shared rendering system without route or content-model changes

### Validation

- Checks run: shared PHP renderer review, shared CSS review, local PHP syntax checks, static HTML export and generated HTML inspection
- Manual checks completed: homepage, representative top-level route, nested route, Change Log Hub and 404 layout review; footer required link review; intranet link attribute review; APES Newsroom route review; changelog/version alignment review
- Known limitations: final browser QA is limited to local rendered/output inspection in this environment rather than a full live-browser pass across every route
- Rollback notes: restore the previous shared renderer, CSS and release metadata, then re-export the static HTML snapshots if the new layout needs to be reverted

## [v2.4.6] - 2026-06-03

<span class="pill pill-version">Version v2.4.6</span>
<span class="pill pill-status">Stable</span>
<span class="pill pill-type">Changed</span>
<span class="pill pill-fix">Fix</span>
<span class="pill pill-compliance">Compliance</span>
<span class="pill pill-accessibility">Accessibility</span>

### Summary

Tightened the shared hero layout so the primary content panel no longer leaves an oversized gap below the call-to-action buttons, and synchronised the release metadata back to the canonical site version.

### Detailed changes

- Updated the shared hero grid so the hero panel aligns to its content instead of stretching to match the taller hero aside, which removes the empty space beneath the hero buttons on the homepage and all shared inner-page hero variants.
- Applied the change in the shared stylesheet only, leaving route content, hero copy, buttons, footer structure and APES Newsroom destinations untouched.
- Re-synchronised the canonical version, website Change Log Hub and root changelog after correcting the existing mismatch between the repository `VERSION` file and the rendered site release metadata.
- Regenerated the exported static HTML snapshots and synchronised the canonical version, website Change Log Hub and root changelog.

### Affected areas

- Website: www.apes.org.uk
- Page or route: homepage shared hero, inner-page shared hero pattern, Change Log Hub, footer version display and regenerated static HTML snapshots
- Files changed: shared CSS, shared site data, VERSION, root CHANGELOG and regenerated static HTML snapshots
- User groups affected: supporters, adopters, service users, volunteers, partners and general public visitors
- Public impact: visitors now see a tighter hero layout with less empty space beneath hero actions, while footer version text and release records now consistently show v2.4.6
- Internal impact: shared hero sizing now behaves consistently across rendered routes and the repository release metadata matches the generated site output again

### Version decision

- Previous version: v2.4.5
- New version: v2.4.6
- Version type: patch stable
- Reason for version bump: small public-facing layout fix across the shared hero component with no route or content restructure

### Validation

- Checks run: shared CSS inspection, local PHP syntax checks, static HTML export and generated HTML inspection
- Manual checks completed: homepage and representative inner-page hero spacing review, footer required link review, footer version alignment review, APES Newsroom route review and changelog synchronisation review
- Known limitations: final browser QA depends on local rendered output spot checks rather than opening every exported route individually
- Rollback notes: restore the previous shared CSS and release metadata, then re-export the static HTML snapshots if needed
