<?php
declare(strict_types=1);

const APES_FALLBACK_VERSION = 'v4.0.1b';
const APES_SITE_NAME = 'Association of Protecting Exotic Species CIC';
const APES_CIC_NUMBER = '16253848';
const APES_CONTACT_EMAIL = 'info@apes.org.uk';
const APES_CONTACT_PHONE = '01744 374 015';
const APES_CONTACT_PHONE_TEL = '01744374015';
const APES_POSTAL_ADDRESS_DISPLAY = '40 Morris Street, St Helens, Merseyside, WA9 3EN';
const APES_PRIMARY_DOMAIN = 'https://www.apes.org.uk';
const APES_NEWSROOM_URL = 'https://www.apesnews.org.uk/';

function apes_newsroom_redirects(): array
{
    return [
        '/news/post/Introducing-the-new-APES-CareBase/' => 'https://www.apesnews.org.uk/introducing-the-new-myapes-manage-your-details-online/',
        '/news/post/Urgent-APES-Must-Relocate-by-3-March-2026/' => 'https://www.apesnews.org.uk/tag/apes-cic/',
        '/news/post/APES-Partners-with-Double-the-Donation-to-Double-Your-Donation-Impact/' => 'https://www.apesnews.org.uk/tag/apes-donor-community/',
        '/news/post/important-update-temporary-move-what-it-means-for-you/' => 'https://www.apesnews.org.uk/tag/apes-cic/',
        '/news/post/fundraising-appeal-help-apes-invest-in-essential-welfare-management-software/' => 'https://www.apesnews.org.uk/tag/apes-donor-community/',
        '/news/tag/moving-properties/' => 'https://www.apesnews.org.uk/tag/apes-cic/',
        '/news/tag/funds/' => 'https://www.apesnews.org.uk/tag/apes-donor-community/',
    ];
}

function apes_error_pages(): array
{
    return [
        'error-403' => [
            'route' => '/403.html',
            'meta_title' => 'Access denied | APES CIC',
            'title' => 'Access denied',
            'description' => 'You do not have permission to open that APES page.',
            'robots' => 'noindex, nofollow',
            'http_status' => 403,
            'hero_kicker' => '403',
            'hero_title' => 'You do not have access to that page.',
            'hero_summary' => 'If you expected this page to be public, please return to the main website or contact APES for help.',
            'hero_actions' => [
                ['label' => 'Go to homepage', 'href' => '/', 'variant' => 'primary'],
                ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'secondary'],
            ],
            'pills' => ['Access denied', 'Noindex'],
            'body_html' => <<<'HTML'
<section class="section-shell">
  <p>This route is not available to the public. Use the main navigation, site search or the APES contact routes if you need help finding a public page.</p>
</section>
HTML,
            'related_links' => [
                ['label' => 'Homepage', 'href' => '/'],
                ['label' => 'Search the site', 'href' => '/search/'],
            ],
            'exclude_from_search' => true,
        ],
        'error-404' => [
            'route' => '/404.html',
            'meta_title' => 'Page not found | APES CIC',
            'title' => 'Page not found',
            'description' => 'The APES page you tried to reach could not be found.',
            'robots' => 'noindex, nofollow',
            'http_status' => 404,
            'hero_kicker' => '404',
            'hero_title' => 'The page you requested could not be found.',
            'hero_summary' => 'Please use the main navigation, site search or contact route to continue.',
            'hero_actions' => [
                ['label' => 'Go to homepage', 'href' => '/', 'variant' => 'primary'],
                ['label' => 'Search the site', 'href' => '/search/', 'variant' => 'secondary'],
            ],
            'pills' => ['Not found', 'Noindex'],
            'body_html' => <<<'HTML'
<section class="section-shell">
  <p>The requested page may have moved as part of the APES website launch. Legacy routes are being redirected intentionally where possible.</p>
</section>
HTML,
            'related_links' => [
                ['label' => 'Homepage', 'href' => '/'],
                ['label' => 'Contact APES', 'href' => '/contact/'],
            ],
            'exclude_from_search' => true,
        ],
        'error-500' => [
            'route' => '/500.html',
            'meta_title' => 'Temporary website problem | APES CIC',
            'title' => 'Temporary website problem',
            'description' => 'APES is having trouble loading that page right now.',
            'robots' => 'noindex, nofollow',
            'http_status' => 500,
            'hero_kicker' => '500',
            'hero_title' => 'Something went wrong while loading this page.',
            'hero_summary' => 'Please try again shortly. If the problem continues, use the APES contact routes so the team can help.',
            'hero_actions' => [
                ['label' => 'Return to homepage', 'href' => '/', 'variant' => 'primary'],
                ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'secondary'],
            ],
            'pills' => ['Temporary problem', 'Noindex'],
            'body_html' => <<<'HTML'
<section class="section-shell">
  <p>This is a temporary website problem rather than a missing page. Please try again shortly or contact APES if you need urgent support.</p>
</section>
HTML,
            'related_links' => [
                ['label' => 'Homepage', 'href' => '/'],
                ['label' => 'Help Centre', 'href' => 'https://help.apes.org.uk/', 'external' => true],
            ],
            'exclude_from_search' => true,
        ],
    ];
}

function apes_page_robots(array $page): string
{
    return trim((string) ($page['robots'] ?? 'index, follow, max-image-preview:large'));
}

function apes_page_is_indexable(array $page): bool
{
    $robots = strtolower(apes_page_robots($page));
    $httpStatus = (int) ($page['http_status'] ?? 200);

    return !str_contains($robots, 'noindex') && $httpStatus < 400;
}

function apes_render_contact_email_link(string $class = ''): string
{
    $email = htmlspecialchars(APES_CONTACT_EMAIL, ENT_QUOTES);
    $classAttr = $class === '' ? '' : ' class="' . htmlspecialchars($class, ENT_QUOTES) . '"';

    return '<a' . $classAttr . ' href="mailto:' . $email . '" rel="nofollow">' . $email . '</a>';
}

function apes_render_contact_phone_link(string $class = ''): string
{
    $display = htmlspecialchars(APES_CONTACT_PHONE, ENT_QUOTES);
    $tel = htmlspecialchars(APES_CONTACT_PHONE_TEL, ENT_QUOTES);
    $classAttr = $class === '' ? '' : ' class="' . htmlspecialchars($class, ENT_QUOTES) . '"';

    return '<a' . $classAttr . ' href="tel:' . $tel . '">' . $display . '</a>';
}

function apes_version(): string
{
    static $version;

    if ($version !== null) {
        return $version;
    }

    $versionFile = dirname(__DIR__, 2) . '/VERSION';

    if (is_file($versionFile)) {
        $fileVersion = trim((string) file_get_contents($versionFile));

        if ($fileVersion !== '') {
            $version = $fileVersion;

            return $version;
        }
    }

    $version = APES_FALLBACK_VERSION;

    return $version;
}

