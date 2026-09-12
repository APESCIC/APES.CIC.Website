# APES CIC public site inventory

- Crawl date: `2026-06-03`
- Source domain: `https://www.apes.org.uk`
- Crawl method: live public inspection, repository route-map verification from `public/includes/site-data.php`, and PHP-first cleanup review against the maintained public route set
- Runtime model: shared PHP source of truth with Apache-compatible `public/` web root

## Internal route inventory

| Route | Live or rebuilt title | Main purpose | Primary CTA or journey | External service or form | Status or review note |
| --- | --- | --- | --- | --- | --- |
| `/` | Association of Protecting Exotic Species CIC | Parent APES gateway and public service overview | Donate, Newsroom, service discovery | APES Newsroom, Shelter & Rescue, Pet Care Clinic | Rebuilt |
| `/index` | Homepage alias | Legacy entry point for homepage | Redirect to canonical home | None | Redirected to `/` |
| `/help-us-move` | Help Us Move | Relocation and continuity appeal | Donate, follow updates | APES Newsroom | Rebuilt |
| `/apes-pet-shop` | APES Pet Shop | Shop landing page and fulfilment guidance | Visit online shop | APES Shop | Rebuilt, placeholder gallery issue documented |
| `/pet-boarding` | APES pet boarding services | Boarding service overview and pricing | Open boarding portal | PetManager | Rebuilt |
| `/services/lost-n-found-pets` | Lost and found pets | Lost and found pet guidance | Open reporting forms | Shelter Manager | Rebuilt |
| `/24-7-services` | 24/7 services | Out-of-hours support and community routes | Contact support channels | Discord and related external community routes | Rebuilt |
| `/services/animal-therapy` | APES animal therapy programme | Welfare and therapy programme summary | Contact APES | Contact centre | Rebuilt |
| `/educational-visits` | Educational visits | Outreach and education service page | Make an enquiry | Zoho public forms or contact centre | Rebuilt |
| `/donate` | Donate | Main public donation page | Donate now | APES donor route | Rebuilt |
| `/donating/fundraising` | Fundraising | Current fundraising priorities | Support a campaign | APES donor route | Rebuilt |
| `/animal-causes` | Animal causes | Cause-led welfare and conservation messaging | Donate or support APES | APES donor route | Rebuilt |
| `/mailing-lists` | Mailing lists | Newsletter and updates signposting | Join a list | External sign-up routes | Rebuilt |
| `/enterprise-mailing-list` | Enterprise mailing list | Partner and enterprise sign-up | Register interest | Zoho public form | Rebuilt |
| `/volunteer` | Volunteer and student placements | Volunteer and placement recruitment information | Register interest | Shelter Manager volunteer and student application form | Rebuilt, editorial review still useful |
| `/messages/vol-stu-application-thank-you/` | Thank you for your application | Post-application thank-you and interview booking | Book an interview | Outlook APES Bookings | Added for Sheltermanager completion redirect |
| `/mission/our-main-mission-statement` | Our main mission statement | Public mission and values page | Learn about APES | None | Rebuilt |
| `/mission/support-ethical-rehabilitation` | Support ethical exotic animal rehabilitation | Rehabilitation and welfare philosophy | Donate or contact APES | Contact centre | Rebuilt |
| `/the-center` | The centre | Location walkthrough and facilities overview | Contact APES | Contact centre | Rebuilt, unfinished live captions documented |
| `/bookings` | Bookings | Booking routes and contact guidance | Contact APES or use external tools | Contact centre, PetManager | Rebuilt |
| `/opening-times` | Opening times | Public opening-hours guidance | Contact APES | Contact centre | Rebuilt, live copy inconsistency documented |
| `/sponsors` | Sponsors | Sponsor recognition and partnership framing | Support APES | None | Rebuilt |
| `/about-us` | About APES | Organisation overview, history and purpose | Contact APES, Donate | None | Rebuilt, spelling inconsistencies documented |
| `/socials` | Socials | Public social and media route hub | Open APES Social or YouTube | APES Social, YouTube | Rebuilt |
| `/apes-communities` | APES communities | Public community route hub | Join Discord, open APES Social | Discord, APES Social, MyAPES | Rebuilt |
| `/staff` | Staff and volunteers | Public-facing team and volunteer framing | Read about APES, Volunteer | None | Rebuilt |
| `/contact-centre` | Contact centre | Bridge page to external support platform | Open contact centre | Contact centre, Help centre | Rebuilt |
| `/policies/terms-of-service` | Terms of service | Website/service terms | Read policy | None | Rebuilt |
| `/policies/privacy` | Privacy Policy | Public privacy guidance | Read policy | None | Rebuilt |
| `/policies/adoption-policy` | Adoption Policy | Adoption conditions | Read policy | None | Rebuilt, source completeness review needed |
| `/policies/re-homing-policy` | Re-Homing Policy | Re-homing conditions | Read policy | None | Rebuilt |
| `/policies/euthanasia-policy` | Euthanasia Policy | Sensitive policy page | Read policy | None | Rebuilt, live heading issue documented |
| `/policies/cookies` | Cookie guidance | Cookie and browser guidance | Read policy | None | Rebuilt, source wording review needed |
| `/contact` | Contact APES | Main support route | Open contact centre | Contact centre, Help centre | Rebuilt |
| `/search` | Search | Public site search | Search routes and content | None | Rebuilt |
| `/news/` | News | Main-site APES Newsroom handoff | Open APES Newsroom | APES Newsroom | Rebuilt as handoff page |
| `/news/post/Introducing-the-new-APES-CareBase/` | Legacy APES CareBase route | Redirect to APES Newsroom successor page | Redirect only | APES Newsroom | Redirected |
| `/news/post/Urgent-APES-Must-Relocate-by-3-March-2026/` | Legacy relocation route | Redirect to APES Newsroom successor page | Redirect only | APES Newsroom | Redirected |
| `/news/post/APES-Partners-with-Double-the-Donation-to-Double-Your-Donation-Impact/` | Legacy donor-route news | Redirect to APES Newsroom successor page | Redirect only | APES Newsroom | Redirected |
| `/news/post/important-update-temporary-move-what-it-means-for-you/` | Legacy continuity route | Redirect to APES Newsroom successor page | Redirect only | APES Newsroom | Redirected |
| `/news/post/fundraising-appeal-help-apes-invest-in-essential-welfare-management-software/` | Legacy fundraising software route | Redirect to APES Newsroom successor page | Redirect only | APES Newsroom | Redirected |
| `/news/tag/moving-properties/` | Legacy moving-properties tag | Redirect to APES Newsroom successor page | Redirect only | APES Newsroom | Redirected |
| `/news/tag/funds/` | Legacy funds tag | Redirect to APES Newsroom successor page | Redirect only | APES Newsroom | Redirected |

