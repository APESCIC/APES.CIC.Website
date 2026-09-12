## Current release

- Version: `v4.1.0b`
- Release date: `2026-09-12`
- Release impact: added a public thank-you page at `/messages/thank-you/` so people who finish the volunteer or student application can book an interview, and pointed the existing Sheltermanager completion URL to that page.
- Operational note: preview the site with `scripts/preview-php-source-site.ps1`, run `scripts/validate-public-site.ps1` before staging, use `scripts/export-static-site.php` only for optional non-canonical static exports, and package only the `public/` bundle for Cloudron upload.

<p align="center">
  <a href="https://www.apes.org.uk/" target="_blank" rel="noopener noreferrer">
    <img src="https://www.apes.org.uk/APES_logo_3D_440x250.png" alt="APES CIC Logo" width="220">
  </a>
</p>

<h1 align="center">APES CIC Website</h1>

<p align="center">
  <strong>Public-facing organisational website for APES CIC services, welfare information, rescue pathways and community engagement.</strong>
</p>

<p align="center">
  <a href="https://www.apes.org.uk/"><img alt="Website" src="https://img.shields.io/badge/www.apes.org.uk-live-1B5E20"></a>
  <img alt="Status" src="https://img.shields.io/badge/status-public_website-2E7D32">
  <img alt="Theme" src="https://img.shields.io/badge/theme-APES_Habitat-43A047">
  <img alt="Accessibility" src="https://img.shields.io/badge/accessibility-WCAG_AA_target-00796B">
</p>

---

## 🌿 Project purpose