function apes_site_data(): array
{
    static $data;

    if ($data !== null) {
        return $data;
    }

    $year = (int) date('Y');
    $siteVersion = apes_version();
    $newsroom_copy = 'News and updates from APES CIC and our services are now published through APES Newsroom. Visit APES Newsroom to read the latest organisation updates, Shelter & Rescue news, Pet Care Clinic updates, service notices, appeals and guidance from across the APES network.';

    $data = [
        'version' => $siteVersion,
        'site_name' => APES_SITE_NAME,
        'site_short_name' => 'APES CIC',
        'cic_number' => APES_CIC_NUMBER,
        'contact_email' => APES_CONTACT_EMAIL,
        'contact_phone' => APES_CONTACT_PHONE,
        'contact_phone_display' => APES_CONTACT_PHONE,
        'contact_phone_tel' => APES_CONTACT_PHONE_TEL,
        'postal_address' => [
            '40 Morris Street',
            'St Helens',
            'Merseyside',
            'WA9 3EN',
            'United Kingdom',
        ],
        'postal_address_display' => APES_POSTAL_ADDRESS_DISPLAY,
        'registered_address' => APES_POSTAL_ADDRESS_DISPLAY,
        'year' => $year,
        'canonical_domain' => APES_PRIMARY_DOMAIN,
        'development_notice' => [
            'header_enabled' => true,
            'popup_enabled' => true,
            'header_message' => 'This new site is under development. Some features and buttons may not work yet. Need help? Use live chat.',
            'popup_message' => 'This new site is under development, so some features and buttons may not work yet. If you need help, please use live chat on the website.',
            'live_chat_label' => 'Open live chat',
            'fallback_href' => '/contact/',
            'storage_key' => 'apesDevelopmentNoticeDismissed',
        ],
        'brand' => [
            'logo_nav_png' => '/assets/logos/apes-logo-navbar-72h.png',
            'logo_nav_webp' => '/assets/logos/apes-logo-navbar-72h.webp',
            'logo_feature_png' => '/assets/logos/apes-logo-primary-trimmed-640w.png',
            'logo_feature_webp' => '/assets/logos/apes-logo-primary-trimmed-640w.webp',
            'og_image' => '/assets/logos/apes-og-image-1200x630.jpg',
            'twitter_image' => '/assets/logos/apes-twitter-card-1200x600.jpg',
            'subtitle' => 'Rescue, rehabilitation, rehoming and public support across the APES network.',
        ],
        'feature_media' => [
            'homepage-hero' => [
                'png' => '/assets/images/apes/apes-homepage-hero.png',
                'webp' => '/assets/images/apes/apes-homepage-hero.webp',
                'alt' => 'APES staff member preparing a reptile habitat while a family speaks with a team member in a calm learning space.',
                'width' => 1672,
                'height' => 941,
            ],
            'route-finder-illustration' => [
                'png' => '/assets/images/apes/apes-route-finder-illustration.png',
                'webp' => '/assets/images/apes/apes-route-finder-illustration.webp',
                'alt' => 'Illustrated service-routing diagram showing APES support journeys from first contact through practical welfare outcomes.',
                'width' => 1672,
                'height' => 941,
            ],
            'rescue-rehab-rehome' => [
                'png' => '/assets/images/apes/apes-rescue-rehab-rehome.png',
                'webp' => '/assets/images/apes/apes-rescue-rehab-rehome.webp',
                'alt' => 'APES team members handling reptile-care equipment and speaking through a welfare handover in a calm indoor setting.',
                'width' => 1672,
                'height' => 941,
            ],
            'education-care' => [
                'png' => '/assets/images/apes/apes-education-care.png',
                'webp' => '/assets/images/apes/apes-education-care.webp',
                'alt' => 'Educator guiding a reptile-care session around a habitat while attendees observe from a respectful distance.',
                'width' => 1672,
                'height' => 941,
            ],
            'fundraising-priorities' => [
                'png' => '/assets/images/apes/apes-fundraising-priorities.png',
                'webp' => '/assets/images/apes/apes-fundraising-priorities.webp',
                'alt' => 'Operational table showing scales, tablet, payment device, food containers, tools and care items that support daily APES welfare work.',
                'width' => 1672,
                'height' => 941,
            ],
            'relocation-continuity' => [
                'png' => '/assets/images/apes/apes-relocation-continuity.png',
                'webp' => '/assets/images/apes/apes-relocation-continuity.webp',
                'alt' => 'APES team members moving covered habitats and care containers between a vehicle and an indoor setup space.',
                'width' => 1672,
                'height' => 941,
            ],
        ],
        'feature_sections' => [
            'about-three-rs' => [
                'image' => 'rescue-rehab-rehome',
                'eyebrow' => 'Three Rs in practice',
                'title' => 'Rescue, rehabilitation and rehoming depend on calm, welfare-first handling.',
                'summary' => 'This supporting image reinforces the public explanation of the three Rs without implying a specific current APES case or outcome.',
            ],
            'services-care-education' => [
                'image' => 'education-care',
                'eyebrow' => 'Care and education routes',
                'title' => 'Care, learning and public support sit alongside the route finder.',
                'summary' => 'Use the Services hub when you need the right mix of care guidance, educational support, bookings and connected APES service routes.',
            ],
            'bookings-care' => [
                'image' => 'education-care',
                'eyebrow' => 'Booking support',
                'title' => 'Bookings work best when visitors understand the care setting behind the route.',
                'summary' => 'This image supports the bookings page by showing a calm welfare-led environment rather than implying a confirmed appointment or named service user.',
            ],
            'educational-visits-care' => [
                'image' => 'education-care',
                'eyebrow' => 'Welfare-led outreach',
                'title' => 'Educational visits should feel practical, calm and respectful of animal welfare.',
                'summary' => 'The image supports APES learning sessions and guided care conversations without claiming to document a specific public event.',
            ],
            'fundraising-priorities-visual' => [
                'image' => 'fundraising-priorities',
                'eyebrow' => 'Operational priorities',
                'title' => 'Practical tools and systems keep daily APES welfare work moving.',
                'summary' => 'This supporting visual aligns with the published fundraising priorities around equipment, software, monitoring and day-to-day operational readiness.',
            ],
            'relocation-continuity-visual' => [
                'image' => 'relocation-continuity',
                'eyebrow' => 'Continuity of care',
                'title' => 'Relocation planning is about keeping animals safe while operations move.',
                'summary' => 'The image supports the appeal tone by focusing on organised welfare continuity rather than presenting an active emergency scene.',
            ],
        ],
        'newsroom_copy' => $newsroom_copy,
        'social_profiles' => [
            ['platform' => 'Facebook', 'label' => 'APES on Facebook', 'handle' => 'apesorguk', 'href' => 'https://www.facebook.com/apesorguk', 'icon' => 'facebook', 'external' => true, 'placements' => ['header', 'footer', 'socials-primary'], 'summary' => 'Follow the main APES Facebook page for public updates, welfare stories and supporter notices.'],
            ['platform' => 'Instagram', 'label' => 'APES on Instagram', 'handle' => '@apesorguk', 'href' => 'https://www.instagram.com/apesorguk', 'icon' => 'instagram', 'external' => true, 'placements' => ['header', 'footer', 'socials-primary'], 'summary' => 'See outward-facing APES photos, visual updates and day-to-day supporter content.'],
            ['platform' => 'X', 'label' => 'APES on X', 'handle' => '@apesorguk', 'href' => 'https://www.x.com/apesorguk', 'icon' => 'x', 'external' => true, 'placements' => ['header', 'footer', 'socials-primary'], 'summary' => 'Use the verified APES X route for quick updates and public-facing announcements.'],
            ['platform' => 'YouTube', 'label' => 'APES on YouTube', 'handle' => '@apesorguk', 'href' => 'https://www.youtube.com/@apesorguk', 'icon' => 'youtube', 'external' => true, 'placements' => ['header', 'footer', 'socials-primary'], 'summary' => 'Watch APES video content, live streams and supporter-facing uploads.'],
            ['platform' => 'Threads', 'label' => 'APES on Threads', 'handle' => '@apesorguk', 'href' => 'https://www.threads.net/@apesorguk', 'icon' => 'threads', 'external' => true, 'placements' => ['header', 'footer', 'socials-primary'], 'summary' => 'Follow APES on Threads for lighter public updates and connected social posts.'],
            ['platform' => 'Bluesky', 'label' => 'APES on Bluesky', 'handle' => '@apesorguk.bsky.social', 'href' => 'https://bsky.app/profile/apesorguk.bsky.social', 'icon' => 'bluesky', 'external' => true, 'placements' => ['header', 'footer', 'socials-primary'], 'summary' => 'Use the verified Bluesky route for decentralised public updates from APES.'],
            ['platform' => 'Facebook group', 'label' => 'APES Facebook group', 'handle' => 'apesorguk group', 'href' => 'https://www.facebook.com/groups/apesorguk', 'icon' => 'facebook', 'external' => true, 'placements' => ['socials-community'], 'summary' => 'Join the APES Facebook group for community discussion rather than the primary public announcement feed.'],
            ['platform' => 'APES Social', 'label' => 'APES Social', 'handle' => 'social.apes.org.uk', 'href' => 'https://social.apes.org.uk/', 'icon' => 'apes-social', 'external' => true, 'placements' => ['socials-community'], 'summary' => 'Visit the APES Social/Mastodon instance as a connected community platform while public handle verification remains under review.'],
            ['platform' => 'Discord', 'label' => 'APES Discord community', 'handle' => 'Discord invite', 'href' => 'https://discord.gg/', 'icon' => 'discord', 'external' => true, 'placements' => ['socials-community'], 'summary' => 'Use the APES Discord route for community discussion and informal supporter chat where that external platform remains active.'],
        ],
        'route_finder_items' => [
            [
                'label' => 'Adopt an animal',
                'summary' => 'Start with the Shelter & Rescue adoption route.',
                'filters' => ['rescue-and-shelter'],
                'keywords' => ['adopt adoption shelter rescue rehome animals'],
                'recommendation_title' => 'Shelter & Rescue adoption routes',
                'description' => 'View the APES Shelter & Rescue adoption route for adoptable animals, suitability checks and aftercare expectations.',
                'primary' => ['label' => 'View adoptable animals', 'href' => 'https://www.apesshelter.org.uk/', 'external' => true],
                'secondary' => ['label' => 'Read the Adoption Policy', 'href' => '/policies/adoption-policy/'],
                'alternatives' => [
                    ['label' => 'Read the Re-Homing Policy', 'href' => '/policies/re-homing-policy/'],
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                ],
                'note' => 'Expect suitability checks, setup expectations and welfare-first placement guidance before any animal leaves APES care.',
            ],
            [
                'label' => 'I can no longer care for my pet',
                'summary' => 'Use the surrender route rather than a general contact request.',
                'filters' => ['rescue-and-shelter'],
                'keywords' => ['surrender owner cannot care no longer care pet rescue shelter'],
                'recommendation_title' => 'Shelter and surrender support',
                'description' => 'Start with the APES Shelter & Rescue surrender route so APES can separate owner surrender from stray or emergency cases.',
                'primary' => ['label' => 'Start surrender route', 'href' => 'https://www.apesshelter.org.uk/', 'external' => true],
                'secondary' => ['label' => 'Contact the contact centre', 'href' => 'https://contact.apes.org.uk/', 'external' => true],
                'alternatives' => [
                    ['label' => 'Read 24/7 services guidance', 'href' => '/24-7-services/'],
                    ['label' => 'Open Help Centre', 'href' => 'https://help.apes.org.uk/', 'external' => true],
                ],
                'note' => 'Use one request per animal and do not arrive without an arranged handover or confirmed APES instruction.',
            ],
            [
                'label' => 'This is not my pet',
                'summary' => 'Use rescue support rather than owner-surrender routes.',
                'filters' => ['rescue-and-shelter', 'lost-and-found'],
                'keywords' => ['not my pet rescue stray abandoned contained found unknown owner'],
                'recommendation_title' => 'Rescue services and containment guidance',
                'description' => 'Request rescue support where the animal is not yours, especially if containment, species identification or public-safety support is needed.',
                'primary' => ['label' => 'Request rescue support', 'href' => 'https://www.apesshelter.org.uk/', 'external' => true],
                'secondary' => ['label' => 'Found pet reporting', 'href' => '/services/lost-n-found-pets/'],
                'alternatives' => [
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                    ['label' => '24/7 services guidance', 'href' => '/24-7-services/'],
                ],
                'note' => 'Only provide species, location, containment status and photographs where it is safe to do so.',
            ],
            [
                'label' => 'I have lost my pet',
                'summary' => 'Use the approved lost-pet reporting route.',
                'filters' => ['lost-and-found'],
                'keywords' => ['lost pet missing animal reporting questionnaire'],
                'recommendation_title' => 'Lost-pet reporting route',
                'description' => 'Open the APES lost-pet route and submit clear identifying details so reports can be matched if a related found-pet report arrives later.',
                'primary' => ['label' => 'Open lost pet questionnaire', 'href' => 'https://service.sheltermanager.com/', 'external' => true],
                'secondary' => ['label' => 'Contact APES', 'href' => '/contact/'],
                'alternatives' => [
                    ['label' => 'Found pet route', 'href' => '/services/lost-n-found-pets/'],
                    ['label' => 'Shelter & Rescue', 'href' => 'https://www.apesshelter.org.uk/', 'external' => true],
                ],
                'note' => 'Provide photographs and identifying details where available, but do not share sensitive information through the route-finder itself.',
            ],
            [
                'label' => 'I have found a pet',
                'summary' => 'Use found-pet reporting and safe-handling guidance.',
                'filters' => ['lost-and-found'],
                'keywords' => ['found pet found animal owner report containment exotic'],
                'recommendation_title' => 'Found-pet reporting route',
                'description' => 'Use the APES found-pet route if you can safely hold the animal or need the approved reporting path to help reconnect it with an owner.',
                'primary' => ['label' => 'Open found pet questionnaire', 'href' => 'https://service.sheltermanager.com/', 'external' => true],
                'secondary' => ['label' => 'Read lost and found guidance', 'href' => '/services/lost-n-found-pets/'],
                'alternatives' => [
                    ['label' => 'Request rescue support', 'href' => 'https://www.apesshelter.org.uk/', 'external' => true],
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                ],
                'note' => 'Avoid unsafe handling and use rescue support for unknown, escaped or higher-risk exotic species.',
            ],
            [
                'label' => 'I want to support APES',
                'summary' => 'Choose the donation, fundraising or sponsor route.',
                'filters' => ['support-apes'],
                'keywords' => ['donate sponsor fundraising support volunteer help apes'],
                'recommendation_title' => 'Donate, sponsor and fundraising routes',
                'description' => 'Use the main APES support routes to donate, back a current fundraising priority or explore sponsorship and volunteering options.',
                'primary' => ['label' => 'Donate now', 'href' => '/donate/'],
                'secondary' => ['label' => 'View fundraising priorities', 'href' => '/donating/fundraising/'],
                'alternatives' => [
                    ['label' => 'Volunteer and placements', 'href' => '/volunteer/'],
                    ['label' => 'Sponsor and partner support', 'href' => '/sponsors/'],
                ],
                'note' => 'The donation route explains the external payment pathway, while fundraising and sponsorship pages cover current operational priorities and review notes.',
            ],
            [
                'label' => 'The animal may be injured, loose in public or at immediate risk',
                'summary' => 'See emergency signposting before routine APES forms.',
                'filters' => ['contact-and-help'],
                'keywords' => ['urgent injured loose public safety immediate risk emergency vet local authority'],
                'recommendation_title' => 'Urgent guidance and emergency signposting',
                'description' => 'Read the urgent guidance first so emergency services, local authority routes or veterinary support are used where appropriate before routine APES forms.',
                'primary' => ['label' => 'Read urgent guidance', 'href' => '/24-7-services/'],
                'secondary' => ['label' => 'Contact APES', 'href' => '/contact/'],
                'alternatives' => [
                    ['label' => 'Open contact centre', 'href' => 'https://contact.apes.org.uk/', 'external' => true],
                    ['label' => 'Visit opening times', 'href' => '/opening-times/'],
                ],
                'note' => 'Do not rely on routine forms alone for immediate danger, injury, traffic risk or public-safety incidents.',
            ],
            [
                'label' => 'I need pet care, boarding, education or a visit',
                'summary' => 'Use care, boarding and education routes across the APES network.',
                'filters' => ['care-and-clinic', 'boarding', 'education'],
                'keywords' => ['pet care clinic boarding education visits therapy book'],
                'recommendation_title' => 'Care, boarding and educational support',
                'description' => 'Choose the best route for Pet Care Clinic, pet boarding, educational visits or animal-therapy-related enquiries without guessing which APES service to contact first.',
                'primary' => ['label' => 'Open the Services hub', 'href' => '/services/'],
                'secondary' => ['label' => 'Bookings and visit routes', 'href' => '/bookings/'],
                'alternatives' => [
                    ['label' => 'Pet boarding', 'href' => '/pet-boarding/'],
                    ['label' => 'Educational visits', 'href' => '/educational-visits/'],
                ],
                'note' => 'Connected services remain separate where needed, including the external Pet Care Clinic and boarding portal journeys.',
            ],
        ],
        'nav' => [
            [
                'label' => 'Home',
                'href' => '/',
            ],
            [
                'label' => 'Services',
                'panel_theme' => 'services',
                'panel_eyebrow' => 'APES service routes',
                'panel_heading' => 'Services across the APES network',
                'panel_description' => 'Find the right welfare, booking, rescue, care and public-support route without guessing which APES service to use first.',
                'panel_pills' => ['Rescue', 'Care', 'Bookings', 'Education'],
                'children' => [
                    ['label' => 'Services hub', 'badge' => 'SV', 'description' => 'Search, filter and compare the main APES public service routes before choosing where to go next.', 'href' => '/services/'],
                    ['label' => 'Bookings', 'badge' => 'BK', 'description' => 'Use the narrower bookings route for visits, boarding and practical booking guidance.', 'href' => '/bookings/'],
                    ['label' => 'Shelter, rescue and adoptions', 'badge' => 'SR', 'description' => 'Open the APES Shelter & Rescue website for rehoming, surrender guidance and welfare support.', 'href' => 'https://www.apesshelter.org.uk/', 'external' => true],
                    ['label' => 'Pet care clinic', 'badge' => 'PC', 'description' => 'Visit APES Pet Care Clinic for low-cost support, care plans, bookings and pet health guidance.', 'href' => 'https://www.apespetcare.org.uk/', 'external' => true],
                    ['label' => 'Pet shop', 'badge' => 'PS', 'description' => 'Browse APES pet-shop information and connected shopping routes that help fund welfare work.', 'href' => '/apes-pet-shop/'],
                    ['label' => 'Pet boarding', 'badge' => 'PB', 'description' => 'Find reptile boarding information and the approved external booking journey.', 'href' => '/pet-boarding/'],
                    ['label' => 'Lost and found pets', 'badge' => 'LF', 'description' => 'Use APES support routes to report a lost pet or help reconnect a found animal.', 'href' => '/services/lost-n-found-pets/'],
                    ['label' => '24/7 support services', 'badge' => '24', 'description' => 'See around-the-clock support and welfare routes for urgent practical help.', 'href' => '/24-7-services/'],
                    ['label' => 'Animal therapy', 'badge' => 'AT', 'description' => 'Learn about animal-therapy support and public benefit activity delivered through APES.', 'href' => '/services/animal-therapy/'],
                    ['label' => 'Educational visits', 'badge' => 'EV', 'description' => 'Book educational visits and species-focused public learning experiences.', 'href' => '/educational-visits/'],
                ],
            ],
            [
                'label' => 'Support APES',
                'panel_theme' => 'support',
                'panel_eyebrow' => 'Funding animal welfare',
                'panel_heading' => 'Fundraising, volunteering and sponsorship',
                'panel_description' => 'Every contribution helps fund food, treatment, safe housing, enrichment and practical support across the APES network.',
                'panel_pills' => ['Donate', 'Sponsor', 'Volunteer', 'Updates'],
                'children' => [
                    ['label' => 'Donate', 'badge' => 'DN', 'description' => 'Support daily welfare work, relocation needs and rescue operations through the main donation route.', 'href' => '/donate/'],
                    ['label' => 'Fundraising priorities', 'badge' => 'FP', 'description' => 'See the equipment, software and operational priorities APES is currently raising funds for.', 'href' => '/donating/fundraising/'],
                    ['label' => 'Volunteer', 'badge' => 'VO', 'description' => 'Find out how volunteer-led work keeps APES services running with care and accountability.', 'href' => '/volunteer/'],
                    ['label' => 'Sponsors', 'badge' => 'SP', 'description' => 'Meet the organisations helping APES with software, infrastructure and practical services.', 'href' => '/sponsors/'],
                    ['label' => 'Enterprise mailing list', 'badge' => 'EM', 'description' => 'Register interest for business, partner, grant and social-enterprise communications.', 'href' => '/enterprise-mailing-list/'],
                    ['label' => 'Help Us Move', 'badge' => 'HM', 'description' => 'Read the relocation appeal and the practical support still needed to protect continuity of care.', 'href' => '/help-us-move/'],
                ],
            ],
            [
                'label' => 'Information',
                'panel_theme' => 'information',
                'panel_eyebrow' => 'Guidance and updates',
                'panel_heading' => 'About, policies, updates and APES Newsroom',
                'panel_description' => 'Read practical welfare guidance, policy information, organisation updates and trusted APES communication routes.',
                'panel_pills' => ['Education', 'News', 'Policies', 'Contact'],
                'children' => [
                    ['label' => 'About APES', 'badge' => 'AP', 'description' => 'Learn about APES CIC, the three Rs and the organisation\'s welfare-first operating model.', 'href' => '/about-us/'],
                    ['label' => 'Mission statement', 'badge' => 'MS', 'description' => 'Read the APES mission, owner-support focus, public benefit role and the three Rs in more detail.', 'href' => '/mission/our-main-mission-statement/'],
                    ['label' => 'Ethical rehabilitation', 'badge' => 'ER', 'description' => 'See how APES frames rehabilitation, recovery planning and welfare-first outcomes.', 'href' => '/mission/support-ethical-rehabilitation/'],
                    ['label' => 'Visit the centre', 'badge' => 'VC', 'description' => 'Check visitor access, parking, appointment and accessibility notes before attending.', 'href' => '/the-center/'],
                    ['label' => 'Opening times', 'badge' => 'OT', 'description' => 'Use the canonical public opening-hours page and out-of-hours guidance.', 'href' => '/opening-times/'],
                    ['label' => 'Policies hub', 'badge' => 'PH', 'description' => 'Browse welfare policies, legal website policies and the main APES public policy routes in one place.', 'href' => '/policies/'],
                    ['label' => 'Adoption Policy', 'badge' => 'AD', 'description' => 'Review APES public adoption conditions and related welfare-policy guidance.', 'href' => '/policies/adoption-policy/'],
                    ['label' => 'Re-Homing Policy', 'badge' => 'RH', 'description' => 'Read the public re-homing checks and welfare safeguards used by APES.', 'href' => '/policies/re-homing-policy/'],
                    ['label' => 'Euthanasia Policy', 'badge' => 'EU', 'description' => 'Read the public welfare-threshold guidance for individual euthanasia decisions.', 'href' => '/policies/euthanasia-policy/'],
                    ['label' => 'APES Newsroom', 'badge' => 'NW', 'description' => 'Read organisation updates, service notices, appeals and cross-division stories in APES Newsroom.', 'href' => APES_NEWSROOM_URL, 'external' => true],
                    ['label' => 'Socials', 'badge' => 'SO', 'description' => 'Find the verified public APES social channels and connected community routes.', 'href' => '/socials/'],
                    ['label' => 'Change Log Hub', 'badge' => 'CL', 'description' => 'Track public website releases, fixes, compliance changes and layout improvements.', 'href' => '/change-log-hub/'],
                    ['label' => 'Privacy Policy', 'badge' => 'PP', 'description' => 'Review how APES handles personal data, communications and public website information.', 'href' => '/policies/privacy/'],
                    ['label' => 'Terms of Service', 'badge' => 'TS', 'description' => 'Read the public terms that govern use of APES services, routes and website content.', 'href' => '/policies/terms-of-service/'],
                    ['label' => 'Cookie guidance', 'badge' => 'CK', 'description' => 'See the current APES cookie guidance and website preference information.', 'href' => '/policies/cookies/'],
                ],
            ],
            [
                'label' => 'Contact',
                'href' => '/contact/',
            ],
            [
                'label' => 'Donate',
                'href' => '/donate/',
                'cta' => true,
            ],
        ],
        'footer_columns' => [
            [
                'title' => 'About APES & visitor info',
                'items' => [
                    ['label' => 'Association of Protecting Exotic Species CIC', 'href' => '/about-us/'],
                    ['label' => 'Mission statement and public benefit', 'href' => '/mission/our-main-mission-statement/'],
                    ['label' => 'Ethical rehabilitation', 'href' => '/mission/support-ethical-rehabilitation/'],
                    ['label' => 'Visit the centre', 'href' => '/the-center/'],
                    ['label' => 'Opening times', 'href' => '/opening-times/'],
                    ['label' => 'Contact page', 'href' => '/contact/'],
                    ['label' => 'Contact centre', 'href' => 'https://contact.apes.org.uk/', 'external' => true],
                ],
            ],
            [
                'title' => 'Services, updates & staff',
                'items' => [
                    ['label' => 'Services hub', 'href' => '/services/'],
                    ['label' => 'Bookings', 'href' => '/bookings/'],
                    ['label' => 'APES Newsroom', 'href' => APES_NEWSROOM_URL, 'external' => true],
                    ['label' => 'Socials', 'href' => '/socials/'],
                    ['label' => 'Change Log Hub', 'href' => '/change-log-hub/'],
                    ['label' => 'Staff intranet', 'href' => 'https://intranet.apes.org.uk', 'external' => true, 'nofollow' => true],
                ],
            ],
            [
                'title' => 'Support APES & access',
                'items' => [
                    ['label' => 'Donate', 'href' => '/donate/'],
                    ['label' => 'Fundraising priorities', 'href' => '/donating/fundraising/'],
                    ['label' => 'Volunteer and placements', 'href' => '/volunteer/'],
                    ['label' => 'Sponsors and partners', 'href' => '/sponsors/'],
                    ['label' => 'Help Us Move', 'href' => '/help-us-move/'],
                    ['label' => 'Volunteer intranet', 'href' => 'https://intranet.apes.org.uk', 'external' => true, 'nofollow' => true],
                    ['label' => 'Student intranet', 'href' => 'https://intranet.apes.org.uk', 'external' => true, 'nofollow' => true],
                ],
            ],
            [
                'title' => 'Policies & legal',
                'items' => [
                    ['label' => 'Policies hub', 'href' => '/policies/'],
                    ['label' => 'Adoption Policy', 'href' => '/policies/adoption-policy/'],
                    ['label' => 'Re-Homing Policy', 'href' => '/policies/re-homing-policy/'],
                    ['label' => 'Euthanasia Policy', 'href' => '/policies/euthanasia-policy/'],
                    ['label' => 'Privacy Policy', 'href' => '/policies/privacy/'],
                    ['label' => 'Terms of Service', 'href' => '/policies/terms-of-service/'],
                    ['label' => 'Cookie guidance', 'href' => '/policies/cookies/'],
                ],
            ],
        ],
        'footer_partners' => [
            ['label' => 'British Arachnological Society', 'href' => 'https://britishspiders.org.uk/', 'logo' => '/assets/logos/partners/bas-logo.png', 'logo_alt' => 'British Arachnological Society logo'],
            ['label' => 'FBH', 'href' => 'https://www.thefbh.org/', 'logo' => '/assets/logos/partners/fbh-logo.webp', 'logo_alt' => 'Federation of British Herpetologists logo'],
            ['label' => 'Merseyside Police', 'href' => 'https://www.merseyside.police.uk/', 'logo' => '/assets/logos/partners/merseyside-police-logo.png', 'logo_alt' => 'Merseyside Police logo'],
        ],
        'footer_required_links' => [
            ['label' => 'Donate', 'href' => '/donate/'],
            ['label' => 'Privacy Policy', 'href' => '/policies/privacy/'],
            ['label' => 'Terms of Service', 'href' => '/policies/terms-of-service/'],
            ['label' => 'Change Log Hub', 'href' => '/change-log-hub/'],
        ],
        'sponsors' => [
            ['label' => 'Zoho', 'href' => 'https://www.zoho.com/', 'logo' => '/assets/logos/sponsors/zoho-logo.png', 'logo_alt' => 'Zoho logo'],
            ['label' => 'Shopify', 'href' => 'https://www.shopify.com/', 'logo' => '/assets/logos/sponsors/shopify-logo.jpg', 'logo_alt' => 'Shopify logo'],
            ['label' => 'Akamai', 'href' => 'https://www.akamai.com/', 'logo' => '/assets/logos/sponsors/akamai-logo.png', 'logo_alt' => 'Akamai logo'],
            ['label' => 'cPanel', 'href' => 'https://www.cpanel.net/', 'logo' => '/assets/logos/sponsors/cpanel-logo.png', 'logo_alt' => 'cPanel logo'],
        ],
        'pages' => [
            'home' => [
                'route' => '/',
                'meta_title' => 'APES CIC | Rescue, rehabilitation, rehoming and exotic animal support',
                'title' => 'Association of Protecting Exotic Species CIC',
                'description' => 'APES CIC provides exotic animal rescue, rehabilitation, rehoming, welfare guidance, education and public support across the APES network.',
                'hero_media' => 'homepage-hero',
                'route_finder_media' => 'route-finder-illustration',
                'hero_kicker' => 'APES CIC',
                'hero_title' => 'Protect exotic species through rescue, care and practical public support.',
                'hero_summary' => 'APES CIC brings together rescue, rehabilitation, rehoming, education and day-to-day support so animals, keepers and communities can get help quickly.',
                'hero_actions' => [
                    ['label' => 'Donate today', 'href' => '/donate/', 'variant' => 'primary'],
                    ['label' => 'Explore services', 'href' => '/services/', 'variant' => 'secondary'],
                ],
                'pills' => ['Rescue and rehoming', 'Public support', 'Education and care'],
                'body_html' => <<<'HTML'
<section class="alert-band">
  <p><strong>Urgent operational update:</strong> APES continues to share relocation and continuity updates through APES Newsroom and the Help Us Move appeal. Supporters can help through donations, fundraising and practical sponsorship.</p>
  <p><a class="text-link" href="/help-us-move/">Read the relocation appeal</a></p>
</section>

[[ROUTE_FINDER_COMPACT]]

<section class="spotlight-band">
  <div class="spotlight-copy">
    <p class="eyebrow">Our public mission</p>
    <h2>Practical help for animals, owners and communities.</h2>
    <p>Use this page to find rescue support, care services, fundraising routes, policy guidance and the main public ways to contact or support APES CIC.</p>
  </div>
  <div class="spotlight-grid">
    <article class="spotlight-card">
      <span class="spotlight-kicker">News</span>
      <h3>APES Newsroom</h3>
      <p>All organisation updates and service notices now live in the central newsroom.</p>
    </article>
    <article class="spotlight-card">
      <span class="spotlight-kicker">Support</span>
      <h3>Donate or get help</h3>
      <p>Donation, contact and relocation routes remain visible in every major journey.</p>
    </article>
    <article class="spotlight-card">
      <span class="spotlight-kicker">Guidance</span>
      <h3>Clear and accessible routes</h3>
      <p>Key welfare, support and policy journeys stay easy to find on desktop and mobile.</p>
    </article>
  </div>
</section>

<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">What APES does</p>
    <h2>Specialist welfare support for exotic species and their keepers.</h2>
  </div>
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Rescue and shelter</h3>
      <p>APES responds when exotic animals are abandoned, unsafe, displaced or in urgent need of species-appropriate care.</p>
      <a class="text-link" href="https://www.apesshelter.org.uk/" target="_blank" rel="noreferrer">Visit Shelter &amp; Rescue</a>
    </article>
    <article class="info-card">
      <h3>Pet care and support</h3>
      <p>The wider APES network supports keepers with practical care services, boarding, educational visits and everyday welfare guidance.</p>
      <a class="text-link" href="/pet-boarding/">Explore care services</a>
    </article>
    <article class="info-card">
      <h3>Education and public benefit</h3>
      <p>APES shares guidance, live support routes, policy information and educational sessions that help people keep animals safely and responsibly.</p>
      <a class="text-link" href="/educational-visits/">Book an educational visit</a>
    </article>
  </div>
</section>

<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">Quick access</p>
    <h2>Main public journeys</h2>
  </div>
  <div class="card-grid card-grid-three">
    <article class="service-card">
      <h3>Donate</h3>
      <p>Support rescue operations, rehabilitation, relocation work and day-to-day welfare services.</p>
      <a class="button button-primary" href="/donate/">Donate now</a>
    </article>
    <article class="service-card">
      <h3>Lost and found pets</h3>
      <p>Use the APES forms and contact routes to report a lost pet or help reconnect a found animal with its owner.</p>
      <a class="button button-secondary" href="/services/lost-n-found-pets/">Access support</a>
    </article>
    <article class="service-card">
      <h3>Volunteer and student placements</h3>
      <p>Find out how volunteer-led support powers APES services and how to register your interest responsibly.</p>
      <a class="button button-secondary" href="/volunteer/">See opportunities</a>
    </article>
    <article class="service-card">
      <h3>Pet boarding</h3>
      <p>Specialist reptile boarding and linked portal access remain available through the approved external booking system.</p>
      <a class="button button-secondary" href="/pet-boarding/">Boarding information</a>
    </article>
    <article class="service-card">
      <h3>Fundraising</h3>
      <p>See current equipment and technology priorities that directly improve animal care, operations and supporter service.</p>
      <a class="button button-secondary" href="/donating/fundraising/">View fundraising needs</a>
    </article>
    <article class="service-card">
      <h3>Policies and governance</h3>
      <p>Read the current public policy pages, service terms and privacy guidance in one consistent APES layout.</p>
      <a class="button button-secondary" href="/policies/terms-of-service/">Review policies</a>
    </article>
  </div>
</section>

<section class="section-shell split-panel">
  <div>
    <p class="eyebrow">About APES CIC</p>
    <h2>Exotic animal welfare with operational transparency.</h2>
    <p>APES CIC is focused on the welfare, protection and conservation of exotic species. The organisation works across rescue, rehabilitation, rehoming, education, public guidance, conservation awareness and practical support for keepers and communities.</p>
    <p>The current public mission remains rooted in compassionate, non-judgemental support. APES combines welfare-first services with plain-language information so people can act quickly when an animal or owner needs help.</p>
  </div>
  <div class="metrics-grid">
    <article class="metric-card">
      <strong>Rescue</strong>
      <span>Respond to abandonment, unsafe keeping and urgent welfare needs.</span>
    </article>
    <article class="metric-card">
      <strong>Rehabilitate</strong>
      <span>Provide species-appropriate care, recovery support and careful handling.</span>
    </article>
    <article class="metric-card">
      <strong>Rehome</strong>
      <span>Match animals to informed adopters through responsible checks and guidance.</span>
    </article>
    <article class="metric-card">
      <strong>Educate</strong>
      <span>Share practical welfare knowledge through visits, support routes and public resources.</span>
    </article>
  </div>
</section>

<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">News and connected services</p>
    <h2>Follow live updates through APES Newsroom.</h2>
  </div>
  <div class="card-grid card-grid-two">
    <article class="info-card">
      <h3>APES Newsroom</h3>
      <p>News and updates from APES CIC and our services are now published through APES Newsroom. Visit APES Newsroom to read the latest organisation updates, Shelter &amp; Rescue news, Pet Care Clinic updates, service notices, appeals and guidance from across the APES network.</p>
      <a class="button button-secondary" href="https://www.apesnews.org.uk/" target="_blank" rel="noreferrer">Open APES Newsroom</a>
    </article>
    <article class="info-card">
      <h3>Live stream and public channels</h3>
      <p>The live site also promotes APES streaming, social and supporter channels. These remain connected services rather than being republished inside this website.</p>
      <ul class="clean-list">
        <li><a class="text-link" href="https://www.youtube.com/" target="_blank" rel="noreferrer">YouTube live stream</a></li>
        <li><a class="text-link" href="https://social.apes.org.uk/" target="_blank" rel="noreferrer">APES Social</a></li>
        <li><a class="text-link" href="https://discord.gg/" target="_blank" rel="noreferrer">Discord community</a></li>
      </ul>
    </article>
  </div>
</section>

<section class="cta-band">
  <div>
    <p class="eyebrow">Support the work</p>
    <h2>Every donation, referral and responsible rehoming enquiry helps protect more animals.</h2>
  </div>
  <div class="action-row">
    <a class="button button-primary" href="/donate/">Donate</a>
    <a class="button button-secondary" href="/contact/">Contact APES</a>
  </div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Services hub', 'href' => '/services/'],
                    ['label' => 'Help Us Move', 'href' => '/help-us-move/'],
                    ['label' => 'Volunteer and student placements', 'href' => '/volunteer/'],
                    ['label' => 'APES Pet Care Clinic', 'href' => 'https://www.apespetcare.org.uk/', 'external' => true],
                    ['label' => 'APES Shelter & Rescue', 'href' => 'https://www.apesshelter.org.uk/', 'external' => true],
                ],
            ],
            'services' => [
                'route' => '/services/',
                'meta_title' => 'Services | Find the right APES support route',
                'title' => 'Services',
                'description' => 'Search, filter and compare APES public service routes across rescue, support, care, education and connected services.',
                'route_finder_media' => 'route-finder-illustration',
                'hero_kicker' => 'Services hub',
                'hero_title' => 'Find the right APES route before you contact us.',
                'hero_summary' => 'Use the APES Services hub to compare rescue, lost-and-found, care, boarding, education, donation and support routes without guessing which service to approach first.',
                'hero_actions' => [
                    ['label' => 'Use the route finder', 'href' => '#service-route-search', 'variant' => 'primary'],
                    ['label' => 'Open bookings', 'href' => '/bookings/', 'variant' => 'secondary'],
                ],
                'pills' => ['Service routing', 'Search and filters', 'Safety-first guidance'],
                'body_html' => <<<'HTML'
[[ROUTE_FINDER_EXPANDED]]

[[FEATURE_MEDIA:services-care-education]]

<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">Connected APES services</p>
    <h2>Crawlable routes stay available even when JavaScript is off.</h2>
  </div>
  <div class="card-grid card-grid-three">
    <article class="service-card">
      <h3>Rescue and shelter</h3>
      <p>Use Shelter &amp; Rescue for adoption, surrender, rescue support and welfare-first case handling across exotic animal needs.</p>
      <a class="text-link" href="https://www.apesshelter.org.uk/" target="_blank" rel="noreferrer">Open Shelter &amp; Rescue</a>
    </article>
    <article class="service-card">
      <h3>Care and clinic</h3>
      <p>Use Pet Care Clinic, boarding, education and therapy routes for practical support that sits alongside the wider APES network.</p>
      <a class="text-link" href="https://www.apespetcare.org.uk/" target="_blank" rel="noreferrer">Visit Pet Care Clinic</a>
    </article>
    <article class="service-card">
      <h3>Support APES</h3>
      <p>Donate, back a current fundraising priority, volunteer or explore sponsorship when you want to strengthen day-to-day welfare work.</p>
      <a class="text-link" href="/donate/">Support APES</a>
    </article>
  </div>
</section>

<section class="note-panel">
  <h2>Safety-first note</h2>
  <p>The route finder does not collect case details. If an animal is injured, loose in public or poses an immediate welfare or public-safety risk, use the urgent guidance first rather than relying only on a routine form.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Bookings', 'href' => '/bookings/'],
                    ['label' => 'Lost and found pets', 'href' => '/services/lost-n-found-pets/'],
                    ['label' => '24/7 services', 'href' => '/24-7-services/'],
                ],
            ],
            'help-us-move' => [
                'route' => '/help-us-move/',
                'meta_title' => 'Help Us Move | APES CIC relocation appeal',
                'title' => 'Help Us Move',
                'description' => 'Support the APES CIC relocation appeal and help protect continuity of animal care during premises changes.',
                'hero_kicker' => 'Operational appeal',
                'hero_title' => 'Help APES protect continuity of care during relocation.',
                'hero_summary' => 'Support the APES relocation appeal and help protect safe housing, continuity of care and everyday welfare support for animals already depending on the organisation.',
                'hero_actions' => [
                    ['label' => 'Support the appeal', 'href' => '/donate/', 'variant' => 'primary'],
                    ['label' => 'Read the latest updates', 'href' => APES_NEWSROOM_URL, 'external' => true, 'variant' => 'secondary'],
                ],
                'pills' => ['Relocation appeal', 'Continuity of care', 'Support needed'],
                'body_html' => <<<'HTML'
[[FEATURE_MEDIA:relocation-continuity-visual]]

<section class="section-shell">
  <p>The public appeal explains that APES was asked to vacate its previous premises with limited time to relocate while continuing to care for animals already depending on the organisation.</p>
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Limited time</h3>
      <p>The organisation reported a fixed deadline to move operations and secure continuity plans quickly.</p>
    </article>
    <article class="info-card">
      <h3>Premises challenge</h3>
      <p>APES needed a suitable location, ideally in the St Helens area, to continue specialist welfare services safely.</p>
    </article>
    <article class="info-card">
      <h3>Animals in care</h3>
      <p>The appeal emphasised that many animals rely on daily housing, monitoring and species-appropriate support.</p>
    </article>
  </div>
</section>

<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">How supporters can help</p>
    <h2>Give, share and keep watching APES Newsroom.</h2>
  </div>
  <ul class="tick-list">
    <li>Donate through the main APES donation route.</li>
    <li>Share the appeal with people who may be able to support premises, transport or fundraising.</li>
    <li>Follow APES Newsroom for the latest continuity and premises updates.</li>
  </ul>
</section>

<section class="cta-band">
  <div>
    <p class="eyebrow">Important note</p>
    <h2>This appeal page stays available while ongoing updates continue through APES Newsroom.</h2>
  </div>
  <div class="action-row">
    <a class="button button-primary" href="/donate/">Support APES</a>
    <a class="button button-secondary" href="https://www.apesnews.org.uk/" target="_blank" rel="noreferrer">Read APES Newsroom</a>
  </div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Donate', 'href' => '/donate/'],
                    ['label' => 'Fundraising priorities', 'href' => '/donating/fundraising/'],
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                ],
            ],
            'apes-pet-shop' => [
                'route' => '/apes-pet-shop/',
                'meta_title' => 'APES Pet Shop | Products that support animal welfare',
                'title' => 'APES Pet Shop',
                'description' => 'The APES Pet Shop supports the organisation through pet product sales, click and collect, and local delivery where available.',
                'hero_kicker' => 'Pet products',
                'hero_title' => 'Shop products that help fund APES animal care.',
                'hero_summary' => 'Browse the APES shop for products that support animal welfare, with information about online orders, collections and local delivery where available.',
                'hero_actions' => [
                    ['label' => 'Visit the online shop', 'href' => 'https://www.apesshop.co.uk/', 'external' => true, 'variant' => 'primary'],
                    ['label' => 'Contact about shop orders', 'href' => '/contact/', 'variant' => 'secondary'],
                ],
                'pills' => ['Welfare fundraising', 'Online shop', 'Collection options'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <p>The pet shop helps APES bring in funds for the animals in its care. The live site also includes a large gallery area with repeated placeholder labels such as "Title" and "Caption". Those placeholders have been intentionally documented in the audit rather than silently rewritten.</p>
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Online orders</h3>
      <p>Browse products through the approved external APES shop.</p>
    </article>
    <article class="info-card">
      <h3>Free local delivery</h3>
      <p>Legacy public copy states that customers within six miles may qualify for free local delivery.</p>
    </article>
    <article class="info-card">
      <h3>Click and collect</h3>
      <p>Customers can order and pay online, then collect when the order is ready.</p>
    </article>
  </div>
</section>

<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">Product sourcing</p>
    <h2>Not in stock? APES may be able to bring items in to order.</h2>
  </div>
  <p>The live page says APES can source requested products through distribution centres and then either dispatch them onward or make them available for collection once ready.</p>
</section>

<section class="note-panel">
  <h2>Content review note</h2>
  <p>The legacy product gallery currently uses repeated placeholder cards. This rebuild preserves that issue in the release audit and avoids inventing product copy that APES has not approved.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Online shop', 'href' => 'https://www.apesshop.co.uk/', 'external' => true],
                    ['label' => 'Donate', 'href' => '/donate/'],
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                ],
            ],
            'pet-boarding' => [
                'route' => '/pet-boarding/',
                'meta_title' => 'Pet boarding | APES reptile boarding services',
                'title' => 'APES pet boarding services',
                'description' => 'View current reptile boarding rates, support for UV and non-UV species, and the external boarding portal.',
                'hero_kicker' => 'Boarding',
                'hero_title' => 'Specialist reptile boarding with linked portal access.',
                'hero_summary' => 'View current reptile boarding options, daily rates and the approved external booking route for arranging a stay.',
                'hero_actions' => [
                    ['label' => 'Book boarding', 'href' => 'https://petmanager.app/', 'external' => true, 'variant' => 'primary'],
                    ['label' => 'Contact about boarding', 'href' => '/contact/', 'variant' => 'secondary'],
                ],
                'pills' => ['Boarding service', 'External booking', 'Species-specific care'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="card-grid card-grid-three">
    <article class="pricing-card">
      <h3>No UV required reptiles</h3>
      <p class="price">GBP 5 per night</p>
      <p>Specialist boarding for reptiles without UV requirements while owners are away.</p>
    </article>
    <article class="pricing-card">
      <h3>UV required reptiles</h3>
      <p class="price">GBP 7 per night</p>
      <p>Boarding that includes suitable UV lighting to support safe day-to-day welfare.</p>
    </article>
    <article class="pricing-card">
      <h3>Small animals</h3>
      <p class="price">Setup in progress</p>
      <p>The live site states that this service is being set up and directs people to sign up for updates.</p>
    </article>
  </div>
</section>

<section class="section-shell split-panel">
  <div>
    <p class="eyebrow">Boarding portal</p>
    <h2>Manage stays through the approved external system.</h2>
    <p>The public page states that the boarding portal lets owners manage a pet's stay, add packages and handle related booking details.</p>
  </div>
  <div class="action-stack">
    <a class="button button-primary" href="https://petmanager.app/" target="_blank" rel="noreferrer">Book now</a>
    <a class="button button-secondary" href="https://petmanager.app/" target="_blank" rel="noreferrer">Boarding portal login</a>
    <a class="button button-secondary" href="https://www.apesnews.org.uk/" target="_blank" rel="noreferrer">Sign up for updates</a>
  </div>
</section>

<section class="note-panel">
  <h2>Before you book</h2>
  <p>The current public route supports reptiles with UV and non-UV needs and keeps small-animal boarding under review. Owners should use the approved booking route, check species suitability before travel, and confirm any welfare, equipment or cancellation questions through APES if their case does not fit the standard portal flow.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Lost and found pets', 'href' => '/services/lost-n-found-pets/'],
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                    ['label' => 'Petmanager portal', 'href' => 'https://petmanager.app/', 'external' => true],
                ],
            ],
            'lost-n-found-pets' => [
                'route' => '/services/lost-n-found-pets/',
                'meta_title' => 'Lost and found pets | APES reporting routes',
                'title' => 'Lost and found pets',
                'description' => 'Use the APES lost pet and found pet reporting routes or contact handlers for help.',
                'hero_kicker' => 'Reporting routes',
                'hero_title' => 'Report a lost pet or help reconnect a found animal.',
                'hero_summary' => 'Use the approved APES reporting routes to submit lost or found pet details quickly and help reunite animals with their owners.',
                'hero_actions' => [
                    ['label' => 'Lost pet questionnaire', 'href' => 'https://service.sheltermanager.com/', 'external' => true, 'variant' => 'primary'],
                    ['label' => 'Found pet questionnaire', 'href' => 'https://service.sheltermanager.com/', 'external' => true, 'variant' => 'secondary'],
                ],
                'pills' => ['Lost pets', 'Found pets', 'Support routes'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="card-grid card-grid-two">
    <article class="info-card">
      <h3>Lost pets</h3>
      <p>Complete the online lost pet form. The public page explains that APES can help match your report if someone later submits a found pet report with related details.</p>
      <a class="text-link" href="https://service.sheltermanager.com/" target="_blank" rel="noreferrer">Open the lost pet questionnaire</a>
    </article>
    <article class="info-card">
      <h3>Found pets</h3>
      <p>If you can keep hold of the animal temporarily while searching for the owner, APES asks you to complete the approved found pet form.</p>
      <a class="text-link" href="https://service.sheltermanager.com/" target="_blank" rel="noreferrer">Open the found pet questionnaire</a>
    </article>
  </div>
</section>

<section class="cta-band">
  <div>
    <p class="eyebrow">Need a person instead?</p>
    <h2>Contact the APES call handlers for direct support.</h2>
  </div>
  <div class="action-row">
    <a class="button button-primary" href="/contact/">Contact APES</a>
    <a class="button button-secondary" href="https://contact.apes.org.uk/" target="_blank" rel="noreferrer">Open contact centre</a>
  </div>
</section>

<section class="note-panel">
  <h2>Reporting guidance</h2>
  <p>Use clear descriptions, photographs and location details where available. If ownership is disputed, the animal is unsafe to contain, or the situation becomes urgent, move to direct APES or emergency guidance rather than relying only on the questionnaire flow.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => '24/7 services', 'href' => '/24-7-services/'],
                    ['label' => 'Shelter & Rescue', 'href' => 'https://www.apesshelter.org.uk/', 'external' => true],
                ],
            ],
            '24-7-services' => [
                'route' => '/24-7-services/',
                'meta_title' => '24/7 services | Emergency support guidance from APES',
                'title' => '24/7 services',
                'description' => 'Read the current APES out-of-hours contact routes, emergency support definitions and veterinary advice signposting.',
                'hero_kicker' => 'Out-of-hours support',
                'hero_title' => 'Advice, emergency channels and urgent support routes.',
                'hero_summary' => 'Find the APES out-of-hours advice routes, urgent support guidance and the external services signposted for emergencies.',
                'hero_actions' => [
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'primary'],
                    ['label' => 'Open contact centre', 'href' => 'https://contact.apes.org.uk/', 'external' => true, 'variant' => 'secondary'],
                ],
                'pills' => ['Out-of-hours advice', 'Urgent support guidance', 'External vet signposting'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <p>The live page lists several ways to contact APES for advice and support, including live chat, email through the contact page, Discord and WhatsApp.</p>
  <ul class="tick-list">
    <li>Live chat on the website.</li>
    <li>Email via the contact page or contact centre.</li>
    <li>Discord community support.</li>
    <li>WhatsApp for urgent shelter and rescue updates.</li>
  </ul>
</section>

<section class="section-shell split-panel">
  <div>
    <p class="eyebrow">What counts as an emergency?</p>
    <ul class="clean-list">
      <li>Made homeless by fire or flood.</li>
      <li>Kicked out from your home.</li>
      <li>Disconnection from utilities.</li>
    </ul>
  </div>
  <div class="note-panel">
    <h2>Emergency veterinary guidance</h2>
    <p>The public page signposts Rutland House Emergency Care and lists an emergency number of <strong>01744 853510</strong> for veterinary emergencies.</p>
    <p>This page remains guidance-only and does not create a new local clinical triage workflow.</p>
  </div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                    ['label' => 'Discord community', 'href' => 'https://discord.gg/', 'external' => true],
                    ['label' => 'WhatsApp support', 'href' => 'https://api.whatsapp.com/', 'external' => true],
                ],
            ],
            'animal-therapy' => [
                'route' => '/services/animal-therapy/',
                'meta_title' => 'Animal therapy | APES therapy and wellbeing support',
                'title' => 'APES animal therapy programme',
                'description' => 'Learn how APES describes the benefits of therapy animals for stress, confidence and emotional support.',
                'hero_kicker' => 'Human welfare',
                'hero_title' => 'Therapy animals as calm, non-judgemental support.',
                'hero_summary' => 'Learn how APES uses supported animal interaction to create calm, confidence and wellbeing-focused experiences for some service users.',
                'hero_actions' => [
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'primary'],
                    ['label' => 'Book educational visits', 'href' => '/educational-visits/', 'variant' => 'secondary'],
                ],
                'pills' => ['Wellbeing support', 'Supported sessions', 'Welfare-first'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <p>The live page describes therapy animals as a calming presence for some service users experiencing stress, anxiety or low mood. It explains that people may benefit from holding, stroking, grooming or simply sitting with an animal when appropriate and supervised.</p>
  <blockquote class="quote-card">
    <p>Animals are a great icebreaker in both one-to-one and group therapy sessions. They lift people's spirits, and just petting or stroking an animal can do wonders for blood pressure and stress levels.</p>
  </blockquote>
  <blockquote class="quote-card">
    <p>The reason animals make such an impact is because people do not feel judged by them. Animals offer unconditional acceptance, which helps service users feel more confident in confronting their issues.</p>
  </blockquote>
</section>

<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">Programme focus</p>
    <h2>Comfort, confidence and supported interaction.</h2>
  </div>
  <ul class="tick-list">
    <li>Build calmer one-to-one and group sessions.</li>
    <li>Support confidence and self-esteem through gentle animal interaction.</li>
    <li>Offer a welfare-first, non-judgemental setting.</li>
  </ul>
</section>

<section class="note-panel">
  <h2>Safeguarding and suitability</h2>
  <p>Animal-therapy activity should be arranged through APES contact routes so suitability, supervision, exclusions, risk assessment and animal welfare controls can be reviewed before a session is confirmed.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Educational visits', 'href' => '/educational-visits/'],
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                ],
            ],
            'educational-visits' => [
                'route' => '/educational-visits/',
                'meta_title' => 'Educational visits | APES outreach and learning sessions',
                'title' => 'Educational visits',
                'description' => 'APES educational visits bring welfare-led talks and exotic species learning experiences to schools, groups and community settings.',
                'hero_kicker' => 'Education',
                'hero_title' => 'Bring exotic wildlife learning directly to your venue.',
                'hero_summary' => 'Plan a welfare-led educational visit for schools, youth groups, care settings and community organisations that want practical exotic species learning.',
                'hero_actions' => [
                    ['label' => 'Plan a visit', 'href' => '/contact/', 'variant' => 'primary'],
                    ['label' => 'Support the programme', 'href' => '/donate/', 'variant' => 'secondary'],
                ],
                'pills' => ['School and community visits', 'Welfare-led learning', 'Outreach'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <p>APES CIC educational visits bring the wonders of exotic wildlife directly to the venue, whether that is a school, college, youth club, care home or community group. The live public copy describes experienced animal educators arriving with a diverse selection of safely handled species and offering age-appropriate talks on biology, behaviour and habitat.</p>
  <p>Through hands-on encounters and interactive questions, the aim is to build appreciation for animal welfare, highlight the risks of the illegal wildlife trade and encourage conservation-minded care.</p>
</section>

[[FEATURE_MEDIA:educational-visits-care]]

<section class="section-shell">
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Tailored visits</h3>
      <p>Sessions can be adapted to group interests and learning objectives.</p>
    </article>
    <article class="info-card">
      <h3>Welfare and conservation</h3>
      <p>Content focuses on responsible ownership, habitat awareness and practical animal welfare.</p>
    </article>
    <article class="info-card">
      <h3>Community benefit</h3>
      <p>Visits are suitable for public learning, wellbeing sessions and responsible care awareness.</p>
    </article>
  </div>
</section>

<section class="section-shell split-panel">
  <div class="note-panel">
    <h2>Who visits are for</h2>
    <p>Current public copy supports schools, youth groups, community organisations, care settings and other supervised learning environments that want welfare-led exotic species education.</p>
  </div>
  <div class="note-panel">
    <h2>Planning and safeguarding</h2>
    <p>Use the APES contact route to discuss learning outcomes, venue suitability, safeguarding expectations, risk assessment needs and whether pricing or donations apply for the type of session requested.</p>
  </div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Animal therapy', 'href' => '/services/animal-therapy/'],
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                ],
            ],
            'donate' => [
                'route' => '/donate/',
                'meta_title' => 'Donate | Support APES CIC animal welfare work',
                'title' => 'Donate',
                'description' => 'Donate to APES CIC and help keep rescue, rehabilitation, support and education work moving.',
                'hero_kicker' => 'Support APES',
                'hero_title' => 'Donate today and help keep more animals safe.',
                'hero_summary' => 'Give through the approved APES donation route to support rescue work, rehabilitation, relocation needs and day-to-day animal care.',
                'hero_actions' => [
                    ['label' => 'Open donation popup', 'href' => '#donate-now', 'variant' => 'primary'],
                    ['label' => 'See fundraising priorities', 'href' => '/donating/fundraising/', 'variant' => 'secondary'],
                ],
                'pills' => ['Rescue support', 'Secure donation route', 'Public fundraising'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">Area of greatest need</p>
    <h2>Your donation helps APES respond where animals and carers need support most.</h2>
  </div>
  <p>Every donation helps APES cover the practical costs that keep rescue, rehabilitation, safe housing and public support moving. Flexible donations are especially valuable because they let the team direct funds to the most urgent welfare, care and continuity needs at the right time.</p>
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Rescue and rehabilitation</h3>
      <p>Help fund urgent intake support, transport, veterinary coordination, recovery supplies and species-appropriate rehabilitation planning for animals entering APES care.</p>
    </article>
    <article class="info-card">
      <h3>Housing and daily care</h3>
      <p>Support food, heating, enclosure upkeep, monitoring equipment, cleaning materials and the everyday welfare costs that keep animals safe and settled.</p>
    </article>
    <article class="info-card">
      <h3>Education and public support</h3>
      <p>Fund guidance, volunteer coordination, welfare-led public advice and the practical supporter services that help people find the right APES route quickly.</p>
    </article>
  </div>
</section>

<section id="donate-now" class="section-shell donorbox-section">
  <div class="section-heading">
    <p class="eyebrow">Approved donation route</p>
    <h2>Give through the APES Donorbox popup form.</h2>
  </div>
  <p>Use the secure popup form below to donate to the APES area of greatest need. This route helps APES respond to welfare priorities, continuity costs and day-to-day care pressures without delaying support for animals already depending on the organisation.</p>
  <div class="donorbox-actions">
    <script id="donorbox-popup-button-installer" type="text/javascript" defer src="https://donorbox.org/install-popup-button.js"></script>
    <a
      id="preview_inline_popup_button"
      class="dbox-donation-button donorbox-popup-button"
      href="https://donorbox.org/donations-909664?designation=Area+of+greatest+need&amount=30"
      data-reminder-widget-enabled="true"
    >
      <img role="presentation" src="https://donorbox.org/images/white_logo.svg" alt="" />
      <span>Donate Now!</span>
    </a>
  </div>
  <p class="donorbox-note">If the popup does not open, the button still works as a standard donation link and will take you directly to the secure Donorbox page.</p>
</section>

<section class="cta-band">
  <div>
    <p class="eyebrow">Give with confidence</p>
    <h2>Your support helps APES protect welfare, maintain continuity and keep public help routes open.</h2>
    <p>Donations help cover both immediate care needs and the less visible operational costs that make safe rescue, rehabilitation and responsible support possible.</p>
  </div>
  <div class="action-row">
    <a class="button button-primary" href="#donate-now">Donate now</a>
    <a class="button button-secondary" href="/donating/fundraising/">View fundraising items</a>
  </div>
</section>

<section class="section-shell donorbox-wall-section">
  <div class="section-heading">
    <p class="eyebrow">Supporter momentum</p>
    <h2>See the donor wall and join the people backing APES.</h2>
  </div>
  <p>The donor wall shows visible supporter activity and helps reassure visitors that they are joining a wider community committed to welfare-first support for exotic species and the people caring for them.</p>
  <div class="donorbox-wall-embed">
    <script src="https://donorbox.org/widget.js" paypalExpress="false"></script>
    <iframe
      title="APES Donorbox donor wall"
      src="https://donorbox.org/embed/donations-909664?donor_wall_color=%23128aed&only_donor_wall=true"
      seamless="seamless"
      name="donorbox"
      frameborder="0"
      scrolling="no"
    >
    </iframe>
  </div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Fundraising', 'href' => '/donating/fundraising/'],
                    ['label' => 'Help Us Move', 'href' => '/help-us-move/'],
                ],
            ],
            'fundraising' => [
                'route' => '/donating/fundraising/',
                'meta_title' => 'Fundraising | APES equipment and technology priorities',
                'title' => 'Fundraising',
                'description' => 'Review current fundraising priorities that support APES welfare operations, equipment and supporter services.',
                'hero_kicker' => 'Fundraising priorities',
                'hero_title' => 'Help fund equipment, systems and practical welfare tools.',
                'hero_summary' => 'See which tools, equipment and systems currently need support so APES can keep welfare services running well.',
                'hero_actions' => [
                    ['label' => 'Support a priority', 'href' => '/donate/', 'variant' => 'primary'],
                    ['label' => 'Contact about fundraising', 'href' => '/contact/', 'variant' => 'secondary'],
                ],
                'pills' => ['Equipment needs', 'Operational tools', 'Supporter services'],
                'body_html' => <<<'HTML'
[[FEATURE_MEDIA:fundraising-priorities-visual]]

<section class="section-shell">
  <p>The legacy page says that from time to time things break down, new technology is needed, and practical equipment has to be replaced quickly to keep services moving.</p>
  <div class="card-grid card-grid-two">
    <article class="info-card">
      <h3>Members software</h3>
      <p>Software and systems that support supporter and member administration.</p>
    </article>
    <article class="info-card">
      <h3>Till system</h3>
      <p>The live page links this item to improving client and customer service.</p>
    </article>
    <article class="info-card">
      <h3>Scales</h3>
      <p>Essential monitoring equipment to support routine welfare and care standards.</p>
    </article>
    <article class="info-card">
      <h3>iPads and steel tables</h3>
      <p>Operational tools that help APES work with animals, track needs and improve daily handling workflows.</p>
    </article>
  </div>
</section>

<section class="note-panel">
  <h2>Fundraising content note</h2>
  <p>The public page contains repeated donor-wall placeholders and some duplicated supporting wording. This rebuild keeps the priorities visible while recording those content issues in the audit for APES review.</p>
</section>

<section class="section-shell split-panel">
  <div class="note-panel">
    <h2>Current public priorities</h2>
    <p>Members software, till systems, scales, iPads and steel tables remain the clearest verified priorities in the public source. APES should add target amounts, item owners and update dates before publishing more granular campaign claims.</p>
  </div>
  <div class="note-panel">
    <h2>How support is used</h2>
    <p>These routes exist to strengthen practical animal care, daily operations and supporter service quality rather than to create abstract fundraising categories without operational context.</p>
  </div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Donate', 'href' => '/donate/'],
                    ['label' => 'Enterprise mailing list', 'href' => '/enterprise-mailing-list/'],
                ],
            ],
            'animal-causes' => [
                'route' => '/animal-causes/',
                'meta_title' => 'Animal causes | Welfare priorities supported by APES',
                'title' => 'Animal causes',
                'description' => 'Animal welfare, conservation and responsible ownership causes supported by APES CIC.',
                'hero_kicker' => 'Causes',
                'hero_title' => 'Support causes that strengthen welfare, education and conservation.',
                'hero_summary' => 'Explore the welfare, education and conservation causes that shape APES rescue work and public support activity.',
                'hero_actions' => [
                    ['label' => 'Support a cause', 'href' => '/donate/', 'variant' => 'primary'],
                    ['label' => 'Read the mission statement', 'href' => '/mission/our-main-mission-statement/', 'variant' => 'secondary'],
                ],
                'pills' => ['Rescue', 'Rehabilitation', 'Education'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Emergency rescue</h3>
      <p>Responding when exotic pets or other animals are abandoned, displaced or unsafe.</p>
    </article>
    <article class="info-card">
      <h3>Rehabilitation and rehoming</h3>
      <p>Helping animals recover, stabilise and move toward long-term welfare outcomes.</p>
    </article>
    <article class="info-card">
      <h3>Education and prevention</h3>
      <p>Reducing avoidable harm through practical guidance, outreach and responsible ownership support.</p>
    </article>
  </div>
</section>

<section class="section-shell">
  <p>Across the current public website, APES also emphasises conservation awareness, non-judgemental support, sustainability and accountability. This rebuilt page acts as a clear cause overview without inventing new policy commitments.</p>
</section>

<section class="note-panel">
  <h2>Campaigns and updates</h2>
  <p>Use this page as a stable overview of APES welfare causes, but expect time-sensitive campaign updates, appeals and public stories to continue through APES Newsroom rather than through a separate campaign archive on this site.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Our main mission statement', 'href' => '/mission/our-main-mission-statement/'],
                    ['label' => 'Donate', 'href' => '/donate/'],
                ],
            ],
            'mailing-lists' => [
                'route' => '/mailing-lists/',
                'meta_title' => 'Mailing lists | Stay updated with APES CIC',
                'title' => 'Mailing lists',
                'description' => 'Keep up with APES CIC updates through approved newsletter and mailing-list routes.',
                'hero_kicker' => 'Updates',
                'hero_title' => 'Choose the right APES updates route for you.',
                'hero_summary' => 'Choose the right updates route for supporter news, public announcements and enterprise or partner communications.',
                'hero_actions' => [
                    ['label' => 'Visit APES Newsroom', 'href' => APES_NEWSROOM_URL, 'external' => true, 'variant' => 'primary'],
                    ['label' => 'Enterprise mailing list', 'href' => '/enterprise-mailing-list/', 'variant' => 'secondary'],
                ],
                'pills' => ['Public updates', 'Partner updates', 'Newsroom route'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <p>For general public news, APES now routes update prompts through APES Newsroom so supporters can read current stories, guidance and notices in one central public destination.</p>
  <div class="card-grid card-grid-two">
    <article class="info-card">
      <h3>General updates</h3>
      <p>Use APES Newsroom for public announcements, appeals, service notices and cross-division updates.</p>
    </article>
    <article class="info-card">
      <h3>Enterprise and partners</h3>
      <p>Use the enterprise mailing-list route for corporate, social-enterprise and partnership-focused communications.</p>
    </article>
  </div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'APES Newsroom', 'href' => APES_NEWSROOM_URL, 'external' => true],
                    ['label' => 'Enterprise mailing list', 'href' => '/enterprise-mailing-list/'],
                ],
            ],
            'enterprise-mailing-list' => [
                'route' => '/enterprise-mailing-list/',
                'meta_title' => 'Enterprise mailing list | APES CIC business and partner updates',
                'title' => 'Enterprise mailing list',
                'description' => 'Register interest in enterprise and partner-focused APES updates through the approved sign-up route.',
                'hero_kicker' => 'Partnership updates',
                'hero_title' => 'Business, partner and social-enterprise communications.',
                'hero_summary' => 'Register interest in partnership, sponsor and social-enterprise communications through the approved external sign-up route.',
                'hero_actions' => [
                    ['label' => 'Open enterprise sign-up', 'href' => 'https://forms.zohopublic.eu/', 'external' => true, 'variant' => 'primary'],
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'secondary'],
                ],
                'pills' => ['Business partnerships', 'Sponsor interest', 'External sign-up'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <p>This route remains a simple bridge to the approved external form used for corporate, social-enterprise, sponsor and wider partnership communications. The rebuild does not duplicate that workflow locally.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'External enterprise sign-up', 'href' => 'https://forms.zohopublic.eu/', 'external' => true],
                    ['label' => 'Sponsors', 'href' => '/sponsors/'],
                ],
            ],
            'volunteer' => [
                'route' => '/volunteer/',
                'meta_title' => 'Volunteer and student placements | Support APES CIC',
                'title' => 'Volunteer and student placements',
                'description' => 'Find out how volunteer-led support and placements help APES CIC deliver animal welfare services.',
                'hero_kicker' => 'Volunteer-led organisation',
                'hero_title' => 'Help APES deliver specialist support with practical, respectful volunteering.',
                'hero_summary' => 'Find out how volunteering and placements help APES deliver welfare-led services, public support and day-to-day care.',
                'hero_actions' => [
                    ['label' => 'Apply to volunteer', 'href' => 'https://service.sheltermanager.com/asmservice?account=apes&method=online_form_html&formid=72', 'external' => true, 'variant' => 'primary'],
                    ['label' => 'Open help centre', 'href' => 'https://help.apes.org.uk/', 'external' => true, 'variant' => 'secondary'],
                ],
                'pills' => ['Volunteer-led', 'Placements', 'Respectful support'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="note-panel">
    <h2>Apply through the approved volunteer form</h2>
    <p>Use the APES volunteer application form to register your interest. APES will review suitability, availability, training needs and role fit before confirming any volunteer placement.</p>
    <div class="action-row">
      <a class="button button-primary" href="https://service.sheltermanager.com/asmservice?account=apes&amp;method=online_form_html&amp;formid=72" target="_blank" rel="noopener noreferrer">Apply to volunteer</a>
    </div>
  </div>
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Animal care and cleaning</h3>
      <p>Support supervised animal areas with cleaning, bedding changes, enclosure resets and routine welfare checks.</p>
    </article>
    <article class="info-card">
      <h3>Feeding and enrichment</h3>
      <p>Help prepare feeds, refresh water, set up enrichment and support species-appropriate daily routines.</p>
    </article>
    <article class="info-card">
      <h3>Housekeeping and laundry</h3>
      <p>Keep rescue spaces supplied with clean towels, bedding, carriers, bowls and practical day-to-day equipment.</p>
    </article>
  </div>
</section>

<section class="section-shell">
  <h2>Volunteer roles APES may need</h2>
  <p>Volunteer needs can change with rescue intake, animal welfare priorities, staff capacity and public demand. These role types are examples of support a rescue may need; APES will match applicants to safe, supervised work where there is a suitable vacancy.</p>
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Transport support</h3>
      <p>Assist with collections, transfers, appointment travel and supply runs where vehicle, insurance and handling requirements are met.</p>
    </article>
    <article class="info-card">
      <h3>Foster care support</h3>
      <p>Provide suitable temporary care or holding support only where APES has assessed the home, species needs and safeguarding expectations.</p>
    </article>
    <article class="info-card">
      <h3>Adoption and home checks</h3>
      <p>Support rehoming follow-up, adopter preparation and home-check activity after training and under APES procedures.</p>
    </article>
    <article class="info-card">
      <h3>Reception and enquiries</h3>
      <p>Help visitors and callers reach the right APES route, with clear signposting and respectful public-facing communication.</p>
    </article>
    <article class="info-card">
      <h3>Fundraising and events</h3>
      <p>Support community stalls, campaigns, donation drives, sponsor activity and public awareness events.</p>
    </article>
    <article class="info-card">
      <h3>Education and outreach</h3>
      <p>Help with talks, learning resources, responsible ownership messaging and supervised public education activity.</p>
    </article>
    <article class="info-card">
      <h3>Retail and donation sorting</h3>
      <p>Sort donated goods, prepare stock, support shop or stall activity and keep fundraising materials organised.</p>
    </article>
    <article class="info-card">
      <h3>Site maintenance</h3>
      <p>Help with gardening, basic repairs, safe storage, practical improvements and centre upkeep where skills and supervision fit.</p>
    </article>
    <article class="info-card">
      <h3>Administration and records</h3>
      <p>Support data entry, follow-up calls, document organisation and routine admin while protecting sensitive information.</p>
    </article>
    <article class="info-card">
      <h3>Photography and profiles</h3>
      <p>Help create animal photos, adoption profiles, campaign materials and social updates in line with APES privacy and welfare standards.</p>
    </article>
    <article class="info-card">
      <h3>Student placements</h3>
      <p>Coordinate placement interest through approved APES routes so training, supervision and suitability can be reviewed before any placement.</p>
    </article>
    <article class="info-card">
      <h3>Specialist support</h3>
      <p>Offer qualified help with veterinary nursing, behaviour, species care, safeguarding, finance, compliance, IT or other professional skills where appropriate.</p>
    </article>
  </div>
</section>

<section class="note-panel">
  <h2>Before you apply</h2>
  <p>Because volunteering can involve public-facing support and animal welfare activity, APES may need to assess suitability, training needs, availability and role fit before confirming a placement.</p>
</section>

<section class="section-shell split-panel">
  <div class="note-panel">
    <h2>Role matching</h2>
    <p>APES should continue to match applicants to practical, supervised roles rather than promise immediate placement. Some roles may be occasional, seasonal, remote, centre-based or limited by animal welfare need.</p>
  </div>
  <div class="note-panel">
    <h2>Checks and onboarding</h2>
    <p>Depending on the role, APES may need to review age suitability, availability, safeguarding expectations, training needs, DBS-related considerations and induction requirements before confirming a placement.</p>
  </div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Volunteer application form', 'href' => 'https://service.sheltermanager.com/asmservice?account=apes&method=online_form_html&formid=72', 'external' => true],
                    ['label' => 'Contact centre', 'href' => 'https://contact.apes.org.uk/', 'external' => true],
                    ['label' => 'Help centre', 'href' => 'https://help.apes.org.uk/', 'external' => true],
                ],
            ],
            'our-main-mission-statement' => [
                'route' => '/mission/our-main-mission-statement/',
                'meta_title' => 'Our main mission statement | APES CIC',
                'title' => 'Our main mission statement',
                'description' => 'Read the public APES CIC mission focused on protection, rescue, rehabilitation, education and responsible support.',
                'hero_kicker' => 'Mission',
                'hero_title' => 'Protecting exotic pets and empowering owners.',
                'hero_summary' => 'Read the APES mission built around rescue, rehabilitation, rehoming, responsible ownership and practical support for people who need help.',
                'hero_actions' => [
                    ['label' => 'Support ethical rehabilitation', 'href' => '/mission/support-ethical-rehabilitation/', 'variant' => 'primary'],
                    ['label' => 'Donate', 'href' => '/donate/', 'variant' => 'secondary'],
                ],
                'pills' => ['Rescue', 'Rehabilitate', 'Rehome'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">The three Rs</p>
    <h2>Rescue, rehabilitate and rehome with practical owner support.</h2>
  </div>
  <div class="card-grid card-grid-three">
    <article class="metric-card">
      <strong>Rescue</strong>
      <span>Respond when exotic pets are abandoned, at risk or in distress.</span>
    </article>
    <article class="metric-card">
      <strong>Rehabilitate</strong>
      <span>Provide housing, nutrition and recovery support that is appropriate to the species.</span>
    </article>
    <article class="metric-card">
      <strong>Rehome</strong>
      <span>Match animals with responsible adopters who are prepared for the realities of specialist care.</span>
    </article>
  </div>
</section>

<section class="section-shell">
  <p>The public mission also emphasises advice, education and support that help owners keep pets at home safely where appropriate, alongside stronger understanding of conservation, welfare and responsible ownership.</p>
  <div class="card-grid card-grid-two">
    <article class="info-card">
      <h3>Owner support and prevention</h3>
      <p>APES frames public support as practical, non-judgemental guidance that can sometimes prevent avoidable surrender, unsafe keeping or delayed welfare action.</p>
    </article>
    <article class="info-card">
      <h3>Public benefit and trust</h3>
      <p>Mission content supports rescue outcomes, responsible rehoming, public education and transparent supporter confidence without relying on unverified headline figures.</p>
    </article>
  </div>
</section>

<section class="section-shell split-panel">
  <div class="note-panel">
    <h2>Frequently asked mission questions</h2>
    <ul class="clean-list">
      <li>How does APES decide whether an animal needs rescue, rehabilitation or rehoming?</li>
      <li>When can practical support help an owner keep a pet safely at home?</li>
      <li>How do donation, volunteering and public routes support long-term welfare outcomes?</li>
    </ul>
  </div>
  <div class="note-panel">
    <h2>Supporter actions</h2>
    <p>Use the Services hub to find the correct route, donate where operational support is needed, and review welfare policies before making assumptions about adoption, surrender or care processes.</p>
  </div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Support ethical rehabilitation', 'href' => '/mission/support-ethical-rehabilitation/'],
                    ['label' => 'About APES', 'href' => '/about-us/'],
                ],
            ],
            'support-ethical-rehabilitation' => [
                'route' => '/mission/support-ethical-rehabilitation/',
                'meta_title' => 'Support ethical exotic animal rehabilitation | APES CIC',
                'title' => 'Support ethical exotic animal rehabilitation',
                'description' => 'Read how APES describes ethical rehabilitation, responsible care and welfare-first recovery.',
                'hero_kicker' => 'Ethical care',
                'hero_title' => 'Recovery that protects welfare, dignity and long-term outcomes.',
                'hero_summary' => 'See how APES frames ethical rehabilitation through species-appropriate care, responsible decisions and long-term welfare outcomes.',
                'hero_actions' => [
                    ['label' => 'Donate to rehabilitation work', 'href' => '/donate/', 'variant' => 'primary'],
                    ['label' => 'Read re-homing policy', 'href' => '/policies/re-homing-policy/', 'variant' => 'secondary'],
                ],
                'pills' => ['Recovery standards', 'Welfare-first', 'Owner support'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="card-grid card-grid-two">
    <article class="info-card">
      <h3>1. Safe intake and assessment</h3>
      <p>APES frames rehabilitation as beginning with safe intake, species-aware assessment and stabilisation of immediate welfare needs.</p>
    </article>
    <article class="info-card">
      <h3>2. Recovery and husbandry</h3>
      <p>Recovery focuses on housing, nutrition, handling and environmental conditions that fit the animal rather than a generic shelter routine.</p>
    </article>
    <article class="info-card">
      <h3>3. Outcome planning</h3>
      <p>Next steps may include continued rehabilitation, rehoming, long-term sanctuary decisions or other welfare-led planning based on the individual case.</p>
    </article>
    <article class="info-card">
      <h3>4. Owner and supporter role</h3>
      <p>Owner education, sponsor support and public trust all contribute to better long-term outcomes when prevention or recovery is still possible.</p>
    </article>
  </div>
</section>

<section class="note-panel">
  <h2>Ethical rehabilitation principles</h2>
  <p>APES presents rehabilitation as species-appropriate, non-judgemental and outcome-focused. The public route should explain why some cases move toward rehoming while others require longer-term care, closer assessment or more limited options.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Main mission statement', 'href' => '/mission/our-main-mission-statement/'],
                    ['label' => 'Adoption policy', 'href' => '/policies/adoption-policy/'],
                ],
            ],
            'the-center' => [
                'route' => '/the-center/',
                'meta_title' => 'The centre | APES public location overview',
                'title' => 'Visit the centre',
                'description' => 'A public walkthrough of the APES centre, including reception, animal spaces and accessibility notes.',
                'hero_kicker' => 'Visit and access',
                'hero_title' => 'Plan a visit to the APES centre.',
                'hero_summary' => 'Use this page for the clearest current public access, parking, appointment and wayfinding guidance published for the APES centre.',
                'hero_actions' => [
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'primary'],
                    ['label' => 'Opening times', 'href' => '/opening-times/', 'variant' => 'secondary'],
                ],
                'pills' => ['Visitor information', 'Access notes', 'Centre overview'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Front entrance</h3>
      <p>The public copy notes a small car park and ramp access at the entrance.</p>
    </article>
    <article class="info-card">
      <h3>Reception</h3>
      <p>Visitors are welcomed and directed from reception.</p>
    </article>
    <article class="info-card">
      <h3>Small animal walkthrough</h3>
      <p>A public-facing walkthrough area for some adoption-ready animals.</p>
    </article>
    <article class="info-card">
      <h3>Stairs and wayfinding</h3>
      <p>The legacy page includes partially completed image captions for stairs and access routes.</p>
    </article>
    <article class="info-card">
      <h3>Exotic adoption room</h3>
      <p>Described as the space where healthy, adoptable animals wait for their forever homes.</p>
    </article>
    <article class="info-card">
      <h3>Pet products area</h3>
      <p>The centre also connects with the APES Pet Shop offering.</p>
    </article>
  </div>
</section>

<section class="section-shell split-panel">
  <div class="note-panel">
    <h2>Before you attend</h2>
    <p>Use the contact route or an agreed appointment before visiting where that is the current APES policy. This page is a public access guide, not a promise of unscheduled drop-in availability.</p>
  </div>
  <div class="note-panel">
    <h2>Accessibility and review note</h2>
    <p>The legacy source includes unfinished captions and developing content. This rebuilt route keeps the verified access descriptors while recording where APES should still approve clearer accessibility, parking and visitor-expectation wording.</p>
  </div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                    ['label' => 'About APES', 'href' => '/about-us/'],
                ],
            ],
            'bookings' => [
                'route' => '/bookings/',
                'meta_title' => 'Bookings | APES service booking routes',
                'title' => 'Bookings',
                'description' => 'Use APES contact and connected service routes to arrange visits, support and other bookings.',
                'hero_kicker' => 'Booking routes',
                'hero_title' => 'Book through the right APES service channel.',
                'hero_summary' => 'Use the right APES booking route for boarding, visits and general service enquiries without guessing which service to contact.',
                'hero_actions' => [
                    ['label' => 'Contact APES to book', 'href' => '/contact/', 'variant' => 'primary'],
                    ['label' => 'Boarding portal', 'href' => 'https://petmanager.app/', 'external' => true, 'variant' => 'secondary'],
                ],
                'pills' => ['Booking guidance', 'Service routes', 'External portals'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <p>Bookings remains the narrower route for arranging visits, boarding and practical service conversations after the wider Services hub has helped you choose the right APES pathway.</p>
</section>

[[FEATURE_MEDIA:bookings-care]]

<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">Booking chooser</p>
    <h2>Choose the booking route that matches your service.</h2>
  </div>
  <p>Use these direct booking routes when you already know which APES service you need. Each option opens the external appointment form in a focused popup window, with a standard new-tab fallback if popup windows are blocked.</p>
  <div class="card-grid card-grid-three booking-choice-grid">
    <article class="info-card booking-choice-card">
      <h3>APES Bookings</h3>
      <p>Use the main APES booking route for general APES appointments, practical booking conversations and service visits handled through the core APES workspace.</p>
      <div class="action-stack">
        <a
          class="button button-primary"
          href="https://workspace.apes.org.uk/apps/appointments/pub/2Gw5w18rbDQ9C9A3/form"
          target="_blank"
          rel="noopener noreferrer"
          data-popup-link
          data-popup-name="apes-bookings"
          data-popup-width="900"
          data-popup-height="760"
        >APES Bookings</a>
      </div>
    </article>
    <article class="info-card booking-choice-card">
      <h3>Shelter and Rescue Bookings</h3>
      <p>Use this route for Shelter &amp; Rescue appointment needs where the booking belongs with rescue, handover, shelter or welfare-support activity.</p>
      <div class="action-stack">
        <a
          class="button button-primary"
          href="https://workspace.apes.org.uk/apps/appointments/pub/rlaztMEqccVqwBcc/form"
          target="_blank"
          rel="noopener noreferrer"
          data-popup-link
          data-popup-name="shelter-rescue-bookings"
          data-popup-width="900"
          data-popup-height="760"
        >Shelter and Rescue Bookings</a>
      </div>
    </article>
    <article class="info-card booking-choice-card">
      <h3>Pet Care Clinic Bookings</h3>
      <p>Use this route for Pet Care Clinic appointments where you need low-cost care support, clinic-led guidance or other pet-care booking follow-up.</p>
      <div class="action-stack">
        <a
          class="button button-primary"
          href="https://workspace.apes.org.uk/apps/appointments/pub/CybCqq5VG5EA3vBV/form"
          target="_blank"
          rel="noopener noreferrer"
          data-popup-link
          data-popup-name="pet-care-clinic-bookings"
          data-popup-width="900"
          data-popup-height="760"
        >Pet Care Clinic Bookings</a>
      </div>
    </article>
  </div>
</section>

<section class="section-shell">
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Boarding</h3>
      <p>Use the external boarding portal for boarding stays and related packages.</p>
    </article>
    <article class="info-card">
      <h3>Educational visits and therapy enquiries</h3>
      <p>Use the APES contact route for visits, outreach, learning sessions and supported programme planning.</p>
    </article>
    <article class="info-card">
      <h3>Need help choosing first?</h3>
      <p>Start with the Services hub if you are not yet sure whether your case belongs with rescue, care, support or education.</p>
    </article>
  </div>
</section>

<section class="cta-band">
  <div>
    <p class="eyebrow">Need the wider route map?</p>
    <h2>Use the Services hub before booking when the route is unclear.</h2>
  </div>
  <div class="action-row">
    <a class="button button-primary" href="/services/">Open Services hub</a>
    <a class="button button-secondary" href="/contact/">Contact APES</a>
  </div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                    ['label' => 'Educational visits', 'href' => '/educational-visits/'],
                    ['label' => 'Pet boarding', 'href' => '/pet-boarding/'],
                ],
            ],
            'opening-times' => [
                'route' => '/opening-times/',
                'meta_title' => 'Opening times | APES public hours and contact guidance',
                'title' => 'Opening times',
                'description' => 'Current public opening hours for APES CIC and guidance on contacting the organisation outside standard hours.',
                'hero_kicker' => 'Public hours',
                'hero_title' => 'Current public hours and contact guidance.',
                'hero_summary' => 'Check the current public opening hours and find the best route for contact if you need help outside standard times.',
                'hero_actions' => [
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'primary'],
                    ['label' => '24/7 services', 'href' => '/24-7-services/', 'variant' => 'secondary'],
                ],
                'pills' => ['Opening hours', 'Out-of-hours contact', 'Service guidance'],
                'body_html' => <<<'HTML'