## Rebuild-only governance route

| Route | Title | Purpose | Status |
| --- | --- | --- | --- |
| `/change-log-hub/` | Change Log Hub | APES-required public release record linked from the footer | Added in rebuild |

## Important CTAs observed or preserved

- Donate
- Contact APES
- Volunteer
- Book boarding
- Report a lost or found pet
- Book an educational visit
- Read APES Newsroom
- Join mailing lists

## Forms, widgets and embeds detected

- Shelter Manager lost or found pet reporting forms
- PetManager boarding journey
- Zoho public enterprise mailing-list form
- APES donor route and donation handling
- Contact centre and Help centre support flows
- Social and community outbound links such as Discord and YouTube

## Public assets and component patterns observed

- APES logo and partner-linked service badges
- Hero banners and service panels across core routes
- Repeated product-gallery placeholders on the Pet Shop page
- Footer route clusters for donation, policies, contact and connected services

## Scripts or platform-generated behaviour observed

- Legacy generated navigation/auth clutter such as Sign In, Sign Up and Sign Out links
- External-form and external-service journeys rather than locally hosted transactional tools
- Legacy main-site news cards and article routing removed from the shared page model in favour of APES Newsroom redirects

## Pages requiring APES review before stable launch

- APES Pet Shop
- About APES
- The centre
- Opening times
- Adoption Policy
- Cookie guidance
- Euthanasia Policy presentation

## Routes intentionally kept external

- `https://www.apesshelter.org.uk/`
- `https://www.apespetcare.org.uk/`
- `https://www.apesshop.co.uk/`
- `https://www.myapes.me.uk/`
- `https://carebase.apes.org.uk/`
- `https://help.apes.org.uk/`
- `https://contact.apes.org.uk/`
- `https://service.sheltermanager.com/`
- `https://petmanager.app/`
- `https://forms.zohopublic.eu/`
- `https://www.apesnews.org.uk/`