The **APES CIC Website** repository supports the development, maintenance and continuous improvement of **[www.apes.org.uk](https://www.apes.org.uk/)**, the main public website for the **Association of Protecting Exotic Species CIC (APES CIC)**.

The website exists to give animal keepers, adopters, foster carers, volunteers, supporters, service users, partners and members of the public clear access to APES CIC information, services, welfare guidance and support pathways.

Primary goals:

- Improve public access to APES CIC services, rescue routes, support pathways and contact routes.
- Support exotic animal welfare, rescue, rehabilitation, responsible rehoming and responsible ownership education.
- Keep public information accurate, accessible, welfare-led and consistent with APES CIC values.
- Maintain clear links between the website and connected APES services, platforms and departments.
- Support high standards for accessibility, data protection, security, safeguarding and UK compliance.
- Provide a structured place to track website bugs, content updates, feature requests and public-facing service improvements.

---

## 🐾 About APES CIC

**Association of Protecting Exotic Species CIC**, also known as **APES CIC**, is a UK Community Interest Company focused on the welfare, protection, rescue, rehabilitation and responsible rehoming of exotic species.

APES CIC supports exotic animals, their keepers and the wider public through welfare-led services, education, advice, rescue pathways and responsible ownership support.

The organisation's work includes:

- Exotic animal rescue.
- Rehabilitation and responsible rehoming.
- Responsible ownership education.
- Welfare guidance and public information.
- Shelter and rescue services.
- Pet care support.
- Adoption and fostering pathways.
- Conservation and welfare awareness.
- Community-focused animal welfare services.

---

## 🧭 Website areas

The website should develop around clear public journeys and task-led service areas rather than generic pages.

| Area | Purpose |
|---|---|
| **Home** | Public entry point, service highlights, urgent notices and key calls to action. |
| **About APES CIC** | Mission, values, public benefit, governance summary and organisational background. |
| **Rescue, Rehabilitate and Rehome** | Rescue pathways, rehabilitation information, rehoming principles and welfare-led guidance. |
| **Shelter and Rescue** | Shelter information, animal availability, adoption, fostering and surrender guidance. |
| **Pet Care** | Public pet care support, clinic information, advice, bookings and service resources. |
| **Adoption and Fostering** | Eligibility, process, expectations, application routes and responsible placement information. |
| **Advice and Guidance** | Exotic animal care guidance, responsible ownership resources and public education content. |
| **News and Updates** | Announcements, rescue updates, campaign news, community updates and public notices. |
| **Donations and Fundraising** | Fundraising routes, donation information, campaign calls to action and supporter journeys. |
| **Volunteering** | Volunteering opportunities, onboarding routes and community involvement. |
| **Policies and Terms** | Public policies, privacy information, complaints routes, terms and legal notices. |
| **Contact and Support** | Contact routes, helpdesk access, service user pathways and linked APES platforms. |

---

## 🔗 Connected APES services

The website may link to or support access to wider APES services and platforms, including:

- APES Shelter and Rescue.
- APES Pet Care Clinic.
- APES CareBase.
- APES News.
- MyAPES Portal.
- APES Workspace.
- APES Network Status.
- APES support and helpdesk systems.

Any change affecting linked services must be checked carefully so users are directed to the correct platform and are not given outdated, confusing or unsafe instructions.

---

## 🎨 APES Habitat design direction

The website should use a **green-led APES Habitat theme**: public-friendly, accessible, welfare-focused and recognisably APES.

### Brand principles

- **Mission-led:** connect major features back to animal welfare, rescue, rehabilitation, education, public benefit or governance.
- **Friendly and colourful:** use habitat-inspired shapes, animal-welfare cues, rounded cards and clear service colour bands.
- **Publicly clear:** design must make rescue routes, service eligibility, contact routes and support pathways easy to understand.
- **Accessible by default:** colour contrast, keyboard navigation, readable typography and plain-language labels are mandatory.
- **Reusable:** use shared tokens, components and templates so new pages remain consistent.

### Suggested colour tokens

| Token | Suggested use |
|---|---|
| **Forest Green** | Primary navigation, headers, governance and high-trust areas. |
| **Leaf Green** | Positive states, active indicators, welfare updates and primary buttons. |
| **Mint** | Soft backgrounds, low-pressure information panels and content cards. |
| **Teal** | Shelter, rescue and operational cards. |
| **Sky Blue** | Pet care, guidance, help and knowledge-base content. |
| **Purple** | People, volunteering, training and community content. |
| **Coral / Orange** | Calls to action, campaign banners and non-critical warnings. |
| **Warm Sand** | Calm page background accents and break sections. |

---

## 🧩 Recommended repository structure

The exact structure may vary by framework, but the repository should stay easy to navigate.

```text
.
├── .github/
│   ├── ISSUE_TEMPLATE/
│   └── pull_request_template.md
├── docs/
│   ├── accessibility/
│   ├── architecture/
│   ├── brand/
│   ├── compliance/
│   └── decisions/
├── public/
│   └── assets/
│       └── logo/
├── src/
│   ├── components/
│   ├── config/
│   ├── features/
│   ├── layouts/
│   ├── pages/
│   ├── services/
│   ├── styles/
│   └── utils/
├── tests/
│   ├── accessibility/
│   ├── integration/
│   └── unit/
├── CHANGELOG.md
├── CONTRIBUTING.md
├── README.md
└── SECURITY.md
```

### Current public-site structure

This repository serves the production website from `public/` as a PHP-first site under Apache-compatible Cloudron hosting. Public routes render through one shared PHP source of truth and one shared theme asset layer.

```text
dev/
└── router.php           # Local PHP preview parity
scripts/
├── ApesPhpTools.ps1
├── export-static-site.php
├── preview-php-source-site.ps1
├── validate-public-site.ps1
├── stage-cloudron-public.ps1
└── package-cloudron-public.ps1
public/
├── index.php            # Public front controller
├── includes/            # Shared page data and rendering partials
├── theme/               # Shared CSS and JS runtime
├── assets/              # Shared public media, favicons and logos
├── 403.html             # Apache fallback error document
├── 404.html             # Apache fallback error document
├── 500.html             # Apache fallback error document
└── robots.txt / sitemap.xml / site.webmanifest
```

---

## 🚀 Getting started

### 1. Clone the repository

```bash
git clone https://github.com/APESCIC/www.apes.org.uk.git
cd www.apes.org.uk
```

### 2. Preview the PHP source site locally

If you prefer a terminal command, use:

```bash
powershell -ExecutionPolicy Bypass -File .\scripts\preview-php-source-site.ps1
```

HTTP preview remains the preferred validation path because it matches the deployed `public/` web-root behavior. Do not rely on `file://` preview for acceptance checks.

### 3. Optional static export for offline review or handoff

```bash
php .\scripts\export-static-site.php
```

On Windows, you can also use:

```bash
powershell -ExecutionPolicy Bypass -File .\scripts\export-static-site.ps1
```

This export writes to `dist/static-export` by default and is not the canonical website source. Do not copy it back into `public/`.

### 4. Run checks before staging or committing

```bash
powershell -ExecutionPolicy Bypass -File .\scripts\validate-public-site.ps1
```

### 5. Stage or package the Cloudron public bundle

```bash
powershell -ExecutionPolicy Bypass -File .\scripts\stage-cloudron-public.ps1
powershell -ExecutionPolicy Bypass -File .\scripts\package-cloudron-public.ps1
```

---

## 🔐 Environment variables

Do not commit secrets, credentials, API tokens or live service keys.

Document required variables in `.env.example` using safe placeholder values only.

| Variable | Purpose | Required | Example |
|---|---|---:|---|
| `APP_ENV` | Application environment. | Yes | `local` |
| `APP_URL` | Local or deployed application URL. | Yes | `http://localhost:3000` |
| `PUBLIC_SITE_URL` | Public website URL. | Yes | `https://www.apes.org.uk` |
| `CONTACT_CENTRE_URL` | APES contact centre base URL. | If used | `https://contact.apes.org.uk` |
| `DONATION_PLATFORM_URL` | Donation or fundraising platform URL. | If used | `https://www.apes.org.uk/donate` |
| `AUTH_PROVIDER_URL` | Authentication provider endpoint for protected admin routes. | If used | `https://auth.example.local` |
| `DATABASE_URL` | Database connection string. | If used | `postgres://user:pass@localhost:5432/website` |

---

## 🛠 Development workflow

### Branch naming

Use short, descriptive branch names.

```text
feature/homepage-service-cards
feature/rescue-pathway-form
fix/broken-contact-link
fix/mobile-navigation-overflow
content/update-adoption-guidance
policy/privacy-page-update
accessibility/main-navigation-focus
security/remove-exposed-data
seo/update-homepage-metadata
```

### Commit style

Use clear commit messages that describe the change and the reason.

```text
Fix broken link on contact page
Update APES mission statement wording
Improve mobile layout on home page
Add volunteering information
Correct CIC details in footer
Update donation call-to-action
Improve accessibility of navigation menu
```

### Pull requests

Every pull request should include:

- Summary of the change.
- Reason for the change.
- Affected page, service or section.
- Screenshots or screen recordings for UI changes.
- Accessibility considerations.
- Security and data protection considerations.
- Testing completed.
- Any content, policy, legal, safeguarding, welfare or senior review required.
- Rollback notes where relevant.

Before requesting review, check:

- The affected page loads correctly.
- Links work as expected.
- Forms behave correctly.
- Content is accurate and written in UK English.
- Mobile layout has been checked.
- Accessibility has been considered.
- No personal data has been committed.
- No internal-only information has been published.
- No credentials, tokens or API keys are included.
- Any legal, policy or service wording has been reviewed where required.

---

## ✅ Definition of done

A website change is not ready to merge until it meets the relevant checklist.

### Functional quality

- [ ] Change meets the agreed acceptance criteria.
- [ ] User-facing copy is clear, accurate and written in UK English.
- [ ] Empty, loading, success and error states are handled where relevant.
- [ ] Mobile and desktop layouts have been checked.
- [ ] Links, buttons and forms behave as expected.
- [ ] Public calls to action route users to the correct APES service or support pathway.

### Accessibility

- [ ] Normal text meets at least **4.5:1** contrast.
- [ ] Large text and meaningful graphical elements meet at least **3:1** contrast where applicable.
- [ ] Colour is not the only method used to show meaning, status or urgency.
- [ ] Keyboard focus is visible and logical.
- [ ] Form fields have labels, helper text and error messages.
- [ ] Images and icons have suitable accessible names or alternative text.
- [ ] Motion is minimal and respects reduced-motion preferences.
- [ ] Headings, landmarks and link text support clear navigation.

### Content and public information

- [ ] Service descriptions are accurate and not misleading.
- [ ] Eligibility, availability, opening times and contact routes are current where shown.
- [ ] Public animal welfare advice is approved, proportionate and clearly worded.
- [ ] Legal, safeguarding, complaints, donation, surrender, adoption and fostering wording has been reviewed where required.
- [ ] Pages avoid internal-only operational details and confidential case information.
- [ ] Public guidance avoids unverified claims and ambiguous welfare advice.

### Data protection and security

- [ ] No secrets, personal data or confidential records are committed.
- [ ] Forms collect only necessary information.
- [ ] Service user, adopter, foster carer, volunteer, staff and animal case information is treated as sensitive.
- [ ] Logs do not expose personal data or confidential case details.
- [ ] Security-sensitive details are not published in public issues or public content.

### Governance and compliance

- [ ] Policy, legal, finance, fundraising, HR or governance content has an identified owner.
- [ ] Review dates are included where appropriate.
- [ ] Legal, safeguarding or data protection changes have been reviewed by the appropriate lead.
- [ ] Changes align with APES CIC operational standards, public benefit objectives and UK compliance expectations.

---

## 🧪 Testing expectations

Use the strongest practical test coverage for the type of change.

| Change type | Expected checks |
|---|---|
| UI component | Unit tests, keyboard check, responsive check, contrast check. |
| Form or workflow | Validation tests, error-state tests, success-state tests, role/access check. |
| Content update | Link check, spelling check, owner/review-date check. |
| Navigation update | Link check, keyboard check, mobile menu check and active-state check. |
| Donation or fundraising content | Link check, wording review, policy review and payment route check. |
| Authentication or protected route | Access tests, failed-login behaviour, role boundary checks. |
| Data handling | Input validation, output escaping, logging review and retention considerations. |
| SEO or metadata | Metadata check, structured data check where relevant and search preview review. |

---

## 🧯 Priority issue types

Use the correct issue template so triage is faster.

| Issue type | Use when |
|---|---|
| **Bug** | Something is broken or behaving unexpectedly. |
| **Feature Request** | A new website function, service page or public journey is needed. |
| **Content Update** | The change is primarily wording, guidance, links, service details or document structure. |
| **Accessibility** | The change relates to usability, assistive technology, keyboard use, contrast or inclusive access. |
| **Compliance / Governance** | The change relates to policy, statutory details, public legal wording, governance, finance or audit. |
| **Security / Privacy** | The change affects access control, personal data, logs, authentication or confidential information. |
| **SEO / Performance** | The change affects search visibility, metadata, page speed or technical performance. |
| **Urgent Fix** | The issue affects public safety, service access, public trust or urgent welfare/support routes. |

---

## 🧱 Component standards

Reusable components should be preferred over one-off layouts.

Recommended component families:

- Homepage hero sections.
- Service cards.
- Quick action buttons.
- Alert banners.
- Animal profile cards.
- Adoption and fostering cards.
- Donation and campaign panels.
- News and update cards.
- Policy/document lists.
- Status chips.
- Form fields and validation summaries.
- Timeline and review-date panels.
- Empty states with APES-themed illustrations.

Component requirements:

- Use shared design tokens.
- Support keyboard interaction.
- Avoid hard-coded colours where a token exists.
- Include accessible names for icons and controls.
- Work on mobile, tablet and desktop widths.
- Document important props, variants and usage constraints.
- Make public calls to action clear, accurate and non-misleading.

---

## 📚 Documentation standards

Keep documentation close to the code and update it in the same pull request as the relevant change.

Recommended documents:

| Document | Purpose |
|---|---|
| `docs/architecture/overview.md` | System overview, major services and integration points. |
| `docs/brand/theme-guide.md` | APES Habitat theme, logo usage, colour tokens and component rules. |
| `docs/accessibility/checklist.md` | Accessibility testing workflow and acceptance checks. |
| `docs/compliance/data-protection.md` | Data handling, privacy and retention notes. |
| `docs/compliance/public-content-review.md` | Review workflow for legal, welfare, safeguarding, donation and policy wording. |
| `docs/decisions/` | Architecture decision records and reasoning. |
| `SECURITY.md` | How to report security concerns safely. |
| `CONTRIBUTING.md` | Contributor expectations and development workflow. |

---

## 🛡 Security and responsible disclosure

Do not open public issues containing:

- Credentials, API keys or tokens.
- Personal data.
- Safeguarding details.
- Animal welfare case details.
- Vulnerability exploit steps that could create immediate risk.
- HR, finance, governance or legal correspondence.
- Internal-only operational information.

Report sensitive concerns through the approved APES internal route or by contacting the responsible lead directly.

For repository security guidance, maintain a separate `SECURITY.md` file.

---

## 🧑‍🤝‍🧑 Contributors and access

This repository is intended for authorised APES CIC directors, staff, volunteers, contractors and approved collaborators.

Access should follow least-privilege principles:

- Give users the minimum role needed for their work.
- Remove access when a role ends or no longer requires repository access.
- Protect the default branch.
- Require pull request review for material changes.
- Treat welfare, safeguarding, governance, HR and finance information as sensitive by default.
- Do not publish internal-only operational information to the public website.

---

## 📈 Roadmap themes

Current development should prioritise:

1. **Theme foundation:** APES Habitat palette, typography, layout tokens and component rules.
2. **Homepage clarity:** welcome hero, urgent notices, clear calls to action, service cards and public search/signposting.
3. **Service journeys:** rescue, surrender, adoption, fostering, volunteering, donations, pet care and support routes.
4. **Public guidance:** clearer welfare advice, responsible ownership education and accessible service information.
5. **Forms and workflows:** clearer labels, progress states, confirmation pages and mobile checks.
6. **Trust and transparency:** CIC information, policies, complaints routes, privacy information and public benefit messaging.
7. **Launch quality:** accessibility review, mobile review, broken-link checks, metadata review and deployment checklist.

---

## 📏 Success measures

The website should be judged by practical public outcomes.

| Measure | Target |
|---|---|
| Homepage clarity | Users can find common service routes within 10 seconds. |
| Rescue pathway clarity | Users can identify the correct support route without confusion. |
| Adoption and fostering clarity | Users understand eligibility, expectations and next steps. |
| Contact reduction | Fewer repeated “where is...” and “how do I...” requests. |
| Compliance visibility | Key public policies show owner, status and review date where appropriate. |
| Accessibility | Core pages pass WCAG AA contrast checks. |
| Consistency | Major service pages follow the same theme and component rules. |
| Mobile usability | Core public journeys are usable on phones. |

---

## 🏢 Organisation

**Association of Protecting Exotic Species CIC (APES CIC)**  
CIC No: `16253848`  
Registered Office: `40 Morris Street, St Helens, Merseyside, WA9 3EN`  
Website: <https://www.apes.org.uk/>  
Contact Centre: <https://contact.apes.org.uk/>  
Telephone: `01744 374 015`

---

## 📄 Licence and reuse

This repository and its contents are maintained for APES CIC public website development and service delivery.

Unless a separate licence file states otherwise, do not reuse APES CIC branding, written materials, graphics, source code, operational workflows or service materials outside authorised APES CIC purposes.

---

## ⚠️ Disclaimer

This repository supports the development of the APES CIC website. It should not be used as a substitute for approved APES policies, internal procedures, legal guidance, veterinary advice, safeguarding advice or formal animal welfare decision-making.

---

<p align="center">
  <strong>Built to support people, protect animals and keep APES services clear, compliant and accessible.</strong>
</p>