<section class="section-shell split-panel">
  <div class="note-panel">
    <h2>Public opening hours</h2>
    <p><strong>Monday to Sunday:</strong> 10:00 AM to 5:00 PM</p>
    <p>This page is the canonical public hours source for the rebuilt site and should be used ahead of older inconsistent mentions elsewhere.</p>
  </div>
  <div class="note-panel">
    <h2>Out-of-hours note</h2>
    <p>Out-of-hours support guidance should not be read as a guarantee of physical rescue attendance. Use the urgent guidance route where welfare, injury or public safety is immediate.</p>
  </div>
</section>

<section class="section-shell split-panel">
  <div class="note-panel">
    <h2>Before you travel</h2>
    <p>Use the contact route or a confirmed appointment where needed, especially if your visit depends on reception support, a booking, or access to a connected service rather than a general public enquiry.</p>
  </div>
  <div class="note-panel">
    <h2>Consistency note</h2>
    <p>The older site contained conflicting opening-hour wording. This rebuilt route keeps the public truth to 10:00 AM to 5:00 PM daily until APES approves a stronger verified source.</p>
  </div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                    ['label' => 'The centre', 'href' => '/the-center/'],
                ],
            ],
            'sponsors' => [
                'route' => '/sponsors/',
                'meta_title' => 'Sponsors | Partner support for APES CIC',
                'title' => 'Sponsors',
                'description' => 'APES recognises the role of supporters, sponsors and partner organisations in sustaining welfare services.',
                'hero_kicker' => 'Partners and sponsors',
                'hero_title' => 'Support from sponsors helps APES do more good.',
                'hero_summary' => 'Meet the sponsors and partners whose tools, services and practical backing help APES deliver stronger public support and welfare operations.',
                'hero_actions' => [
                    ['label' => 'Become a partner', 'href' => '/enterprise-mailing-list/', 'variant' => 'primary'],
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'secondary'],
                ],
                'pills' => ['Sponsor support', 'Digital infrastructure', 'Service delivery'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">Technology and service support</p>
    <h2>Software and infrastructure support helps APES stay responsive.</h2>
  </div>
  <p>APES thanks the organisations helping strengthen digital services, supporter journeys, operations and online resilience across the wider APES network.</p>
  <div class="card-grid card-grid-two sponsor-grid">
    <article class="sponsor-card">
      <div class="sponsor-card-logo-wrap">
        <img class="sponsor-card-logo" src="/assets/logos/sponsors/zoho-logo.png" alt="Zoho logo">
      </div>
      <h3>Zoho</h3>
      <p>Zoho provides APES-friendly software routes for forms, website services, CRM-style workflows and supporter communications.</p>
      <p><strong>How they help APES:</strong> Supports forms, operational workflows and connected digital services used across the organisation.</p>
      <a class="button button-secondary" href="https://www.zoho.com/" target="_blank" rel="noreferrer">Visit Zoho</a>
    </article>
    <article class="sponsor-card">
      <div class="sponsor-card-logo-wrap sponsor-card-logo-dark">
        <img class="sponsor-card-logo" src="/assets/logos/sponsors/shopify-logo.jpg" alt="Shopify logo">
      </div>
      <h3>Shopify</h3>
      <p>Shopify powers connected ecommerce routes that help APES sell products and build supporter-facing retail journeys.</p>
      <p><strong>How they help APES:</strong> Helps APES run online shop experiences that can contribute funds toward welfare and operational work.</p>
      <a class="button button-secondary" href="https://www.shopify.com/" target="_blank" rel="noreferrer">Visit Shopify</a>
    </article>
    <article class="sponsor-card">
      <div class="sponsor-card-logo-wrap">
        <img class="sponsor-card-logo" src="/assets/logos/sponsors/akamai-logo.png" alt="Akamai logo">
      </div>
      <h3>Akamai</h3>
      <p>Akamai is recognised for cloud delivery, performance and security services that help organisations protect digital experiences.</p>
      <p><strong>How they help APES:</strong> Strengthens website performance, content delivery and online resilience for public-facing services.</p>
      <a class="button button-secondary" href="https://www.akamai.com/" target="_blank" rel="noreferrer">Visit Akamai</a>
    </article>
    <article class="sponsor-card">
      <div class="sponsor-card-logo-wrap">
        <img class="sponsor-card-logo" src="/assets/logos/sponsors/cpanel-logo.png" alt="cPanel logo">
      </div>
      <h3>cPanel</h3>
      <p>cPanel is widely used for website and hosting management, helping teams look after domains, files, email and server-side administration.</p>
      <p><strong>How they help APES:</strong> Supports hosting management and practical website administration for connected APES services.</p>
      <a class="button button-secondary" href="https://www.cpanel.net/" target="_blank" rel="noreferrer">Visit cPanel</a>
    </article>
  </div>
</section>

<section class="section-shell split-panel">
  <div class="note-panel">
    <h2>Why sponsorship matters</h2>
    <p>Sponsorship helps APES cover software, website infrastructure, communications tooling and operational systems that keep welfare services visible and usable.</p>
  </div>
  <div class="note-panel">
    <h2>How to express interest</h2>
    <p>Use the enterprise mailing-list route or the APES contact page for sponsorship, grant, software support or wider partnership conversations.</p>
  </div>
</section>

<section class="section-shell split-panel">
  <div class="note-panel">
    <h2>Current sponsor-style support</h2>
    <p>Zoho, Shopify, Akamai and cPanel are the clearest verified sponsor-style or software-support routes in the current public source and are shown here as the main named supporters.</p>
  </div>
  <div class="note-panel">
    <h2>Partner and network organisations</h2>
    <p>BAS, FBH and Merseyside Police are currently better represented as partner or connected organisations in the footer network area than as full sponsor cards on this page.</p>
  </div>
</section>

<section class="note-panel">
  <h2>Review and categorisation note</h2>
  <p>The current verified public list is strongest for Zoho, Shopify, Akamai and cPanel in sponsor-style roles, while BAS, FBH and Merseyside Police are currently surfaced as partner or connected organisations. APES should approve any future change to sponsor, partner, software-supporter or membership labels before this page grows further.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Enterprise mailing list', 'href' => '/enterprise-mailing-list/'],
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                ],
            ],
            'about-us' => [
                'route' => '/about-us/',
                'meta_title' => 'About APES CIC | Rescue, rehabilitation and public support',
                'title' => 'About APES',
                'description' => 'Learn about APES CIC, the three Rs, volunteer-led operations and the welfare-first approach behind the organisation.',
                'hero_kicker' => 'About the organisation',
                'hero_title' => 'A volunteer-led APES CIC committed to the three Rs.',
                'hero_summary' => 'Learn how APES CIC works across rescue, rehabilitation, rehoming and public support while staying volunteer-led and welfare-first.',
                'hero_actions' => [
                    ['label' => 'Read the mission statement', 'href' => '/mission/our-main-mission-statement/', 'variant' => 'primary'],
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'secondary'],
                ],
                'pills' => ['Welfare-first', 'Volunteer-led', 'Non-profit'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">The three Rs</p>
    <h2>Rescue, rehabilitate and rehome.</h2>
  </div>
  <p>The live page describes APES as a small shelter and rescue service supporting people with specialist exotic pets. It explains rescue as responding when animals are in need, rehabilitation as helping them recover with proper care, and rehoming as finding suitable adoptive homes.</p>
</section>

[[FEATURE_MEDIA:about-three-rs]]

<section class="section-shell">
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Transparency</h3>
      <p>The public copy says APES tries to be transparent and publish data that it can share publicly.</p>
    </article>
    <article class="info-card">
      <h3>All staff are volunteers</h3>
      <p>The live site asks the public to treat unpaid volunteers with respect.</p>
    </article>
    <article class="info-card">
      <h3>Non-profit operation</h3>
      <p>Fees, product sales and donations support the rescue centre and related services.</p>
    </article>
  </div>
</section>

<section class="note-panel">
  <h2>Content review note</h2>
  <p>The live page contains copy issues such as "Satuday", "center" and other wording inconsistencies. Those are captured in the content audit for APES review rather than silently rewritten into policy-like commitments.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Mission statement', 'href' => '/mission/our-main-mission-statement/'],
                    ['label' => 'The centre', 'href' => '/the-center/'],
                ],
            ],
            'socials' => [
                'route' => '/socials/',
                'meta_title' => 'Socials | Official APES public channels',
                'title' => 'Socials',
                'description' => 'Find the main public APES social, streaming and community routes in one place.',
                'hero_kicker' => 'Public channels',
                'hero_title' => 'Official APES social and supporter channels.',
                'hero_summary' => 'Find the main APES social, streaming and supporter channels in one place so you can follow updates in the way that suits you best.',
                'hero_actions' => [
                    ['label' => 'Follow APES on Facebook', 'href' => 'https://www.facebook.com/apesorguk', 'external' => true, 'variant' => 'primary'],
                    ['label' => 'Read APES Newsroom', 'href' => APES_NEWSROOM_URL, 'external' => true, 'variant' => 'secondary'],
                ],
                'pills' => ['Social channels', 'Supporter updates', 'Connected services'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">Verified public profiles</p>
    <h2>Primary public APES channels across the web.</h2>
  </div>
  [[SOCIAL_PRIMARY_GRID]]
</section>

<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">Connected communities</p>
    <h2>Secondary or community-led APES routes.</h2>
  </div>
  [[SOCIAL_COMMUNITY_GRID]]
</section>

<section class="note-panel">
  <h2>Verification note</h2>
  <p>The public header now uses only verified <strong>apesorguk</strong> profiles. Community-only routes remain here, while unverified consumer-social <strong>apescic</strong> accounts stay excluded until APES confirms them.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'APES communities', 'href' => '/apes-communities/'],
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                    ['label' => 'APES Newsroom', 'href' => APES_NEWSROOM_URL, 'external' => true],
                ],
            ],
            'apes-communities' => [
                'route' => '/apes-communities/',
                'meta_title' => 'APES communities | Public supporter and community routes',
                'title' => 'APES communities',
                'description' => 'Browse the main public APES community routes, supporter spaces and connected social channels.',
                'hero_kicker' => 'Community routes',
                'hero_title' => 'Find the APES communities that best fit your role.',
                'hero_summary' => 'Choose the community route that best fits your role, whether you want supporter updates, discussion or a connected APES platform.',
                'hero_actions' => [
                    ['label' => 'Join Discord community', 'href' => 'https://discord.gg/', 'external' => true, 'variant' => 'primary'],
                    ['label' => 'Visit MyAPES', 'href' => 'https://www.myapes.me.uk/', 'external' => true, 'variant' => 'secondary'],
                ],
                'pills' => ['Community spaces', 'Supporter routes', 'External platforms'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <p>APES uses external community spaces for conversation, supporter updates and engagement. The rebuilt site keeps those routes visible, but does not recreate account systems, moderation tools or message history locally.</p>
  <div class="card-grid card-grid-two">
    <article class="info-card">
      <h3>Supporter and public communities</h3>
      <p>Use public social and Discord channels for general updates, community discussion and outward-facing engagement.</p>
    </article>
    <article class="info-card">
      <h3>Members and portal journeys</h3>
      <p>Routes such as MyAPES remain external because they belong to separate APES account or portal systems.</p>
    </article>
  </div>
</section>
<section class="note-panel">
  <h2>Live-site review note</h2>
  <p>The current public APES Communities page contains obvious placeholder copy such as "The face of the moon was in shadow". The rebuild replaces that with a neutral community-route overview and records the placeholder issue in the content audit.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Socials', 'href' => '/socials/'],
                    ['label' => 'MyAPES', 'href' => 'https://www.myapes.me.uk/', 'external' => true],
                    ['label' => 'Volunteer', 'href' => '/volunteer/'],
                ],
            ],
            'staff' => [
                'route' => '/staff/',
                'meta_title' => 'Staff and volunteers | APES CIC public team overview',
                'title' => 'Staff and volunteers',
                'description' => 'Public information about the volunteer-led APES CIC team and how the organisation frames its staffing model.',
                'hero_kicker' => 'Team overview',
                'hero_title' => 'A volunteer-led organisation with public-facing transparency notes.',
                'hero_summary' => 'Read the public overview of APES staff and volunteer roles, with the organisation\'s emphasis on respect, transparency and welfare-first service.',
                'hero_actions' => [
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'primary'],
                    ['label' => 'Volunteer with APES', 'href' => '/volunteer/', 'variant' => 'secondary'],
                ],
                'pills' => ['Volunteer-led team', 'Public roles', 'Respect staff and volunteers'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">The board</p>
    <h2>Public names and roles currently shown on the live staff page.</h2>
  </div>
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Board roles</h3>
      <p>The live page currently lists Mr Murphy as Director, alongside organisation-secretary roles including Mr A Carman, Mr D Smith, Miss A Sidwell and Barry Chadwick.</p>
    </article>
    <article class="info-card">
      <h3>Management and supervisors</h3>
      <p>Public management names currently shown include Mrs A Chadwick as Reception Manager and supervisors such as Rae Hitchcock, Matthew Bolas and Abi Gilpin.</p>
    </article>
    <article class="info-card">
      <h3>Volunteer-led operations</h3>
      <p>The wider public site says APES is volunteer-led and asks visitors to treat staff and volunteers respectfully while services operate under pressure.</p>
    </article>
  </div>
</section>
<section class="note-panel">
  <h2>Content review note</h2>
  <p>The live staff page uses "Book Appointment" calls to action for listed people. The rebuild preserves the public team structure but does not recreate personal booking flows locally.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'About APES', 'href' => '/about-us/'],
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                ],
            ],
            'terms-of-service' => [
                'route' => '/policies/terms-of-service/',
                'meta_title' => 'Terms of Service | APES CIC',
                'title' => 'Terms of service',
                'description' => 'Public terms and conditions for APES CIC websites.',
                'hero_kicker' => 'Policy',
                'hero_title' => 'Terms and conditions for APES CIC public websites.',
                'hero_summary' => 'Read the public terms that apply to APES websites, services, external links and online payments.',
                'hero_actions' => [
                    ['label' => 'Privacy Policy', 'href' => '/policies/privacy/', 'variant' => 'primary'],
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'secondary'],
                ],
                'pills' => ['Terms of use', 'Website access', 'Payments and services'],
                'body_html' => <<<'HTML'
<section class="policy-stack">
  <article class="policy-block">
    <h2>Effective date</h2>
    <p>11 April 2025</p>
  </article>
  <article class="policy-block">
    <h2>1. Introduction</h2>
    <p>These Terms and Conditions govern your use of websites operated by Association of Protecting Exotic Species CIC, including but not limited to www.apes.org.uk, www.apesshelter.org.uk, www.apespetcare.org.uk, www.apesshop.co.uk and www.myapes.me.uk.</p>
  </article>
  <article class="policy-block">
    <h2>2. Acceptance of terms</h2>
    <p>By accessing or using any APES website, you agree to be bound by these terms and the Privacy Policy. If you do not agree, please refrain from using the websites.</p>
  </article>
  <article class="policy-block">
    <h2>3. Modifications to terms</h2>
    <p>APES CIC may modify or update these terms at any time. Continued use after changes constitutes acceptance of the updated terms.</p>
  </article>
  <article class="policy-block">
    <h2>4. Use of the websites</h2>
    <p>You agree to use the websites only for lawful purposes. Content is provided for informational purposes only and should not be treated as legal, financial or professional advice.</p>
  </article>
  <article class="policy-block">
    <h2>5. Intellectual property</h2>
    <p>All content, trademarks, logos and materials on the websites are owned by APES CIC or used with permission. They may not be reproduced or distributed without written consent.</p>
  </article>
  <article class="policy-block">
    <h2>6. User accounts</h2>
    <p>If you create an account on any APES website, you are responsible for accurate information, credential confidentiality and reporting suspected unauthorised use.</p>
  </article>
  <article class="policy-block">
    <h2>7. Payments and partnership with Stripe</h2>
    <p>Some APES websites offer products, services or donations involving online payments. Payment processing may be handled by Stripe, and related payment activity is governed by Stripe terms.</p>
  </article>
  <article class="policy-block">
    <h2>8. Limitation of liability</h2>
    <p>To the fullest extent permitted by law, APES CIC is not liable for direct, indirect, incidental, consequential or punitive damages arising from or related to use of the websites.</p>
  </article>
  <article class="policy-block">
    <h2>9. Indemnification</h2>
    <p>You agree to indemnify and hold harmless APES CIC and its affiliates, officers, volunteers and agents from claims or losses connected with your use of the websites or breach of these terms.</p>
  </article>
  <article class="policy-block">
    <h2>10. Governing law and jurisdiction</h2>
    <p>These terms are governed by the laws of England and Wales, and disputes are subject to the exclusive jurisdiction of the courts of England and Wales.</p>
  </article>
  <article class="policy-block">
    <h2>11. Dispute resolution</h2>
    <p>The public page states that parties should first attempt to resolve disputes through mediation or arbitration prior to pursuing legal remedies.</p>
  </article>
  <article class="policy-block">
    <h2>12. Third-party links</h2>
    <p>APES websites may contain links to third-party sites for convenience. APES does not endorse or assume responsibility for those external sites, and access is at your own risk.</p>
  </article>
  <article class="policy-block">
    <h2>13. Termination</h2>
    <p>APES CIC may terminate or suspend access to the websites at any time, without notice, including for breach of the terms.</p>
  </article>
  <article class="policy-block">
    <h2>14. Severability</h2>
    <p>If any provision is invalid or unenforceable, the remaining provisions remain in force.</p>
  </article>
  <article class="policy-block">
    <h2>15. Contact information</h2>
    <ul class="clean-list">
      <li>Address: 40 Morris Street, St Helens, Merseyside, WA9 3EN</li>
      <li>Email: legal@apes.org.uk</li>
      <li>Phone: 01744 374 015</li>
    </ul>
  </article>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Privacy Policy', 'href' => '/policies/privacy/'],
                    ['label' => 'Cookie guidance', 'href' => '/policies/cookies/'],
                ],
            ],
            'policies' => [
                'route' => '/policies/',
                'meta_title' => 'Policies hub | APES CIC',
                'title' => 'Policies hub',
                'description' => 'Browse APES welfare policies, legal website policies and related public guidance in one place.',
                'hero_kicker' => 'Policy hub',
                'hero_title' => 'Welfare, legal and public policy guidance in one place.',
                'hero_summary' => 'Use the Policies hub to distinguish between welfare decision policies, website legal policies and related APES public guidance before you rely on a route or process.',
                'hero_actions' => [
                    ['label' => 'Read Adoption Policy', 'href' => '/policies/adoption-policy/', 'variant' => 'primary'],
                    ['label' => 'Read Terms of Service', 'href' => '/policies/terms-of-service/', 'variant' => 'secondary'],
                ],
                'pills' => ['Welfare policies', 'Legal website policies', 'Public guidance'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">Welfare policies</p>
    <h2>Policies that explain how APES approaches animal welfare decisions.</h2>
  </div>
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Adoption Policy</h3>
      <p>Review the public adoption conditions, good-faith information notes and adopter responsibility guidance.</p>
      <a class="text-link" href="/policies/adoption-policy/">Open Adoption Policy</a>
    </article>
    <article class="info-card">
      <h3>Re-Homing Policy</h3>
      <p>Read the public re-homing safeguards, stray-hold expectations and welfare checks described by APES.</p>
      <a class="text-link" href="/policies/re-homing-policy/">Open Re-Homing Policy</a>
    </article>
    <article class="info-card">
      <h3>Euthanasia Policy</h3>
      <p>Read the welfare-threshold guidance published by APES for individual euthanasia decisions.</p>
      <a class="text-link" href="/policies/euthanasia-policy/">Open Euthanasia Policy</a>
    </article>
  </div>
</section>

<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">Legal and website policies</p>
    <h2>Policies that govern the public website and personal-data handling.</h2>
  </div>
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Privacy Policy</h3>
      <p>Read how APES describes collection, use and protection of personal information across connected websites and services.</p>
      <a class="text-link" href="/policies/privacy/">Open Privacy Policy</a>
    </article>
    <article class="info-card">
      <h3>Terms of service</h3>
      <p>Read the legal terms that apply to use of APES websites, connected services, payments and external links.</p>
      <a class="text-link" href="/policies/terms-of-service/">Open Terms of service</a>
    </article>
    <article class="info-card">
      <h3>Cookie guidance</h3>
      <p>Review the current public cookie guidance and browser-control notes published for APES websites.</p>
      <a class="text-link" href="/policies/cookies/">Open Cookie guidance</a>
    </article>
  </div>
</section>

<section class="note-panel">
  <h2>How to use this hub</h2>
  <p>Welfare policies explain APES animal-care and decision-making approaches. Legal website policies explain how the public websites work. Use the contact route if you need clarification on which policy applies to your situation.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Adoption Policy', 'href' => '/policies/adoption-policy/'],
                    ['label' => 'Privacy Policy', 'href' => '/policies/privacy/'],
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                ],
            ],
            'privacy' => [
                'route' => '/policies/privacy/',
                'meta_title' => 'Privacy Policy | APES CIC',
                'title' => 'Privacy Policy',
                'description' => 'Public privacy guidance for APES CIC websites and connected services.',
                'hero_kicker' => 'Policy',
                'hero_title' => 'Privacy guidance for APES CIC public websites.',
                'hero_summary' => 'Read how APES describes the collection, use and protection of personal information across its public websites and connected services.',
                'hero_actions' => [
                    ['label' => 'Terms of Service', 'href' => '/policies/terms-of-service/', 'variant' => 'primary'],
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'secondary'],
                ],
                'pills' => ['Privacy', 'Data use', 'Third-party services'],
                'body_html' => <<<'HTML'
<section class="policy-stack">
  <article class="policy-block">
    <h2>Effective date</h2>
    <p>11 October 2023</p>
  </article>
  <article class="policy-block">
    <h2>1. Introduction</h2>
    <p>Association of Protecting Exotic Species CIC is committed to protecting your privacy. The live policy applies to APES public websites including www.apes.org.uk, www.apesshelter.org.uk, www.apespetcare.org.uk, www.apesshop.co.uk and www.myapes.me.uk.</p>
  </article>
  <article class="policy-block">
    <h2>2. Information we collect</h2>
    <p>The public policy says APES may collect personal information such as name, email, postal address, phone number, payment details handled by a secure payment partner, and account credentials where accounts exist.</p>
    <p>It also describes non-personal information such as browser type, operating system, IP address, device details and website usage data.</p>
  </article>
  <article class="policy-block">
    <h2>3. How APES uses information</h2>
    <ul class="clean-list">
      <li>Provide, operate and maintain the websites.</li>
      <li>Process transactions and payments with partners such as Stripe.</li>
      <li>Improve and personalise services.</li>
      <li>Communicate updates and service information.</li>
      <li>Understand website usage and help prevent fraud.</li>
    </ul>
  </article>
  <article class="policy-block">
    <h2>4. Cookies and tracking technologies</h2>
    <p>The public privacy policy says APES may use cookies and similar tracking technologies, and that some site functions may not work properly if cookies are refused.</p>
  </article>
  <article class="policy-block">
    <h2>5. Disclosure of your information</h2>
    <p>The public policy states that information may be shared with service providers, disclosed to meet legal requirements, or transferred as part of a merger, acquisition or sale of assets.</p>
  </article>
  <article class="policy-block">
    <h2>6. Third-party services</h2>
    <p>APES works with third-party services such as Stripe for secure payment handling, and those providers have their own privacy policies.</p>
  </article>
  <article class="policy-block">
    <h2>7. Data security</h2>
    <p>Standard security measures are used, but the policy notes that no electronic transmission or storage method can be guaranteed as completely secure.</p>
  </article>
  <article class="policy-block">
    <h2>8. Retention</h2>
    <p>Personal information is retained only for as long as necessary for the purposes described in the policy, unless law requires or permits longer retention.</p>
  </article>
  <article class="policy-block">
    <h2>9. Your rights</h2>
    <p>Depending on jurisdiction, users may have rights to access, correct, update or request deletion of personal information.</p>
  </article>
  <article class="policy-block">
    <h2>10. International data transfers</h2>
    <p>The public policy notes that information may be transferred to or maintained on systems outside the user's local jurisdiction.</p>
  </article>
  <article class="policy-block">
    <h2>11. Changes to the policy</h2>
    <p>APES may update the Privacy Policy at any time, and changes are effective on publication with an updated effective date.</p>
  </article>
  <article class="policy-block">
    <h2>12. Contact information</h2>
    <ul class="clean-list">
      <li>Address: 40 Morris Street, St Helens, Merseyside, WA9 3EN</li>
      <li>Email: legal@apes.org.uk</li>
      <li>Phone: 01744 374 015</li>
    </ul>
  </article>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Terms of Service', 'href' => '/policies/terms-of-service/'],
                    ['label' => 'Cookie guidance', 'href' => '/policies/cookies/'],
                ],
            ],
            'adoption-policy' => [
                'route' => '/policies/adoption-policy/',
                'meta_title' => 'Adoption Policy | APES CIC',
                'title' => 'Adoption Policy',
                'description' => 'Public adoption conditions published by APES CIC.',
                'hero_kicker' => 'Policy',
                'hero_title' => 'Animal adoption scheme conditions.',
                'hero_summary' => 'Review the public adoption conditions APES shares with adopters before an animal leaves its care.',
                'hero_actions' => [
                    ['label' => 'Re-homing policy', 'href' => '/policies/re-homing-policy/', 'variant' => 'primary'],
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'secondary'],
                ],
                'pills' => ['Adoption terms', 'Public guidance', 'Review required'],
                'body_html' => <<<'HTML'
<section class="policy-stack">
  <article class="policy-block">
    <h2>Animal adoption scheme conditions</h2>
    <p>Public adoption information is shared in good faith to help guide adopters through the process. The live page says this information may include details from previous owners, the Association inspectorate or observations made while the animal was in APES care.</p>
    <p>The page also states that while APES makes every effort to ensure information is accurate, it cannot guarantee that accuracy, and users should also review the re-homing policy.</p>
  </article>
  <article class="policy-block">
    <h2>Health and treatment note</h2>
    <p>The visible public condition says the animal is deemed to be in normal health when leaving the facility unless specific conditions are noted on the Animal Adoption Form. Future veterinary or treatment costs become the adopter's responsibility except where another clause states otherwise.</p>
  </article>
  <article class="policy-block">
    <h2>Adopter responsibilities</h2>
    <p>The public route is intended to support informed adoption rather than impulse collection. Adopters should expect species-appropriate setup, realistic ongoing costs, legal compliance where relevant and continued responsibility once the animal leaves APES care.</p>
  </article>
  <article class="policy-block">
    <h2>Review note</h2>
    <p>Because the source page exposes only part of the longer adoption wording in crawlable public output, this rebuilt page preserves the visible text and records the need for APES review before any deeper legal rewrite.</p>
  </article>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Re-homing policy', 'href' => '/policies/re-homing-policy/'],
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                ],
            ],
            're-homing-policy' => [
                'route' => '/policies/re-homing-policy/',
                'meta_title' => 'Re-homing Policy | APES CIC',
                'title' => 'Re-Homing Policy',
                'description' => 'Public re-homing policy guidance from APES CIC.',
                'hero_kicker' => 'Policy',
                'hero_title' => 'Matching animals to homes that protect long-term welfare.',
                'hero_summary' => 'See how APES approaches re-homing checks, adopter suitability and welfare protections before placing an animal.',
                'hero_actions' => [
                    ['label' => 'Adoption Policy', 'href' => '/policies/adoption-policy/', 'variant' => 'primary'],
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'secondary'],
                ],
                'pills' => ['Re-homing', 'Adopter checks', 'Welfare standards'],
                'body_html' => <<<'HTML'
<section class="policy-stack">
  <article class="policy-block">
    <h2>Overview</h2>
    <p>The public policy says these arrangements help APES ensure every animal finds the best possible home where it can live a happy, healthy life.</p>
  </article>
  <article class="policy-block">
    <h2>Key public points</h2>
    <ul class="clean-list">
      <li>Most animals in APES care have been rescued by animal control officers, and strays are transferred by the relevant local authority.</li>
      <li>Stray animals are kept for at least seven days to allow owners a fair chance to reclaim them.</li>
      <li>APES states that it will not import animals from other countries for re-homing in England and Wales.</li>
      <li>Before adoption, every animal undergoes a clinical examination by a qualified member of the animal care team.</li>
      <li>Animals can only be re-homed when proof is provided that the adopter is over 18 years old.</li>
      <li>The policy says APES takes reasonable steps to make sure animals are not re-homed without confidence that they can enjoy a good quality of life.</li>
      <li>Home visits by trained volunteers are described as part of checking that adopters understand the responsibilities of ownership.</li>
    </ul>
  </article>
  <article class="policy-block">
    <h2>What this means in practice</h2>
    <p>The rebuilt route keeps the priority points visible: a seven-day stray hold, no import-based rehoming for England and Wales, over-18 adopters, home checks where appropriate and welfare/legal safeguards before any placement proceeds.</p>
  </article>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Adoption Policy', 'href' => '/policies/adoption-policy/'],
                    ['label' => 'Support ethical rehabilitation', 'href' => '/mission/support-ethical-rehabilitation/'],
                ],
            ],
            'euthanasia-policy' => [
                'route' => '/policies/euthanasia-policy/',
                'meta_title' => 'Euthanasia Policy | APES CIC',
                'title' => 'Euthanasia Policy',
                'description' => 'Public euthanasia policy guidance from APES CIC.',
                'hero_kicker' => 'Policy',
                'hero_title' => 'Public euthanasia policy guidance.',
                'hero_summary' => 'Read the public APES guidance on when euthanasia may be considered as part of an individual welfare assessment.',
                'hero_actions' => [
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'primary'],
                    ['label' => 'Re-Homing Policy', 'href' => '/policies/re-homing-policy/', 'variant' => 'secondary'],
                ],
                'pills' => ['Welfare decisions', 'Public policy', 'Individual assessment'],
                'body_html' => <<<'HTML'
<section class="policy-stack">
  <article class="policy-block">
    <h2>When euthanasia may be considered</h2>
    <p>The live page explains that although APES tries to give animals the best possible chance to return to good health, there are times when euthanasia may be used after individual assessment.</p>
    <ul class="clean-list">
      <li>If the animal is in pain that cannot be controlled and would otherwise face a slow, painful death.</li>
      <li>If the animal has an inoperable health condition with serious quality-of-life implications.</li>
      <li>If the animal is injured and assessed as non-recoverable.</li>
    </ul>
  </article>
  <article class="policy-block">
    <h2>Decision principles</h2>
    <p>This public route should be read as welfare-threshold guidance rather than a promise of a fixed outcome. The emphasis remains on individual assessment, relief of suffering and species-appropriate decision making.</p>
  </article>
  <article class="policy-block">
    <h2>Veterinary oversight and records</h2>
    <p>Where euthanasia is considered, the brief supports clearer reference to professional oversight, documented reasoning and careful record keeping without inventing a procedural framework that APES has not yet published in full.</p>
  </article>
  <article class="policy-block">
    <h2>Presentation note</h2>
    <p>The legacy source shows a clearly broken heading. That presentation issue is corrected here while the public policy content remains grounded in the narrow verified criteria already published by APES.</p>
  </article>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Re-homing Policy', 'href' => '/policies/re-homing-policy/'],
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                ],
            ],
            'cookies' => [
                'route' => '/policies/cookies/',
                'meta_title' => 'Cookie guidance | APES CIC',
                'title' => 'Cookie guidance',
                'description' => 'Public cookie and browser guidance for APES CIC websites.',
                'hero_kicker' => 'Policy',
                'hero_title' => 'Cookie guidance for APES public websites.',
                'hero_summary' => 'Read the available public cookie guidance for APES websites, including browser controls and related privacy links.',
                'hero_actions' => [
                    ['label' => 'Privacy Policy', 'href' => '/policies/privacy/', 'variant' => 'primary'],
                    ['label' => 'Terms of Service', 'href' => '/policies/terms-of-service/', 'variant' => 'secondary'],
                ],
                'pills' => ['Cookies', 'Browser controls', 'Policy review'],
                'body_html' => <<<'HTML'
<section class="policy-stack">
  <article class="policy-block">
    <h2>Cookie use</h2>
    <p>The current public privacy wording says APES may use cookies and similar tracking technologies to support website operation and hold certain information.</p>
  </article>
  <article class="policy-block">
    <h2>Browser controls</h2>
    <p>Users can instruct their browser to refuse cookies, though parts of APES websites may not function properly as a result.</p>
  </article>
  <article class="policy-block">
    <h2>Review note</h2>
    <p>Because the original cookies page was not reliably retrievable in the public crawl, this rebuilt route intentionally stays narrow and should be reviewed against the authoritative APES policy text before publication.</p>
  </article>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Privacy Policy', 'href' => '/policies/privacy/'],
                    ['label' => 'Terms of Service', 'href' => '/policies/terms-of-service/'],
                ],
            ],
            'contact' => [
                'route' => '/contact/',
                'meta_title' => 'Contact APES | Find support and contact routes',
                'title' => 'Contact APES',
                'description' => 'Contact APES through the public contact centre, telephone, address and connected support routes.',
                'hero_kicker' => 'Contact and support',
                'hero_title' => 'Use the best APES route for help, support and enquiries.',
                'hero_summary' => 'Use the APES contact centre, help resources, phone number or postal details to reach the right support route quickly.',
                'hero_actions' => [
                    ['label' => 'Open contact centre', 'href' => 'https://contact.apes.org.uk/', 'external' => true, 'variant' => 'primary'],
                    ['label' => 'Help centre', 'href' => 'https://help.apes.org.uk/', 'external' => true, 'variant' => 'secondary'],
                ],
                'pills' => ['Contact centre', 'Phone and email', 'Support hub'],
                'body_html' => <<<'HTML'
<section class="section-shell split-panel">
  <div class="note-panel">
    <h2>Best contact route</h2>
    <p>Please use the live chat or contact centre where possible. The public site describes these as the best ways to contact APES.</p>
    <p><a class="text-link" href="https://contact.apes.org.uk/" target="_blank" rel="noreferrer">Contact APES via the contact centre</a></p>
  </div>
  <div class="note-panel">
    <h2>Direct details</h2>
    <ul class="clean-list">
      <li>Email: <a href="mailto:info@apes.org.uk" rel="nofollow">info@apes.org.uk</a></li>
      <li>Phone: 01744 374 015</li>
      <li>Address: 40 Morris Street, St Helens, Merseyside, WA9 3EN</li>
    </ul>
  </div>
</section>

<section class="section-shell">
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Working hours</h3>
      <p>Weekdays and weekends are publicly listed as 10:00 AM to 5:00 PM, matching the canonical opening-times route.</p>
    </article>
    <article class="info-card">
      <h3>Important links</h3>
      <p>Donate, volunteer and connected services remain visible from this page because it acts as a support hub in the live site.</p>
    </article>
    <article class="info-card">
      <h3>Connected platforms</h3>
      <p>MyAPES, members area, public resources and APES Social remain external journeys rather than local rebuild features.</p>
    </article>
  </div>
</section>

<section class="note-panel">
  <h2>Visit and access note</h2>
  <p>If you need to attend in person, check opening times and visit guidance first. Use the contact centre or direct contact route to confirm appointment-based attendance where needed rather than assuming immediate on-site availability.</p>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Contact centre', 'href' => 'https://contact.apes.org.uk/', 'external' => true],
                    ['label' => 'Help centre', 'href' => 'https://help.apes.org.uk/', 'external' => true],
                    ['label' => 'MyAPES', 'href' => 'https://www.myapes.me.uk/', 'external' => true],
                ],
            ],
            'contact-centre' => [
                'route' => '/contact-centre/',
                'meta_title' => 'Contact centre | External APES support route',
                'title' => 'Contact centre',
                'description' => 'Bridge page for the external APES contact centre and linked support routes.',
                'hero_kicker' => 'Support bridge',
                'hero_title' => 'Open the APES contact centre.',
                'hero_summary' => 'Open the external APES contact centre for tickets, live-chat support and guided access to the wider help system.',
                'hero_actions' => [
                    ['label' => 'Open contact centre', 'href' => 'https://contact.apes.org.uk/', 'external' => true, 'variant' => 'primary'],
                    ['label' => 'Open Help centre', 'href' => 'https://help.apes.org.uk/', 'external' => true, 'variant' => 'secondary'],
                ],
                'pills' => ['External support hub', 'Ticket routes', 'Knowledge base'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <p>Use the external APES contact centre for general enquiries, live-chat support and the best available routing into the wider APES help system.</p>
  <div class="card-grid card-grid-two">
    <article class="info-card">
      <h3>Open a ticket</h3>
      <p>The live contact-centre page routes visitors to ticket flows for APES CIC, Shelter &amp; Rescue, Pet Care Clinic, Pet Shop, legal, marketing, public relations, MyAPES support and reception.</p>
    </article>
    <article class="info-card">
      <h3>Call or self-serve</h3>
      <p>The live page also offers phone routes for different APES services and links to knowledgebase content for people who prefer self-serve support.</p>
    </article>
  </div>
</section>
<section class="section-shell">
  <div class="card-grid card-grid-three">
    <article class="info-card">
      <h3>Main APES CIC line</h3>
      <p>01744 374 015</p>
    </article>
    <article class="info-card">
      <h3>Shelter &amp; Rescue line</h3>
      <p>0300 302 0227</p>
    </article>
    <article class="info-card">
      <h3>Pet Care Clinic line</h3>
      <p>0300 302 0228</p>
    </article>
  </div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                    ['label' => 'Help centre', 'href' => 'https://help.apes.org.uk/', 'external' => true],
                ],
            ],
            'search' => [
                'route' => '/search/',
                'meta_title' => 'Search the APES CIC website | Services, policies and support routes',
                'title' => 'Search',
                'description' => 'Search the APES CIC website for services, policies, support routes and public guidance.',
                'hero_kicker' => 'Site search',
                'hero_title' => 'Find pages, routes and guidance quickly.',
                'hero_summary' => 'Search the APES website by service, policy, support route or key topic to find the public page you need more quickly.',
                'hero_actions' => [
                    ['label' => 'Start searching', 'href' => '#site-search', 'variant' => 'primary'],
                    ['label' => 'Contact APES', 'href' => '/contact/', 'variant' => 'secondary'],
                ],
                'pills' => ['Site search', 'Services and policies', 'Quick access'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <p>Use the public site search to reach services, policies, donation routes, volunteer information and other key APES guidance pages.</p>
  <form class="search-form" action="/search/" method="get">
    <label for="site-search" class="search-label">Search the APES CIC website</label>
    <div class="search-row">
      <input id="site-search" name="q" type="search" value="" placeholder="Try donate, policy, volunteer or boarding" />
      <button class="button button-primary" type="submit">Search</button>
    </div>
  </form>
  <div id="search-results-anchor"></div>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Contact APES', 'href' => '/contact/'],
                    ['label' => 'Change Log Hub', 'href' => '/change-log-hub/'],
                ],
            ],
            'news' => [
                'route' => '/news/',
                'meta_title' => 'News | APES Newsroom',
                'title' => 'News',
                'description' => 'APES Newsroom is the central public destination for APES updates, service notices, appeals and organisational news.',
                'hero_kicker' => 'APES Newsroom',
                'hero_title' => 'Public news now lives in APES Newsroom.',
                'hero_summary' => 'Use APES Newsroom for current public updates, service notices, appeals and organisation-wide announcements.',
                'hero_actions' => [
                    ['label' => 'Open APES Newsroom', 'href' => APES_NEWSROOM_URL, 'external' => true, 'variant' => 'primary'],
                    ['label' => 'Read Help Us Move', 'href' => '/help-us-move/', 'variant' => 'secondary'],
                ],
                'pills' => ['Public news', 'Current updates', 'APES Newsroom'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <p>The rebuilt APES website follows the APES Newsroom standard. Main public news navigation, newsletter prompts and update calls to action now direct people to APES Newsroom for current information.</p>
  <p>Legacy main-site news post and tag URLs are being redirected to APES Newsroom successor pages so the public site no longer duplicates article archives locally.</p>
</section>

<section class="section-shell">
  <div class="section-heading">
    <p class="eyebrow">What moved</p>
    <h2>Use APES Newsroom for current and archived update content.</h2>
  </div>
  <ul class="clean-list">
    <li>Organisation updates and campaign notices now route through APES Newsroom.</li>
    <li>Legacy article and tag URLs from the rebuilt main site now redirect to current APES Newsroom successor pages.</li>
    <li>The main website keeps support routes, policy pages, services and donation pathways separate from the newsroom archive.</li>
  </ul>
</section>
HTML,
                'related_links' => [
                    ['label' => 'APES Newsroom', 'href' => APES_NEWSROOM_URL, 'external' => true],
                    ['label' => 'Help Us Move', 'href' => '/help-us-move/'],
                ],
            ],
            'change-log-hub' => [
                'route' => '/change-log-hub/',
                'meta_title' => 'Change Log Hub | APES CIC website releases',
                'title' => 'Change Log Hub',
                'description' => 'Track every major release for this website, including updates, fixes, compliance changes, and user-facing improvements.',
                'hero_kicker' => 'Release records',
                'hero_title' => 'Change Log Hub',
                'hero_summary' => 'Track every major release for this website, including updates, fixes, compliance changes, and user-facing improvements.',
                'hero_actions' => [
                    ['label' => 'Expand all releases', 'href' => '#release-list', 'variant' => 'primary'],
                    ['label' => 'View current release', 'href' => '#release-v401b', 'variant' => 'secondary'],
                ],
                'pills' => ['Current version ' . $siteVersion, 'Patch beta', 'Public contact details'],
                'body_html' => <<<'HTML'
<section class="section-shell">
  <div class="release-tools">
    <label class="search-label" for="release-search">Search release notes</label>
    <input id="release-search" class="release-search" type="search" placeholder="Search version, changes, compliance or footer" data-release-search />
    <div class="filter-row">
      <button class="chip-button is-active" type="button" data-release-filter="all">All releases</button>
      <button class="chip-button" type="button" data-release-filter="current">Current release</button>
      <button class="chip-button" type="button" data-release-filter="beta">Beta</button>
      <button class="chip-button" type="button" data-release-filter="added">Added</button>
      <button class="chip-button" type="button" data-release-filter="changed">Changed</button>
      <button class="chip-button" type="button" data-release-filter="fixed">Fixed</button>
      <button class="chip-button" type="button" data-release-filter="removed">Removed</button>
      <button class="chip-button" type="button" data-release-filter="security">Security</button>
      <button class="chip-button" type="button" data-release-filter="compliance">Compliance</button>
      <button class="chip-button" type="button" data-release-filter="accessibility">Accessibility</button>
      <button class="chip-button" type="button" data-release-filter="public-facing">Public-facing</button>
      <button class="chip-button" type="button" data-release-filter="internal-only">Internal-only</button>
    </div>
    <div class="action-row">
      <button class="button button-secondary" type="button" data-expand-all>Expand all</button>
      <button class="button button-secondary" type="button" data-collapse-all>Collapse all</button>
    </div>
  </div>
</section>

<section class="section-shell" id="release-list">
  <details class="release-card" data-release-card data-tags="current beta changed fixed public-facing" open id="release-v401b">
    <summary>
      <span class="release-version">v4.0.1b</span>
      <span class="release-date">2026-08-19</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v4.0.1b</span>
        <span class="pill pill-status">Beta</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
      </div>
      <h3>Summary</h3>
      <p>Updated public contact details to the Morris Street address and 01744 374 015, and reduced crawler use of the public email by removing it from JSON-LD and marking mailto links as nofollow.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Replaced the public CIC telephone number <code>0300 302 0998</code> with <code>01744 374 015</code> in shared PHP contact helpers, contact copy, leftover public HTML snapshots, and README organisation details.</li>
        <li>Updated the public postal address and registered office to <code>40 Morris Street, St Helens, Merseyside, WA9 3EN</code>.</li>
        <li>Kept <code>info@apes.org.uk</code> visible and clickable for visitors, added <code>rel="nofollow"</code> to those mailto links, and removed the Organisation schema email field so crawlers are not given a structured harvest target.</li>
        <li>Left <code>legal@apes.org.uk</code>, Shelter and Clinic 0300 lines, and the veterinary emergency number unchanged.</li>
        <li>Synchronised version files, README, root changelog, public changelog mirror and Change Log Hub release metadata.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: all public routes via shared header, sidebar and footer; <code>/contact/</code>, <code>/contact-centre/</code>, <code>/policies/privacy/</code>, <code>/policies/terms-of-service/</code>, Change Log Hub and leftover public HTML snapshots</li>
        <li>Files changed: shared PHP contact constants and render helpers, contact and policy copy, public HTML snapshots, VERSION files, README, root CHANGELOG, public CHANGELOG and Change Log Hub source</li>
        <li>User groups affected: public visitors, supporters, staff and partners using published contact details</li>
        <li>Public impact: visitors now see the current Morris Street address and 01744 number; the public email remains usable but is not advertised in structured data</li>
        <li>Internal impact: contact details now come from shared PHP helpers, with leftover HTML snapshots aligned so Apache DirectoryIndex cannot serve the old 0300 number</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v4.0.0b</li>
        <li>New version: v4.0.1b</li>
        <li>Version type: patch beta</li>
        <li>Reason for version bump: user-visible contact-detail correction without route, architecture or hosting changes.</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: PHP lint on changed includes, local HTTP preview of home, contact, privacy, terms and footer, JSON-LD email absence check, mailto nofollow check, and public-site validation where PHP is available</li>
        <li>Manual checks completed: contact copy review, Shelter/Clinic 0300 retention review, footer-required link review and Cloudron LAMP compatibility review</li>
        <li>Known limitations: leftover HTML snapshots remain in <code>public/</code> because Apache DirectoryIndex prefers <code>index.html</code>; they were updated in place rather than deleted</li>
        <li>Rollback notes: restore the previous contact constants, schema email field, HTML snapshots and version records if the previous 0300 number or Cross House registered office must return.</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed public-facing" id="release-v297">
    <summary>
      <span class="release-version">v2.9.7</span>
      <span class="release-date">2026-06-10</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.9.7</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
      </div>
      <h3>Summary</h3>
      <p>Removed the opening <code>/volunteer/</code> reminder paragraph so the page now starts directly with the approved volunteer application guidance.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Removed the opening volunteer reminder paragraph from the shared site data so the route begins with the approved application panel instead of the unpaid-volunteer explainer.</li>
        <li>Extended the volunteer regression check so validation now fails if the removed introductory copy reappears in the exported volunteer snapshot.</li>
        <li>Kept the volunteer application route, SEO metadata, footer-required links, APES Newsroom routing, public-folder structure and Cloudron LAMP assumptions unchanged.</li>
        <li>Regenerated the public static snapshots and synchronised the version files, README, root changelog, public changelog mirror and Change Log Hub release metadata.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: <code>/volunteer/</code>, Change Log Hub, root and public release records, README current release, validation script and regenerated public HTML snapshots</li>
        <li>Files changed: volunteer page source content, validation script, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated public HTML snapshots</li>
        <li>User groups affected: prospective volunteers, student-placement applicants, supporters, staff and partners using the volunteering route</li>
        <li>Public impact: volunteer-page visitors now land directly on the application guidance without the extra introductory reminder paragraph</li>
        <li>Internal impact: the copy change remains isolated to the volunteer route while adding a lightweight guard against the removed paragraph returning</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.9.6</li>
        <li>New version: v2.9.7</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: user-visible volunteer copy removal without route, content-model or hosting changes.</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: volunteer regression check, local PHP preview review, PHP syntax checks where available, public-site validation script, static export regeneration and release-metadata consistency review</li>
        <li>Manual checks completed: volunteer page source review, footer-required link review, Change Log Hub release review and Cloudron LAMP compatibility review</li>
        <li>Known limitations: in-app browser screenshot validation was unavailable in this session, so final visual QA relied on local preview startup, source inspection and regenerated output review</li>
        <li>Rollback notes: restore the removed volunteer intro paragraph in shared site data, keep the regression check aligned with the intended copy, then rerun the PHP static export to restore the previous public snapshots if needed.</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed public-facing" id="release-v296">
  <details class="release-card" data-release-card data-tags="stable changed fixed public-facing" id="release-v295">
    <summary>
      <span class="release-version">v2.9.5</span>
      <span class="release-date">2026-06-10</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.9.5</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
      </div>
      <h3>Summary</h3>
      <p>Tightened the <code>/volunteer/</code> page layout so the desktop sidebar no longer leaves a large empty card, the main content has a better column balance and the volunteer panels use more controlled spacing.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Added volunteer-only desktop layout overrides so the main content column is wider and the sidebar column is slimmer without changing shared sitewide card spacing.</li>
        <li>Removed the volunteer sidebar wrapper's stretched card appearance so the smaller sidebar panels sit directly on the page instead of inside a tall empty container.</li>
        <li>Reduced padding and internal gaps for the volunteer application panel, the "Before you apply" panel and the lower split note cards to better match the screenshot feedback.</li>
        <li>Kept the volunteer route content, links, SEO metadata, APES Newsroom routing, footer-required links and Cloudron LAMP assumptions unchanged.</li>
        <li>Regenerated the public static snapshots and synchronised the version files, README, root changelog, public changelog mirror and Change Log Hub release metadata.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: <code>/volunteer/</code>, Change Log Hub, root and public release records, README current release and regenerated public HTML snapshots</li>
        <li>Files changed: volunteer-scoped shared CSS, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots</li>
        <li>User groups affected: prospective volunteers, student-placement applicants, supporters, staff and partners using the volunteering route</li>
        <li>Public impact: volunteer-page visitors now get a tighter desktop layout with less empty sidebar space and more balanced content panels</li>
        <li>Internal impact: the volunteer spacing correction stays isolated to that route instead of broadening the shared card system again</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.9.4</li>
        <li>New version: v2.9.5</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: user-visible volunteer-page layout correction without route, content-model or hosting changes.</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: local PHP preview review, PHP syntax checks where available, public-site validation script, static export regeneration and release-metadata consistency review</li>
        <li>Manual checks completed: volunteer page source/layout review, footer-required link review, Change Log Hub release review and Cloudron LAMP compatibility review</li>
        <li>Known limitations: in-app browser screenshot validation was unavailable in this session, so final visual QA relied on local preview startup, source inspection and regenerated output review</li>
        <li>Rollback notes: restore the volunteer page layout CSS overrides, version files and release records, then rerun the PHP static export to restore the prior public snapshots if needed.</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed public-facing" id="release-v294">
    <summary>
      <span class="release-version">v2.9.4</span>
      <span class="release-date">2026-06-10</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.9.4</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
      </div>
      <h3>Summary</h3>
      <p>Expanded shared card spacing across the website so content, sidebar, sponsor, metric and footer cards feel less cramped on desktop and stacked mobile layouts.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Increased shared grid gaps for reusable card layouts so stacked and multi-column card groups keep more consistent breathing room.</li>
        <li>Enlarged the base card padding and normalized internal card spacing for shared content-card patterns without changing public HTML structure or routes.</li>
        <li>Aligned refreshed sidebar, release and footer card spacing so heavier card surfaces no longer visually compress their content.</li>
        <li>Added responsive spacing tuning below the main grid-collapse breakpoints so single-column card stacks remain comfortably separated.</li>
        <li>Regenerated the public static snapshots so the exported site reflects the updated shared spacing and release metadata consistently.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: volunteer page card stacks, shared content cards, service cards, sidebar mini-panels, footer cards, Change Log Hub, root and public release records, README and generated public HTML snapshots</li>
        <li>Files changed: shared CSS, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, adopters, service users, volunteers, partners and general public visitors</li>
        <li>Public impact: shared cards now have clearer spacing and improved readability across desktop, tablet and mobile views</li>
        <li>Internal impact: the shared card system is more consistent, reducing the need for page-specific spacing overrides</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.9.3</li>
        <li>New version: v2.9.4</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: user-visible shared layout spacing fix without route, content-model or hosting changes.</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: shared CSS diff review, PHP syntax checks where available, public-site validation script, static export regeneration and release-metadata consistency review</li>
        <li>Manual checks completed: volunteer page card spacing review, shared service/sidebar/footer card source review, footer-required link review, Change Log Hub release review and Cloudron LAMP compatibility review</li>
        <li>Known limitations: final QA in this session relies on local preview and regenerated output review rather than exhaustive route-by-route browser screenshots</li>
        <li>Rollback notes: restore the previous shared CSS, version files and release records, then rerun the PHP static export to restore the prior public snapshots if needed.</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed compliance internal-only" id="release-v292">
    <summary>
      <span class="release-version">v2.9.2</span>
      <span class="release-date">2026-06-10</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.9.2</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
        <span class="pill pill-compliance">Compliance</span>
      </div>
      <h3>Summary</h3>
      <p>Applied the APES rendering standard across the whole website by adding a shared PHP preview router, reusable deployment helpers and aligned Cloudron validation workflows.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Added repo-root PHP preview routing through <code>dev/router.php</code> plus the task <code>APES: Preview PHP source site</code> so the shared PHP source can be tested over HTTP instead of relying on <code>file://</code> or static-only previews.</li>
        <li>Added reusable PowerShell helpers for PHP preview, static export, full-site validation, Cloudron staging and ZIP packaging while auto-detecting common Windows PHP installs such as XAMPP.</li>
        <li>Added GitHub Actions workflows to validate the shared PHP/public output and build a Cloudron public-bundle artifact so the documented deployment path now exists in the repository.</li>
        <li>Preserved <code>public/</code> as the approved APES web-root equivalent while keeping Apache redirects, sitemap, robots, branded error pages, footer links, README notes and release records aligned for Cloudron LAMP.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: whole-site preview workflow, shared PHP rendering, legacy redirect parity, deployment helpers, Change Log Hub, root and public release records, README, Cloudron docs, validation report and regenerated public HTML snapshots</li>
        <li>Files changed: <code>dev/router.php</code>, repo-root PowerShell helpers, GitHub workflow scaffolds, VS Code tasks, shared PHP runtime, Apache headers, VERSION files, root and public changelogs, README, deployment docs, validation report, Change Log Hub source and regenerated static HTML snapshots</li>
        <li>User groups affected: APES maintainers previewing, validating, packaging and publishing the website to Cloudron LAMP</li>
        <li>Public impact: no new public routes or information-architecture changes are intended; rendered output and release metadata stay aligned while maintainers get stronger preview and deployment parity.</li>
        <li>Internal impact: APES now has a consistent HTTP preview, validation, staging and packaging workflow grounded in the approved rendering standard.</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.9.1</li>
        <li>New version: v2.9.2</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: whole-site rendering, deployment and documentation alignment without public route expansion or architecture replacement.</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: PHP lint checks for the shared runtime and router, <code>php public/scripts/export-static-site.php</code>, <code>powershell -ExecutionPolicy Bypass -File scripts/validate-public-site.ps1</code>, Cloudron staging/package helper review and release-metadata consistency review</li>
        <li>Manual checks completed: representative route preview review, root-relative asset 200 review, canonical and legacy-news redirect review, branded 403/404 handling review, footer-required link review, Change Log Hub release review, sitemap/robots review and Cloudron LAMP documentation alignment review</li>
        <li>Known limitations: final browser QA on the deployed Cloudron staging app and live Apache verification still require a post-upload environment outside this repo-only implementation pass.</li>
        <li>Rollback notes: restore the previous preview tasks and shared runtime files, remove the new repo-root scripts and workflows, restore the previous version files and release records, then rerun the shared PHP static export before redeployment.</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed internal-only" id="release-v291">
    <summary>
      <span class="release-version">v2.9.1</span>
      <span class="release-date">2026-06-10</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.9.1</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
      </div>
      <h3>Summary</h3>
      <p>Restored a reliable VS Code browser preview workflow by adding a PHP-free static preview task and Live Server public-root settings.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Added a local PowerShell static preview helper that serves <code>public/</code> as the web root, resolves clean directory routes to <code>index.html</code>, and returns the branded static error pages when available.</li>
        <li>Added the VS Code task <code>APES: Preview public site</code> so maintainers can open <code>http://localhost:8080/</code> in VS Code Simple Browser without needing PHP installed.</li>
        <li>Set the VS Code Live Server root to <code>/public</code> so root-relative assets such as <code>/theme/site.css</code> and <code>/theme/js/site.js</code> resolve correctly during local preview.</li>
        <li>Kept the existing PHP export task unchanged for production static snapshot generation in PHP-enabled environments.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: local VS Code preview workflow, Change Log Hub, root and public release records, README and footer version display</li>
        <li>Files changed: VS Code settings and tasks, local preview helper, VERSION files, root CHANGELOG, public CHANGELOG, README, Change Log Hub source and generated static HTML snapshots</li>
        <li>User groups affected: APES maintainers previewing the website locally in VS Code</li>
        <li>Public impact: no public route, content, metadata, navigation or journey change is intended.</li>
        <li>Internal impact: maintainers can preview the exported website from the correct public web root even when PHP is not installed locally.</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.9.0</li>
        <li>New version: v2.9.1</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: local preview tooling and documentation fix without route, content, database or production hosting changes.</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: VS Code task review, PowerShell static preview helper review, local HTTP preview smoke test, release-metadata consistency review and Cloudron LAMP compatibility review</li>
        <li>Manual checks completed: served-root asset path review, APES footer required-link review, Newsroom routing verification-only review, sitemap and robots verification-only review, branded 403/404/500 static-page review and VS Code preview workflow documentation review</li>
        <li>Known limitations: <code>php</code> is not installed in this implementation environment, so PHP syntax checks and the standard PHP static exporter still need to run in a PHP-enabled environment before deployment.</li>
        <li>Rollback notes: restore the previous VS Code settings and tasks, remove the local static preview helper, restore the previous version files and release records, then rerun the standard PHP export in a PHP-enabled environment if needed.</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable added changed accessibility public-facing" id="release-v290">
    <summary>
      <span class="release-version">v2.9.0</span>
      <span class="release-date">2026-06-10</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.9.0</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Added</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-accessibility">Accessibility</span>
      </div>
      <h3>Summary</h3>
      <p>Refactored the public website theme into modular CSS and browser-module JavaScript while adding a manual VS Code static export task for served-root preview workflows.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Created the shared <code>/theme/</code> asset structure with ordered stylesheet imports for utilities, layouts, components, responsive rules and animation states.</li>
        <li>Moved shared browser behaviour into <code>/theme/js/</code> ES module entrypoints covering navigation, popup links, the development notice, release filters and the route finder.</li>
        <li>Updated the PHP renderer and footer to load <code>/theme/site.css</code> and <code>/theme/js/site.js</code> while leaving production PHP routing unchanged for Cloudron LAMP.</li>
        <li>Kept compatibility shims at the previous <code>/assets/css/site.css</code> and <code>/assets/js/site.js</code> paths for stale snapshots during the transition release.</li>
        <li>Added a manual VS Code task to run <code>php public/scripts/export-static-site.php</code> and documented the edit, export and served-root preview workflow.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: shared theme assets, shared PHP rendering, generated static snapshots, Change Log Hub, root and public release records, README and footer version display</li>
        <li>Files changed: shared CSS and JS asset structure, shared renderer and footer asset references, VS Code task configuration, VERSION files, root CHANGELOG, public CHANGELOG, README, Change Log Hub source and generated static HTML snapshots</li>
        <li>User groups affected: maintainers previewing the APES website locally, plus public visitors receiving the same shared styling and interactions after deployment</li>
        <li>Public impact: no route, content or journey change is intended; the public site should keep the same visual treatment and interaction behaviour through the new theme entrypoints.</li>
        <li>Internal impact: APES maintainers now have a clearer modular theme structure and a documented manual static export task for VS Code preview work.</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.8.9</li>
        <li>New version: v2.9.0</li>
        <li>Version type: minor stable</li>
        <li>Reason for version bump: development workflow and shared asset-structure addition across the website without route, content, database or production hosting changes.</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: <code>git pull</code>, modular CSS reconstruction review, asset-reference search, generated snapshot asset-path synchronisation, release-metadata consistency review, footer-link source review and Cloudron LAMP compatibility review</li>
        <li>Manual checks completed: PHP renderer and footer asset-reference review, VS Code task review, APES Newsroom routing verification-only review, sitemap and robots verification-only review, branded 403/404/500 source-page review and static preview workflow documentation review</li>
        <li>Known limitations: <code>php</code> is not installed in this implementation environment, so PHP syntax checks, the standard static exporter and full served-browser QA still need to run in a PHP-enabled environment before deployment.</li>
        <li>Rollback notes: restore the previous shared asset references, remove the <code>/theme/</code> entrypoints and VS Code task, restore the previous version files and release records, then rerun the standard PHP export in a PHP-enabled environment.</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed accessibility public-facing" id="release-v289">
    <summary>
      <span class="release-version">v2.8.9</span>
      <span class="release-date">2026-06-10</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.8.9</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
        <span class="pill pill-accessibility">Accessibility</span>
      </div>
      <h3>Summary</h3>
      <p>Expanded the volunteering page into the main APES volunteer information hub, added practical rescue role categories and kept the approved Sheltermanager application form as the primary application route.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Expanded the shared <code>/volunteer/</code> page definition with a prominent in-page application call to action that opens the approved Sheltermanager volunteer form.</li>
        <li>Added practical rescue volunteer role examples covering animal care, cleaning, feeding, enrichment, housekeeping, transport, fostering, adoption support, public enquiries, fundraising, education, retail, maintenance, administration, photography, student placements and specialist support.</li>
        <li>Confirmed public volunteering recruitment and support links continue to point to <code>/volunteer/</code>, while the operational Volunteer intranet link remains unchanged.</li>
        <li>Kept the volunteering page route, canonical URL, sitemap entry, robots rules, footer-required links, APES Newsroom routing and Cloudron LAMP hosting assumptions unchanged.</li>
        <li>Synchronised the canonical version files, README current-release notes, root changelog, public changelog mirror, Change Log Hub source and generated public snapshots to <code>v2.8.9</code>.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: <code>/volunteer/</code>, Change Log Hub, root and public release records, README current release and generated public HTML snapshots</li>
        <li>Files changed: shared site data, generated volunteer page, generated Change Log Hub, generated HTML footer version text, VERSION files, root CHANGELOG, public CHANGELOG and README</li>
        <li>User groups affected: prospective volunteers, student-placement applicants, supporters, staff and partners using the volunteering route</li>
        <li>Public impact: visitors can review common APES rescue volunteer role types and apply through the approved external form from the volunteer page.</li>
        <li>Internal impact: APES has one clearer volunteer information hub while preserving the existing external Sheltermanager application workflow.</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.8.8</li>
        <li>New version: v2.8.9</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: public-facing volunteering content expansion and generated-output synchronisation without route, SEO or hosting restructuring.</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: <code>git pull</code>, volunteer-link audit, release-metadata consistency review, sitemap and robots verification-only review, footer-link source review, branded error-page footer review and Cloudron LAMP compatibility review</li>
        <li>Manual checks completed: volunteering hero and in-page application CTA review, public volunteering link review, canonical/title/meta/robots review for <code>/volunteer/</code>, sitemap entry review, APES Newsroom routing review and generated footer version review</li>
        <li>Known limitations: <code>php</code> is not installed in this implementation environment, so the standard static export and full rendered browser QA could not be completed here; generated snapshots were synchronised manually from the shared source content.</li>
        <li>Rollback notes: restore the previous volunteering body content, generated snapshots, version files and release records, then rerun the standard PHP export in a PHP-enabled environment if the expanded volunteering hub needs to be reversed.</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed accessibility public-facing" id="release-v288">
    <summary>
      <span class="release-version">v2.8.8</span>
      <span class="release-date">2026-06-10</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.8.8</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
        <span class="pill pill-accessibility">Accessibility</span>
      </div>
      <h3>Summary</h3>
      <p>Replaced the volunteering-page primary hero call to action with the approved Sheltermanager volunteer application form so applicants can go straight to the correct external route.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Updated the shared <code>/volunteer/</code> page definition so the primary hero button now opens the approved Sheltermanager volunteering form instead of sending visitors to the general contact page.</li>
        <li>Kept the volunteering page route, title, meta description, canonical URL, help-centre secondary action, sitemap entry, robots rules, footer-required links and APES Newsroom routing unchanged.</li>
        <li>Synchronised the canonical version files, README current-release notes, root changelog, public changelog mirror and Change Log Hub source to <code>v2.8.8</code>.</li>
        <li>Confirmed Cloudron LAMP compatibility remains unchanged because this is a shared PHP content update with no new runtime dependency or hosting requirement.</li>
        <li>The standard PHP static export could not be rerun in this environment because <code>php</code> is unavailable, so generated HTML snapshots and rendered footer version text still need resynchronising in a PHP-enabled environment before deployment.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: <code>/volunteer/</code>, Change Log Hub, root and public release records, README current release and generated public HTML snapshots once re-exported</li>
        <li>Files changed: shared site data, VERSION files, root CHANGELOG, public CHANGELOG and README</li>
        <li>User groups affected: prospective volunteers, student-placement applicants, supporters, staff and partners using the volunteering route</li>
        <li>Public impact: visitors can now use the main volunteering hero button to open the approved external application form directly</li>
        <li>Internal impact: the volunteering CTA now points to the approved Sheltermanager workflow while release records move forward to the next stable patch version</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.8.7</li>
        <li>New version: v2.8.8</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: small public-facing volunteering CTA change without any route, SEO or metadata restructuring</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: <code>git pull</code>, volunteer page source review, release-metadata consistency review, sitemap and robots verification-only review, footer-link source review and Cloudron LAMP compatibility review</li>
        <li>Manual checks completed: volunteering hero-action source audit, canonical/title/meta/robots review for <code>/volunteer/</code>, sitemap entry review, footer required-link review, APES Newsroom routing review and Change Log Hub source review</li>
        <li>Known limitations: <code>php</code> is not installed in this implementation environment, so the standard static export, full rendered browser QA and generated HTML snapshot resynchronisation could not be completed here</li>
        <li>Rollback notes: restore the previous volunteering hero action, version files and release records, then rerun the standard PHP export in a PHP-enabled environment if the direct application CTA needs to be reversed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed accessibility public-facing" id="release-v287">
    <summary>
      <span class="release-version">v2.8.7</span>
      <span class="release-date">2026-06-09</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.8.7</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
        <span class="pill pill-accessibility">Accessibility</span>
      </div>
      <h3>Summary</h3>
      <p>Changed the shared sticky header so only the main navigation remains floating during scroll, while the top contact bar and development notice collapse until the page returns to the top.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Added shared header scroll-state logic so the top contact bar and development notice collapse as soon as the page leaves the top, while the main navigation continues to stick in place.</li>
        <li>Updated the shared header CSS transitions, spacing and border handling so the compact state hides the two upper bars cleanly without leaving a persistent layout gap.</li>
        <li>Kept the shared header markup, desktop mega-menu structure, mobile overlay navigation pattern, footer routes and APES Newsroom routing unchanged while reusing the existing header-height offset logic.</li>
        <li>Synchronised the shared release metadata, README, changelog mirrors and version files to <code>v2.8.7</code>, and prepared the PHP source of truth for static snapshot regeneration once PHP is available again in a compatible environment.</li>
        <li>Preserved canonical URLs, sitemap entries, robots rules, footer-required links and branded error-page source files without route, label or metadata changes.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: shared header behaviour, shared CSS, shared JS, Change Log Hub, root and public release records, README and branded error pages once regenerated from source</li>
        <li>Files changed: shared CSS, shared JS, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG and README</li>
        <li>User groups affected: supporters, donors, volunteers, staff, partners and general public visitors using the shared sticky header across desktop, tablet and mobile layouts</li>
        <li>Public impact: visitors now see the contact bar and development notice disappear after scrolling away from the top while the main navigation remains available as the sticky header</li>
        <li>Internal impact: shared header behaviour now uses a single compact scroll state, keeping mega-menu positioning and mobile-nav offsets aligned to the visible sticky navigation height</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.8.6</li>
        <li>New version: v2.8.7</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: shared public-facing header scroll behaviour change without route, SEO or metadata restructuring</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: shared header JS and CSS review, release-metadata consistency review, sitemap and robots verification-only review, footer-link source review and header offset logic review</li>
        <li>Manual checks completed: shared sticky-header source audit for compact-state transitions, desktop mega-menu anchor review, mobile overlay offset review, footer required-link review, APES Newsroom routing review and branded 403/404/500 source-page review</li>
        <li>Known limitations: the local PHP runtime is still unavailable in this implementation environment, so the standard static export, full rendered browser QA and generated HTML snapshot resynchronisation still need to run in a PHP-enabled environment before deployment</li>
        <li>Rollback notes: restore the previous shared header CSS and JS, version files and release records, then rerun the standard PHP export when PHP is available if the sticky-header compact behaviour needs to be reversed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed removed fixed accessibility public-facing" id="release-v283">
    <summary>
      <span class="release-version">v2.8.3</span>
      <span class="release-date">2026-06-04</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.8.3</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-type">Removed</span>
        <span class="pill pill-fix">Fix</span>
        <span class="pill pill-accessibility">Accessibility</span>
      </div>
      <h3>Summary</h3>
      <p>Removed the site-wide Hellobar embed from the shared APES page shell so APES-owned popups can run without the third-party Hello Bar script loading across the public website and branded error pages.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Removed the shared Hello Bar script include from the PHP document renderer while leaving the APES development popup, booking popup-window behaviour and Donorbox popup flows in place.</li>
        <li>Synchronised the shared release metadata, README, version files, Change Log Hub source and generated website snapshots to <code>v2.8.3</code> so the release record now reflects the Hellobar removal.</li>
        <li>Manually synchronised the exported HTML snapshots in <code>/public/</code> because no local PHP runtime was available for the standard static export script in this shell.</li>
        <li>Preserved APES Newsroom routing, footer-required links, canonical URLs, sitemap entries, robots rules and branded error pages without route, label or metadata changes.</li>
        <li>Checked for a related GitHub issue and found no explicit linked issue in the current repo context, so no issue update was posted during this implementation pass.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: shared PHP rendering, all generated public and error-page HTML snapshots, Change Log Hub, root and public release records, README and footer version display</li>
        <li>Files changed: shared PHP renderer, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README, Change Log Hub snapshot and regenerated or manually synchronised static HTML snapshots</li>
        <li>User groups affected: supporters, donors, volunteers, staff, partners and general public visitors encountering site-wide overlays, donation prompts or branded fallback pages</li>
        <li>Public impact: visitors no longer load the third-party Hello Bar script site-wide, reducing interference risk with APES-managed popup behaviour while leaving APES and Donorbox popup journeys available</li>
        <li>Internal impact: popup ownership is now cleaner in the shared shell, making future APES-managed popup work easier to control without a parallel Hello Bar dependency</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.8.2</li>
        <li>New version: v2.8.3</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: shared public-facing third-party popup-script removal without any route, SEO or metadata restructuring</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: shared renderer review, generated HTML sync review, Hellobar reference search, popup wiring review and release-metadata consistency review</li>
        <li>Manual checks completed: Hellobar removal review on representative public and branded error-page output, development-popup source review, booking-popup script review, Donorbox popup embed review, footer required-link review, APES Newsroom routing review, sitemap/canonical/robots verification-only review and branded 403/404/500 source-page review</li>
        <li>Known limitations: the local PHP runtime was unavailable in this implementation pass, so the standard PHP static export script and full rendered browser QA still need to run in a PHP-enabled environment before deployment</li>
        <li>Rollback notes: restore the previous shared renderer, version files and release records, then rerun the standard PHP export to reintroduce the prior shell output if the Hellobar removal needs to be reversed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed accessibility public-facing" id="release-v282">
    <summary>
      <span class="release-version">v2.8.2</span>
      <span class="release-date">2026-06-04</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.8.2</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
        <span class="pill pill-accessibility">Accessibility</span>
      </div>
      <h3>Summary</h3>
      <p>Restored the shared desktop navigation to its intended horizontal header layout after the mobile overlay panel wrapper caused the desktop menu list to fall back to a vertical stack.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Retargeted the shared desktop navigation flex and spacing selectors to <code>.primary-nav &gt; .primary-nav__panel &gt; ul</code> so the real wrapped list once again renders as a horizontal row above <code>981px</code>.</li>
        <li>Left the current mobile menu architecture, overlay flow, close button, accordion submenu behaviour and shared navigation JavaScript unchanged because the regression was isolated to desktop CSS selector scope.</li>
        <li>Synchronised the shared stylesheet, Change Log Hub release record, mirrored changelogs, README and canonical version files to <code>v2.8.2</code>, and prepared the PHP source of truth for static snapshot regeneration in a PHP-enabled environment.</li>
        <li>Preserved APES Newsroom routing, footer-required links, canonical URLs, sitemap entries, robots rules and branded error pages without route, label or metadata changes.</li>
        <li>Checked for a related GitHub issue and found no explicit linked issue in the current repo context, so no issue update was posted during this implementation pass.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: shared header navigation, shared CSS, Change Log Hub, root and public release records, README, branded error pages and regenerated static HTML snapshots</li>
        <li>Files changed: shared CSS, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, donors, volunteers, staff, partners and general public visitors using desktop, laptop, tablet or mobile navigation</li>
        <li>Public impact: desktop visitors now see the main navigation links aligned horizontally in the header again while the mobile menu continues to behave as the recent overlay release intended</li>
        <li>Internal impact: the shared desktop selector chain now matches the wrapped navigation markup introduced by the mobile menu work, reducing the risk of future desktop/mobile navigation drift</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.8.1</li>
        <li>New version: v2.8.2</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: shared public-facing navigation layout regression fix without any route, SEO or metadata restructuring</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: shared CSS review, release-metadata consistency review and source/output diff inspection</li>
        <li>Manual checks completed: desktop selector review for 1366px and 1920px layouts, mobile overlay selector-regression review for 768px, 430px and 390px breakpoints, footer required-link review, APES Newsroom routing review, sitemap/canonical/robots verification-only review, and branded 403/404/500 source-page review</li>
        <li>Known limitations: the local PHP runtime was unavailable in this implementation pass, so PHP syntax checks, static HTML export/regeneration and full rendered browser QA still need to run in a PHP-enabled environment before deployment</li>
        <li>Rollback notes: restore the previous shared CSS, version files and release records, then re-export the static HTML snapshots once PHP is available if the desktop navigation fix needs to be reversed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed accessibility public-facing" id="release-v281">
    <summary>
      <span class="release-version">v2.8.1</span>
      <span class="release-date">2026-06-04</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.8.1</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
        <span class="pill pill-accessibility">Accessibility</span>
      </div>
      <h3>Summary</h3>
      <p>Reworked the shared APES mobile navigation into a dedicated overlay panel so direct links and accordion groups now respond reliably on touch devices without sticky donate or chat controls blocking taps.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Rebuilt the shared mobile navigation shell around the existing header markup by adding a dedicated mobile panel, close control, backdrop hook and clearer open-state accessibility labels while preserving all existing desktop link targets and mega-menu content.</li>
        <li>Reworked the shared mobile navigation CSS below <code>980px</code> so the menu opens as a fixed overlay panel with independent scrolling, full-width touch targets, accordion-friendly <code>&lt;details&gt;</code> groups and a body scroll-lock state.</li>
        <li>Refined the shared navigation script so mobile open-state, desktop mega-menu positioning and close behaviour are handled separately, allowing direct links and submenu links to navigate on first tap while still supporting Escape, overlay close and page-transition resets.</li>
        <li>Kept APES Newsroom routing, footer-required links, canonical URLs, sitemap entries and branded error pages unchanged, then regenerated the static HTML snapshots and synchronised the canonical version plus release records to <code>v2.8.1</code>.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: shared header navigation, shared CSS, shared JS, Change Log Hub, root and public release records, README, branded error pages and regenerated static HTML snapshots</li>
        <li>Files changed: shared PHP header, shared CSS, shared JS, shared site data release records, VERSION files, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, donors, volunteers, staff, partners and general public visitors using mobile or tablet navigation</li>
        <li>Public impact: mobile visitors can now open the menu, expand grouped sections, follow direct links and submenu links on first tap, and use the overlay without donate or chat widgets stealing interaction</li>
        <li>Internal impact: the shared navigation state model is now cleaner across mobile and desktop breakpoints, reducing the chance of future tap-target and overlay regressions</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.8.0</li>
        <li>New version: v2.8.1</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: shared public-facing navigation reliability, accessibility and overlay-behaviour fixes without any route or SEO structure change</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: local PHP syntax checks, shared CSS and JS review, static HTML export and generated HTML inspection</li>
        <li>Manual checks completed: mobile navigation markup/state review, footer required-link review, APES Newsroom routing review, sitemap and canonical verification-only review, and branded 403/404/500 page review after regeneration</li>
        <li>Known limitations: live device/browser interaction with third-party Donorbox and Chatwoot widgets still requires a post-deploy touch test outside this repo-only implementation pass</li>
        <li>Rollback notes: restore the previous shared header, CSS, JS, version files and release records, then re-export the static HTML snapshots if the mobile navigation rollout needs to be reversed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable added changed fixed compliance accessibility public-facing" id="release-v280">
    <summary>
      <span class="release-version">v2.8.0</span>
      <span class="release-date">2026-06-04</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.8.0</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Added</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
        <span class="pill pill-compliance">Compliance</span>
        <span class="pill pill-accessibility">Accessibility</span>
      </div>
      <h3>Summary</h3>
      <p>Completed the APES launch SEO and production-cutover pass by tightening shared metadata and JSON-LD, redirecting legacy main-site news URLs into APES Newsroom, and hardening robots, sitemap and error-page handling for Cloudron LAMP.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Added shared robots-meta support plus Organization, WebSite and breadcrumb JSON-LD through the PHP renderer while keeping <code>https://www.apes.org.uk</code> as the only canonical host in shared metadata output.</li>
        <li>Reworked the <code>/news/</code> page into a pure APES Newsroom handoff, removed local news-post and tag pages from the shared page model, and mapped each legacy <code>/news/post/...</code> and <code>/news/tag/...</code> route to an exact one-hop APES Newsroom successor URL in Apache.</li>
        <li>Disabled the production development notice, blocked public access to technical <code>/includes/</code>, <code>/outputs/</code> and <code>/scripts/</code> paths, and added branded <code>403.html</code> and <code>500.html</code> companions alongside the updated <code>404.html</code> experience.</li>
        <li>Regenerated the static HTML snapshots, refreshed <code>robots.txt</code> and <code>sitemap.xml</code>, synchronised the canonical version to <code>v2.8.0</code>, and updated the APES release, inventory, content-audit and redirect records for launch.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: shared PHP rendering, <code>/news/</code>, <code>/search/</code>, error pages, Apache routing, <code>robots.txt</code>, <code>sitemap.xml</code>, Change Log Hub, root and public release records, and regenerated static HTML snapshots</li>
        <li>Files changed: shared PHP rendering, shared site data, <code>.htaccess</code>, <code>robots.txt</code>, <code>sitemap.xml</code>, VERSION files, README, changelog records, documentation records and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, donors, volunteers, staff, partners and general public visitors</li>
        <li>Public impact: visitors now get cleaner canonical metadata, proper APES Newsroom routing for legacy news URLs, production-ready search indexing, and branded error handling with clearer recovery routes</li>
        <li>Internal impact: launch SEO rules, redirect mappings, sitemap truth and error-page handling now live in the shared source of truth and Cloudron-facing Apache configuration</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.7.0</li>
        <li>New version: v2.8.0</li>
        <li>Version type: minor stable</li>
        <li>Reason for version bump: site-wide SEO, routing, error-handling and production-launch behaviour changes without a breaking public-domain move</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: local PHP syntax checks, static HTML export, sitemap regeneration, generated HTML inspection and redirect/error-route review</li>
        <li>Manual checks completed: canonical metadata review, APES Newsroom redirect review, footer required-link review, search indexability review, robots/sitemap review, error-page review and changelog/version synchronisation review</li>
        <li>Known limitations: live Cloudron staging verification, Apache status-code confirmation in the deployed app and Google Search Console submission still require post-deploy checks outside this repo-only implementation pass</li>
        <li>Rollback notes: restore the previous shared PHP, site data, Apache config, robots/sitemap files, version files and release records, then re-export the static HTML snapshots if the launch SEO cutover needs to be reversed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable added changed fixed compliance public-facing" id="release-v270">
    <summary>
      <span class="release-version">v2.7.0</span>
      <span class="release-date">2026-06-04</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.7.0</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Added</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
        <span class="pill pill-compliance">Compliance</span>
      </div>
      <h3>Summary</h3>
      <p>Added a site-wide development notice popup and persistent header message, then rebalanced the shared APES footer so every footer-card link now renders as a clearer tile-style action.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Added a persistent development notice band near the top of the shared header and a first-open popup that explains some links and features are still in development while directing visitors to live chat for fast help.</li>
        <li>Wired the notice actions into the existing Chatwoot widget with a safe contact-page fallback, session-based dismissal, keyboard-accessible dialog behaviour and focus return on close.</li>
        <li>Redistributed the four APES footer cards into more balanced groups and made every footer-card link render as a visible tile without removing the required donation, Privacy Policy, Terms of Service, Change Log Hub or intranet routes.</li>
        <li>Preserved APES Newsroom routing, APES CIC identity and footer compliance rules, then synchronised the canonical version plus release records to v2.7.0 before regenerating the static HTML snapshots.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: shared header, shared footer, shared site data, shared CSS, shared JS, Change Log Hub, footer version display, root and public release records, and regenerated static HTML snapshots</li>
        <li>Files changed: shared PHP rendering, shared site data, shared CSS, shared JS, VERSION, public VERSION, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, donors, volunteers, staff, partners and general public visitors</li>
        <li>Public impact: visitors now see a clear development notice, get a direct route into live chat, and can use a more balanced, button-led footer across the public site</li>
        <li>Internal impact: the shared shell now holds the development-notice copy and behaviour in one place, and the footer grouping is easier to maintain without route-level edits</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.6.4</li>
        <li>New version: v2.7.0</li>
        <li>Version type: minor stable</li>
        <li>Reason for version bump: site-wide public messaging and shared-shell footer behaviour additions without a breaking route restructure</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: local PHP syntax checks, shared CSS and JS review, static HTML export and generated HTML inspection</li>
        <li>Manual checks completed: header notice review, popup/session behaviour review, footer required link and intranet attribute review, footer version alignment review, APES Newsroom route review and changelog/version synchronisation review</li>
        <li>Known limitations: deployed FTP validation and live browser confirmation still require a post-push check outside this repo-only implementation pass</li>
        <li>Rollback notes: restore the previous shared PHP, site data, CSS, JS, version files and release records, then re-export the static HTML snapshots if the notice or footer rollout needs to be reversed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed public-facing" id="release-v264">
    <summary>
      <span class="release-version">v2.6.4</span>
      <span class="release-date">2026-06-03</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.6.4</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
      </div>
      <h3>Summary</h3>
      <p>Centred the shared APES sidebar logo card more explicitly through the shared stylesheet so the feature logo sits consistently within its support panel across the public website.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Updated the shared <code>.brand-feature-panel</code> styling so the sidebar card, <code>&lt;picture&gt;</code> wrapper and logo image all centre explicitly without changing the shared sidebar markup.</li>
        <li>Kept the existing responsive logo sizing and card spacing while preventing route-level drift for the logo card across all pages that inherit the shared sidebar.</li>
        <li>Preserved the APES column-card footer, required donation, Privacy Policy, Terms of Service and Change Log Hub links, left APES Newsroom routing unchanged, and synchronised the canonical version plus release records to v2.6.4.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: shared sidebar logo card, shared CSS, Change Log Hub, footer version display, root and public release records, and regenerated static HTML snapshots</li>
        <li>Files changed: shared CSS, shared release data, VERSION, public VERSION, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, donors, volunteers, staff, partners and general public visitors</li>
        <li>Public impact: visitors now see the APES feature logo centred more cleanly within the shared sidebar card across the public site</li>
        <li>Internal impact: the shared sidebar logo card now has clearer centring rules in the main stylesheet, reducing the chance of route-specific visual drift</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.6.3</li>
        <li>New version: v2.6.4</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: low-risk public-facing layout fix to the shared sidebar logo presentation without a breaking restructure or route change</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: shared CSS review, shared PHP data review, static HTML export and generated HTML inspection</li>
        <li>Manual checks completed: homepage, inner-page, Change Log Hub and 404 sidebar logo review; footer required link review; APES Newsroom route review; and changelog/version alignment review</li>
        <li>Known limitations: deployed FTP validation and live browser confirmation still require a post-push check outside this repo-only implementation pass</li>
        <li>Rollback notes: restore the previous shared CSS, version files and release records, then re-export the static HTML snapshots if the logo-card alignment change needs to be reversed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable added changed public-facing" id="release-v263">
    <summary>
      <span class="release-version">v2.6.3</span>
      <span class="release-date">2026-06-03</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.6.3</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Added</span>
        <span class="pill pill-type">Changed</span>
      </div>
      <h3>Summary</h3>
      <p>Added the requested site-wide messaging, donation and support embeds through the shared shell, while keeping the APES footer layout, required footer links and release records aligned to a new stable patch version.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Added OneSignal, Hello Bar and Mastodon <code>rel="me"</code> verification links through the shared document shell so the integrations load centrally across the public website.</li>
        <li>Added a site-wide Donorbox sticky popup widget through the shared body shell, keeping the existing Donate page content and support routes unchanged.</li>
        <li>Consolidated the footer-side third-party integrations into one Facebook SDK loader with app ID <code>670420541399530</code> and one Chatwoot loader for the APES workspace, avoiding the duplicated legacy Facebook snippets from the supplied markup.</li>
        <li>Preserved the APES column-card footer, required donation, Privacy Policy, Terms of Service and Change Log Hub links, left APES Newsroom routing unchanged, and synchronised the canonical version plus release records to v2.6.3.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: shared document head, shared body shell, shared footer, Change Log Hub, footer version display, root and public release records, and regenerated static HTML snapshots</li>
        <li>Files changed: shared PHP rendering, shared footer output, VERSION, public VERSION, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, donors, volunteers, staff, partners and general public visitors</li>
        <li>Public impact: visitors now receive the requested site-wide notification, donation, social-verification and support-widget integrations without a visible footer redesign or route change</li>
        <li>Internal impact: shared shell integrations are now centralised in one maintained source of truth, reducing duplication and keeping future release management simpler</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.6.2</li>
        <li>New version: v2.6.3</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: small site-wide public integration additions and shared-shell maintenance improvements without a breaking restructure or URL change</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: shared PHP renderer review, local PHP syntax checks, static HTML export and generated HTML inspection</li>
        <li>Manual checks completed: shared head/body/footer embed review, footer required link review, intranet link attribute review, APES Newsroom route review and changelog/version alignment review</li>
        <li>Known limitations: external widget runtime behaviour, live browser confirmation and deployed FTP validation still require a post-push check outside this repo-only implementation pass</li>
        <li>Rollback notes: restore the previous shared renderer, shared footer output, version files and changelog entries, then re-export the static HTML snapshots if the integration rollout needs to be reversed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable added changed public-facing" id="release-v262">
    <summary>
      <span class="release-version">v2.6.2</span>
      <span class="release-date">2026-06-03</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.6.2</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Added</span>
        <span class="pill pill-type">Changed</span>
      </div>
      <h3>Summary</h3>
      <p>Refreshed the Donate page with stronger area-of-greatest-need messaging, added a Donorbox popup donation button and donor wall, and synchronised the shared release records to the new stable patch version.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Rewrote the Donate page body copy to explain how flexible donations support rescue, rehabilitation, housing, daily welfare costs, education and public support across APES.</li>
        <li>Added the requested Donorbox popup button installer and secure donation button for the approved area-of-greatest-need route, while keeping a standard link fallback if JavaScript or popups are unavailable.</li>
        <li>Added the requested Donorbox donor wall embed inside an APES-styled supporter section so the page shows visible community backing without changing the wider site architecture.</li>
        <li>Added shared styling for the Donorbox donation section and embed, preserved the required footer links and version display, kept APES Newsroom routing unchanged, and synchronised the canonical version plus changelog records to v2.6.2.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: Donate page, shared CSS, Change Log Hub, footer version display, root and public release records, and regenerated static HTML snapshots</li>
        <li>Files changed: shared site data, shared CSS, VERSION, public VERSION, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, donors, volunteers, staff, partners and general public visitors</li>
        <li>Public impact: visitors now get clearer donation messaging, a popup donation flow and visible donor-wall engagement on the main Donate page</li>
        <li>Internal impact: the APES donation journey now has a clearer shared content source and release record for future fundraising updates</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.6.1</li>
        <li>New version: v2.6.2</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: public-facing donation content and embed improvements without a breaking restructure or route change</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: shared PHP and CSS inspection, local PHP syntax checks, static HTML export and generated HTML inspection</li>
        <li>Manual checks completed: Donate page copy review, Donorbox popup button presence review, donor wall embed review, footer required link review, APES Newsroom route review and changelog/version alignment review</li>
        <li>Known limitations: popup, external embed behaviour and deployed FTP validation still require a live browser and post-push deployment check outside this repo-only implementation pass</li>
        <li>Rollback notes: restore the previous donate copy, shared CSS, version files and changelog entries, then re-export the static HTML snapshots if the release needs to be reversed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed accessibility public-facing" id="release-v261">
    <summary>
      <span class="release-version">v2.6.1</span>
      <span class="release-date">2026-06-03</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.6.1</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
        <span class="pill pill-accessibility">Accessibility</span>
      </div>
      <h3>Summary</h3>
      <p>Added three popup-enabled booking routes to the Bookings page and regrouped the shared footer so key public, legal and staff routes are easier to scan without changing the wider site architecture.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Added a dedicated three-option booking chooser to the Bookings page for APES Bookings, Shelter and Rescue Bookings, and Pet Care Clinic Bookings, each pointing to the requested workspace appointment form.</li>
        <li>Added shared popup-window launch behaviour in the site JavaScript so the booking routes open in a centred external window when allowed, while preserving a normal new-tab fallback when popups are blocked or JavaScript is unavailable.</li>
        <li>Extended the shared footer data model so footer items can render as standard links, highlighted route tiles or non-link subheadings, then regrouped the footer into clearer About, services, support and policy/update/staff sections.</li>
        <li>Preserved the required donation, Privacy Policy, Terms of Service, Change Log Hub, APES Newsroom and intranet links, kept APES Newsroom routing unchanged, and synchronised the canonical version plus changelog records to v2.6.1.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: Bookings, shared footer, Change Log Hub, footer version display, root and public release records, and regenerated static HTML snapshots</li>
        <li>Files changed: shared PHP rendering, shared CSS, shared JS, shared site data, VERSION, public VERSION, root CHANGELOG, public CHANGELOG, README and regenerated static HTML snapshots</li>
        <li>User groups affected: service users, supporters, volunteers, staff, partners and general public visitors</li>
        <li>Public impact: visitors can now choose the correct external booking form directly from the Bookings page and scan the shared footer more quickly for core service, legal and intranet routes</li>
        <li>Internal impact: footer presentation intent and popup booking behaviour are now managed centrally so future route and release updates stay aligned</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.6.0</li>
        <li>New version: v2.6.1</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: small public-facing booking and footer usability improvements without a breaking restructure or URL change</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: local PHP syntax checks, shared CSS/JS/PHP inspection, static HTML export and generated HTML inspection</li>
        <li>Manual checks completed: Bookings chooser label and URL review, popup-launch fallback review, desktop and mobile footer grouping review, footer required link review, APES Newsroom route review and changelog/version alignment review</li>
        <li>Known limitations: popup and responsive verification in this environment is based on source and generated-output inspection rather than a full deployed browser pass against Cloudron hosting</li>
        <li>Rollback notes: restore the previous shared footer data, JS, CSS, bookings content, version files and changelog entries, then re-export the static HTML snapshots if the release needs to be reversed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed accessibility compliance public-facing" id="release-v260">
    <summary>
      <span class="release-version">v2.6.0</span>
      <span class="release-date">2026-06-03</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.6.0</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-accessibility">Accessibility</span>
        <span class="pill pill-compliance">Compliance</span>
      </div>
      <h3>Summary</h3>
      <p>Added a shared APES image system across key public routes, bringing supportive photography and illustration into the homepage, route-finder, fundraising, bookings, educational and relocation journeys while keeping release, footer and Newsroom rules aligned.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Added six deployable APES image assets plus WebP variants inside the public asset tree and wired them through shared PHP rendering with explicit dimensions, responsive picture markup and lazy loading for non-hero placements.</li>
        <li>Extended the shared renderer and stylesheet so homepage hero media, route-finder illustrations and reusable feature-media sections can be enabled per page without changing public routes, footer links or form behaviour.</li>
        <li>Placed the new visuals on the homepage, Services hub, Bookings, Educational visits, About APES, Fundraising priorities and Help Us Move routes using conservative descriptive copy that supports the public journeys without implying named animals or live case evidence.</li>
        <li>Preserved APES Newsroom routing, the APES column-card footer, required donation, Privacy Policy, Terms of Service and Change Log Hub links, and synchronised the canonical version plus changelog records to v2.6.0.</li>
        <li>Checked for a related GitHub issue and found no explicit linked issue in the current repo context, so issue-update templates were not posted during this implementation pass.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: homepage hero and route finder, Services hub, Bookings, Educational visits, About APES, Fundraising priorities, Help Us Move, Change Log Hub, footer version display and regenerated static HTML snapshots</li>
        <li>Files changed: shared PHP rendering, shared CSS, shared site data, VERSION, public VERSION, root CHANGELOG, public CHANGELOG and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, adopters, service users, volunteers, partners, educators and general public visitors</li>
        <li>Public impact: visitors now see warmer, route-relevant visuals across key public journeys with responsive image delivery and no route or footer-link changes</li>
        <li>Internal impact: reusable image metadata and shared media rendering now support future APES visual placements from a central source of truth</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.5.0</li>
        <li>New version: v2.6.0</li>
        <li>Version type: minor stable</li>
        <li>Reason for version bump: new public-facing visual content and shared rendering capabilities added across multiple core routes without a breaking restructure</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: local PHP syntax checks, shared CSS/PHP inspection, WebP asset generation, static HTML export and generated HTML inspection</li>
        <li>Manual checks completed: homepage hero and compact route-finder layout review, Services hub route-finder and care-image review, Bookings and Educational visits image stacking review, Fundraising and Help Us Move image placement review, footer required link review, APES Newsroom route review and changelog/version alignment review</li>
        <li>Known limitations: validation in this environment focused on generated output and local inspection rather than a full live-browser comparison on every published route</li>
        <li>Rollback notes: restore the previous shared renderer, shared CSS, image references, version files and changelog entries, then re-export the static HTML snapshots if the image rollout needs to be reversed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed accessibility compliance public-facing" id="release-v250">
    <summary>
      <span class="release-version">v2.5.0</span>
      <span class="release-date">2026-06-03</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.5.0</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-accessibility">Accessibility</span>
        <span class="pill pill-compliance">Compliance</span>
      </div>
      <h3>Summary</h3>
      <p>Promoted the shared hero into a full-width site header panel, moved the previous hero-side support cards into the lower sidebar across all rendered public routes, and synchronised the release metadata to the new minor stable version.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Updated the shared PHP page renderer so the hero panel now spans the full content width while the logo, contact and connected-service cards render in the lower page sidebar above page-specific related links.</li>
        <li>Reworked the shared stylesheet so the new full-width hero and lower two-column body/sidebar layout behave consistently across the homepage, inner content routes, Change Log Hub and 404 page without changing route copy or CTA destinations.</li>
        <li>Preserved APES Newsroom routing, footer structure, required donation, Privacy Policy, Terms of Service and Change Log Hub links, and intranet link rules while applying the shared layout shift.</li>
        <li>Bumped the canonical version and synchronised the website Change Log Hub, root changelog, public changelog mirror and exported static HTML snapshots to v2.5.0.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: homepage hero, all shared inner-page hero/sidebar layouts, Change Log Hub, 404 page, footer version display and regenerated static HTML snapshots</li>
        <li>Files changed: shared PHP rendering, shared CSS, shared site data, VERSION, public VERSION, root CHANGELOG, public CHANGELOG and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, adopters, service users, volunteers, partners and general public visitors</li>
        <li>Public impact: visitors now see a full-width hero followed by a clearer lower content/sidebar layout that keeps support cards available without crowding the page header.</li>
        <li>Internal impact: the shared renderer now owns a single full-width hero pattern across all exported public routes and the release metadata is aligned at v2.5.0.</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.4.6</li>
        <li>New version: v2.5.0</li>
        <li>Version type: minor stable</li>
        <li>Reason for version bump: site-wide public layout change across the shared rendering system without route or content-model changes.</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: shared PHP renderer review, shared CSS review, local PHP syntax checks, static HTML export and generated HTML inspection</li>
        <li>Manual checks completed: homepage, representative top-level route, nested route, Change Log Hub and 404 layout review; footer required link review; intranet link attribute review; APES Newsroom route review; changelog/version alignment review</li>
        <li>Known limitations: final browser QA is limited to local rendered/output inspection in this environment rather than a full live-browser pass across every route</li>
        <li>Rollback notes: restore the previous shared renderer, CSS and release metadata, then re-export the static HTML snapshots if the new layout needs to be reverted</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed compliance accessibility public-facing" id="release-v246">
    <summary>
      <span class="release-version">v2.4.6</span>
      <span class="release-date">2026-06-03</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.4.6</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
        <span class="pill pill-compliance">Compliance</span>
        <span class="pill pill-accessibility">Accessibility</span>
      </div>
      <h3>Summary</h3>
      <p>Tightened the shared hero layout so the primary content panel no longer leaves an oversized gap below the call-to-action buttons, and synchronised the release metadata back to the canonical site version.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Updated the shared hero grid so the hero panel aligns to its content instead of stretching to match the taller hero aside, which removes the empty space beneath the hero buttons on the homepage and all shared inner-page hero variants.</li>
        <li>Applied the change in the shared stylesheet only, leaving route content, hero copy, buttons, footer structure and APES Newsroom destinations untouched.</li>
        <li>Re-synchronised the canonical version, website Change Log Hub and root changelog after correcting the existing mismatch between the repository <code>VERSION</code> file and the rendered site release metadata.</li>
        <li>Regenerated the exported static HTML snapshots and synchronised the canonical version, website Change Log Hub and root changelog.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: homepage shared hero, inner-page shared hero pattern, Change Log Hub, footer version display and regenerated static HTML snapshots</li>
        <li>Files changed: shared CSS, shared site data, VERSION, root CHANGELOG and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, adopters, service users, volunteers, partners and general public visitors</li>
        <li>Public impact: visitors now see a tighter hero layout with less empty space beneath hero actions, while footer version text and release records now consistently show v2.4.6</li>
        <li>Internal impact: shared hero sizing now behaves consistently across rendered routes and the repository release metadata matches the generated site output again</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.4.5</li>
        <li>New version: v2.4.6</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: small public-facing layout fix across the shared hero component with no route or content restructure</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: shared CSS inspection, local PHP syntax checks, static HTML export and generated HTML inspection</li>
        <li>Manual checks completed: homepage and representative inner-page hero spacing review, footer required link review, footer version alignment review, APES Newsroom route review and changelog synchronisation review</li>
        <li>Known limitations: final browser QA depends on local rendered output spot checks rather than opening every exported route individually</li>
        <li>Rollback notes: restore the previous shared CSS and release metadata, then re-export the static HTML snapshots if needed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable added changed compliance accessibility public-facing" id="release-v240">
    <summary>
      <span class="release-version">v2.4.0</span>
      <span class="release-date">2026-06-03</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.4.0</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Added</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-compliance">Compliance</span>
      </div>
      <h3>Summary</h3>
      <p>Added a new Services hub with a shared route finder, replaced placeholder public social links with verified APES channels, strengthened mission and welfare-policy content, and aligned opening-hours, visit and footer guidance across the site.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Created a new public <code>/services/</code> hub and shared route-finder component, then reused the same route data on the homepage compact finder and the expanded Services page search and filter interface.</li>
        <li>Updated shared navigation, footer content and breadcrumb routing so the Services hub, mission routes, visit guidance, opening times and welfare policies are easier to find.</li>
        <li>Replaced placeholder header social links with verified <code>apesorguk</code> Facebook, Instagram, X, YouTube, Threads and Bluesky profiles, while keeping community-only channels on the Socials page.</li>
        <li>Expanded the mission, ethical rehabilitation, visit, opening-times, volunteer, boarding, educational, therapy, fundraising, sponsor and welfare-policy routes using current repo truth plus review notes where external verification is still needed.</li>
        <li>Kept APES Newsroom as the central public news destination, preserved required footer links and APES CIC identity, and synchronised the canonical version, Change Log Hub and root changelog before re-exporting static HTML snapshots.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: homepage, Services hub, bookings, mission routes, visit/opening-times routes, welfare policy routes, socials, footer, header, Change Log Hub and regenerated static HTML snapshots</li>
        <li>Files changed: shared PHP rendering, shared site data, shared CSS, shared JS, VERSION, root CHANGELOG and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, adopters, service users, volunteers, partners and general public visitors</li>
        <li>Public impact: visitors now have clearer route selection, verified public social channels, stronger welfare-policy visibility and more consistent visit/contact guidance</li>
        <li>Internal impact: shared route-finder and social-profile data now drive multiple public components from one source of truth</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.3.3</li>
        <li>New version: v2.4.0</li>
        <li>Version type: minor stable</li>
        <li>Reason for version bump: new public routing features, broader content expansion and shared navigation/footer improvements without breaking route removals</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: local PHP syntax checks, static HTML export, shared JS/CSS inspection and generated HTML inspection</li>
        <li>Manual checks completed: homepage route finder, Services hub search/filter output, welfare-policy visibility, footer required links, verified social placement and changelog/version alignment review</li>
        <li>Known limitations: visual QA depends on local rendered inspection in this environment, and some externally hosted service claims remain intentionally conservative until APES approves stronger source text</li>
        <li>Rollback notes: restore the previous shared site data, rendering/CSS/JS changes, version and changelog entries, then re-export the static HTML snapshots if needed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed accessibility public-facing" id="release-v232">
    <summary>
      <span class="release-version">v2.3.2</span>
      <span class="release-date">2026-06-03</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.3.2</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
      </div>
      <h3>Summary</h3>
      <p>Corrected broken apostrophe rendering in shared navigation and affected page copy so public text displays cleanly across the exported site.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Replaced corrupted mojibake apostrophes in shared APES content source strings, including the About APES mega-menu description used across the site header.</li>
        <li>Corrected affected public page copy in the pet boarding, animal therapy and staff routes so the same text issue no longer appears in exported page bodies.</li>
        <li>Kept APES branding, footer structure, required legal links and APES Newsroom routing unchanged while applying the text-only fix.</li>
        <li>Regenerated the exported static HTML snapshots and synchronised the canonical version, Change Log Hub and root changelog.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: shared header navigation, homepage, pet boarding, animal therapy, staff, Change Log Hub and regenerated static HTML snapshots</li>
        <li>Files changed: shared site data, VERSION, root CHANGELOG and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, adopters, service users, volunteers, partners and general public visitors</li>
        <li>Public impact: broken apostrophes now render correctly in shared navigation and affected page copy</li>
        <li>Internal impact: the PHP content source is now clean, so future exports inherit the corrected text consistently</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.3.1</li>
        <li>New version: v2.3.2</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: small public-facing text correction across shared content with no structural or URL change</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: local PHP syntax checks, static HTML export, source text scan and generated HTML inspection</li>
        <li>Manual checks completed: homepage, pet boarding, animal therapy, staff and Change Log Hub output review plus footer version/link alignment review</li>
        <li>Known limitations: browser-based visual QA was unavailable in this session, so validation relied on source inspection and regenerated HTML review</li>
        <li>Rollback notes: restore the previous shared site data, version and changelog entries, then re-export the static HTML snapshots if needed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable changed fixed accessibility public-facing" id="release-v231">
    <summary>
      <span class="release-version">v2.3.1</span>
      <span class="release-date">2026-06-03</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.3.1</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
      </div>
      <h3>Summary</h3>
      <p>Adjusted the homepage spotlight component spacing so the nested mission cards sit in from the parent panel edges and read more clearly across desktop, tablet and mobile layouts.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Updated the shared spotlight grid CSS so nested spotlight cards keep visible inner gutter spacing instead of reading edge-to-edge inside the parent mission panel.</li>
        <li>Kept the existing homepage spotlight markup unchanged and applied the fix through the reusable component classes so any future page using the same pattern inherits the corrected spacing.</li>
        <li>Preserved the existing responsive layout behaviour while keeping the APES teal-led branding, footer structure and APES Newsroom routing unchanged.</li>
        <li>Regenerated the exported static HTML snapshots and synchronised the canonical version, Change Log Hub and root changelog.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: homepage spotlight mission panel, shared spotlight component styling, Change Log Hub and release metadata</li>
        <li>Files changed: shared CSS, shared site data, VERSION, root CHANGELOG and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, adopters, service users, volunteers, partners and general public visitors</li>
        <li>Public impact: spotlight sub-cards now keep clearer spacing from the parent panel edges, improving readability and visual polish</li>
        <li>Internal impact: shared spotlight component styling now applies the inset consistently wherever the pattern is reused</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.3.0</li>
        <li>New version: v2.3.1</li>
        <li>Version type: patch stable</li>
        <li>Reason for version bump: small public-facing layout and accessibility polish with no structural or URL change</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: shared CSS inspection, static HTML export and generated HTML inspection</li>
        <li>Manual checks completed: spotlight component usage search, footer required link review, footer version alignment review and Change Log Hub synchronisation review</li>
        <li>Known limitations: in-app browser validation was unavailable in this session, so final verification relied on source inspection and regenerated output review rather than a rendered browser screenshot</li>
        <li>Rollback notes: restore the previous shared CSS, version and changelog entries, then re-export the static HTML snapshots if needed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="stable added fixed accessibility public-facing" id="release-v230">
    <summary>
      <span class="release-version">v2.3.0</span>
      <span class="release-date">2026-06-03</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.3.0</span>
        <span class="pill pill-status">Stable</span>
        <span class="pill pill-type">Added</span>
        <span class="pill pill-fix">Fix</span>
      </div>
      <h3>Summary</h3>
      <p>Added a site-wide breadcrumb trail, kept the shared navigation closed after menu clicks, and corrected the FileZilla deployment target so uploads point at the public site root instead of generated artefacts.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Added a breadcrumb trail above the hero on every non-home page using the shared page metadata and route-aware section labels so nested routes stay readable.</li>
        <li>Kept the menu closed after clicking a navigation item by removing the automatic open state from the shared header while preserving active-section styling.</li>
        <li>Corrected the FileZilla deployment profile so uploads target the website root instead of the nested `outputs/.../.codex-exec/...` path and do not keep queueing non-public artefacts.</li>
        <li>Regenerated the exported static HTML snapshots and synchronised the canonical version, Change Log Hub and root changelog.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: homepage, all non-home pages, nested service and policy routes, legacy news routes, Change Log Hub and shared release metadata</li>
        <li>Files changed: shared PHP rendering, shared CSS, shared site data, VERSION, root CHANGELOG and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, adopters, service users, volunteers, partners and general public visitors</li>
        <li>Public impact: visitors now get an immediate sense of location on the site, and menu navigation closes more predictably after selection</li>
        <li>Internal impact: the shared navigation state is simpler and the deployment profile no longer points uploads at generated preflight artefacts</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.2.1</li>
        <li>New version: v2.3.0</li>
        <li>Version type: minor stable</li>
        <li>Reason for version bump: new breadcrumb navigation and shared navigation/deployment corrections without URL restructuring</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: local PHP syntax checks, static HTML export and generated HTML inspection</li>
        <li>Manual checks completed: breadcrumb placement on representative routes, menu close behaviour, Change Log Hub hero and footer version/link review, FileZilla queue target inspection</li>
        <li>Known limitations: FileZilla upload verification is based on profile inspection in this environment rather than an interactive FTP session</li>
        <li>Rollback notes: restore the previous shared PHP, CSS, version and changelog entries, then re-export the static HTML snapshots if needed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="beta changed fixed compliance accessibility public-facing" id="release-v220b">
    <summary>
      <span class="release-version">v2.2.0b</span>
      <span class="release-date">2026-06-03</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.2.0b</span>
        <span class="pill pill-status">Beta</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
        <span class="pill pill-compliance">Compliance</span>
      </div>
      <h3>Summary</h3>
      <p>Refreshed site-wide hero copy and page calls to action so each public route now leads with clearer, service-specific guidance instead of release or rebuild messaging.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Rewrote the homepage hero and spotlight section around APES CIC public purpose, support journeys and welfare services.</li>
        <li>Updated shared hero summaries and pill labels across service, support, policy, contact, news and archive pages to make them more page-specific and user-facing.</li>
        <li>Adjusted hero buttons so each page points to the most relevant next action, including donation, booking, contact, policy and APES Newsroom routes.</li>
        <li>Kept the shared footer structure and required links intact while synchronising the canonical version, Change Log Hub and root changelog.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: homepage, service pages, support pages, policy pages, contact routes, news bridges, legacy archives and release metadata</li>
        <li>Files changed: shared site data, VERSION, root CHANGELOG and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, adopters, service users, volunteers, partners and general public visitors</li>
        <li>Public impact: page introductions and top-level actions are now clearer, more relevant to each route and less technical in tone</li>
        <li>Internal impact: shared hero content is now more consistent across the full exported site</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.1.2b</li>
        <li>New version: v2.2.0b</li>
        <li>Version type: minor beta</li>
        <li>Reason for version bump: broad public-facing content and CTA refresh across many shared routes without a structural or URL-breaking change</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: local PHP syntax checks, static HTML export and generated HTML inspection</li>
        <li>Manual checks completed: homepage copy review, representative service and policy page review, news bridge review, footer link presence and release display alignment</li>
        <li>Known limitations: validation focused on source-driven export and spot-checking rather than exhaustive browser testing of every route</li>
        <li>Rollback notes: restore the previous shared copy, version and changelog entries, then re-export the static HTML snapshots if needed</li>
      </ul>
    </div>
  </details>
  <details class="release-card" data-release-card data-tags="beta changed fixed compliance accessibility public-facing" id="release-v212b">
    <summary>
      <span class="release-version">v2.1.2b</span>
      <span class="release-date">2026-06-03</span>
    </summary>
    <div class="release-body">
      <div class="pill-row">
        <span class="pill pill-version">Version v2.1.2b</span>
        <span class="pill pill-status">Beta</span>
        <span class="pill pill-type">Changed</span>
        <span class="pill pill-fix">Fix</span>
        <span class="pill pill-compliance">Compliance</span>
      </div>
      <h3>Summary</h3>
      <p>Updated the shared navigation script so the mobile menu closes when visitors activate any primary navigation link or open a new page, including back-forward cache restores, while keeping the shared header and footer shell intact.</p>
      <h3>Detailed changes</h3>
      <ul class="clean-list">
        <li>Updated the shared navigation script so any primary navigation link activation closes the mobile menu immediately.</li>
        <li>Added page transition guards so the menu state resets on navigation and back-forward cache restores, preventing stale open menus after moving to a new page.</li>
        <li>Kept the existing shared header and footer structure intact so the fix applies site-wide without per-page markup changes.</li>
        <li>Bumped the canonical website version and synchronised the release record across the website Change Log Hub and root changelog.</li>
      </ul>
      <h3>Affected areas</h3>
      <ul class="clean-list">
        <li>Website: www.apes.org.uk</li>
        <li>Page or route: shared site-wide navigation, homepage, content pages, change-log hub and release metadata</li>
        <li>Files changed: shared JS, shared site data, VERSION, root CHANGELOG and regenerated static HTML snapshots</li>
        <li>User groups affected: supporters, adopters, service users, volunteers, partners and general public visitors</li>
        <li>Public impact: the mobile menu now closes reliably when visitors move to a new page, reducing confusion and accidental obstruction of content</li>
        <li>Internal impact: the shared navigation behaviour now stays consistent across all rendered pages</li>
      </ul>
      <h3>Version decision</h3>
      <ul class="clean-list">
        <li>Previous version: v2.1.1b</li>
        <li>New version: v2.1.2b</li>
        <li>Version type: patch beta</li>
        <li>Reason for version bump: small shared-behaviour fix with no breaking URL or content restructure</li>
      </ul>
      <h3>Validation</h3>
      <ul class="clean-list">
        <li>Checks run: local PHP syntax checks, static HTML export, generated HTML inspection and browser interaction verification</li>
        <li>Manual checks completed: mobile menu close-on-link activation, new-page navigation reset and back-forward cache restore</li>
        <li>Known limitations: the browser test used the local development renderer and the static HTML export is regenerated from the PHP source of truth</li>
        <li>Rollback notes: restore the previous JS and version/changelog entries, then re-export the static HTML snapshots if needed</li>
      </ul>
    </div>
  </details>
</section>
HTML,
                'related_links' => [
                    ['label' => 'Donate', 'href' => '/donate/'],
                    ['label' => 'Privacy Policy', 'href' => '/policies/privacy/'],
                    ['label' => 'Terms of Service', 'href' => '/policies/terms-of-service/'],
                ],
            ],
        ],
    ];

    return $data;
}

function apes_route_map(): array
{
    $map = [
        '/' => 'home',
        '/index' => 'home',
        '/index/' => 'home',
    ];

    foreach (apes_site_data()['pages'] as $key => $page) {
        $route = $page['route'];
        $map[$route] = $key;

        if ($route !== '/') {
            $map[rtrim($route, '/')] = $key;
        }
    }

    foreach (apes_error_pages() as $key => $page) {
        $route = $page['route'];
        $map[$route] = $key;
    }

    return $map;
}

function apes_path_from_request_uri(string $requestUri): string
{
    $path = parse_url($requestUri, PHP_URL_PATH) ?: '/';
    $path = '/' . ltrim($path, '/');

    return $path === '' ? '/' : $path;
}

function apes_page_key_from_request(string $requestUri): ?string
{
    return apes_route_map()[apes_path_from_request_uri($requestUri)] ?? null;
}

function apes_canonical_route_for_key(string $pageKey): ?string
{
    return apes_site_data()['pages'][$pageKey]['route']
        ?? apes_error_pages()[$pageKey]['route']
        ?? null;
}

function apes_public_routes(): array
{
    $routes = [];

    foreach (apes_site_data()['pages'] as $page) {
        if (apes_page_is_indexable($page)) {
            $routes[] = $page['route'];
        }
    }

    return $routes;
}

function apes_breadcrumb_section(string $section): array
{
    return match ($section) {
        'news' => ['label' => 'News', 'href' => '/news/'],
        'mission' => ['label' => 'Mission', 'href' => '/mission/our-main-mission-statement/'],
        'policies' => ['label' => 'Policies', 'href' => '/policies/'],
        'services' => ['label' => 'Services', 'href' => '/services/'],
        'donating' => ['label' => 'Support APES', 'href' => '/donate/'],
        'about-us' => ['label' => 'About APES', 'href' => '/about-us/'],
        'contact-centre' => ['label' => 'Contact centre', 'href' => '/contact-centre/'],
        'change-log-hub' => ['label' => 'Change Log Hub', 'href' => '/change-log-hub/'],
        default => ['label' => ucwords(str_replace('-', ' ', $section))],
    };
}

function apes_breadcrumbs_for_page(array $page, ?string $pageKey = null): array
{
    if (($pageKey ?? '') === 'home') {
        return [];
    }

    $title = trim((string) ($page['breadcrumb_label'] ?? $page['title'] ?? ''));

    if ($title === '') {
        $title = 'Page not found';
    }

    if ($pageKey === null || $pageKey === '' || !isset(apes_site_data()['pages'][$pageKey])) {
        return [
            ['label' => 'Home', 'href' => '/'],
            ['label' => $title, 'current' => true],
        ];
    }

    $route = trim((string) ($page['route'] ?? ''), '/');
    $crumbs = [
        ['label' => 'Home', 'href' => '/'],
    ];

    if ($route !== '') {
        $segments = array_values(array_filter(explode('/', $route), 'strlen'));

        if (count($segments) > 1) {
            $section = apes_breadcrumb_section($segments[0]);

            if (!empty($section['label'])) {
                $crumbs[] = $section;
            }
        }
    }

    $crumbs[] = ['label' => $title, 'current' => true];

    return $crumbs;
}


