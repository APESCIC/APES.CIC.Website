<?php
declare(strict_types=1);

require_once __DIR__ . '/site-data.php';

if (!function_exists('apes_current_page')) {
    function apes_current_page(): array
    {
        global $page_key;
        global $apes_override_page;

        $site = apes_site_data();
        $errorPages = apes_error_pages();

        if (is_array($apes_override_page ?? null)) {
            $page = $apes_override_page;

            if (isset($page['http_status'])) {
                http_response_code((int) $page['http_status']);
            }

            return [$site, $page];
        }

        $page = $site['pages'][$page_key] ?? $errorPages[$page_key] ?? null;

        if ($page === null) {
            http_response_code(404);
            $page = $errorPages['error-404'];
        } elseif (isset($page['http_status'])) {
            http_response_code((int) $page['http_status']);
        }

        return [$site, $page];
    }

    function apes_asset(string $path): string
    {
        return '/assets/' . ltrim($path, '/');
    }

    function apes_image_by_key(array $site, string $key): ?array
    {
        return $site['feature_media'][$key] ?? null;
    }

    function apes_render_picture(array $media, string $imgClass = '', bool $lazy = true, bool $highPriority = false): string
    {
        $imgClass = trim($imgClass);
        $classAttr = $imgClass === '' ? '' : ' class="' . htmlspecialchars($imgClass, ENT_QUOTES) . '"';
        $loadingAttr = $lazy ? ' loading="lazy"' : '';
        $fetchPriorityAttr = $highPriority ? ' fetchpriority="high"' : '';

        return sprintf(
            '<picture><source srcset="%s" type="image/webp" /><img%s src="%s" alt="%s" width="%d" height="%d"%s decoding="async"%s /></picture>',
            htmlspecialchars((string) $media['webp'], ENT_QUOTES),
            $classAttr,
            htmlspecialchars((string) $media['png'], ENT_QUOTES),
            htmlspecialchars((string) $media['alt'], ENT_QUOTES),
            (int) $media['width'],
            (int) $media['height'],
            $loadingAttr,
            $fetchPriorityAttr
        );
    }

    function apes_render_feature_media(array $feature, array $site): string
    {
        $media = apes_image_by_key($site, (string) $feature['image']);

        if ($media === null) {
            return '';
        }

        $sectionClass = trim('section-shell feature-media ' . (string) ($feature['class_name'] ?? ''));
        $eyebrow = trim((string) ($feature['eyebrow'] ?? ''));
        $title = trim((string) ($feature['title'] ?? ''));
        $summary = trim((string) ($feature['summary'] ?? ''));

        ob_start();
        ?>
<section class="<?= htmlspecialchars($sectionClass, ENT_QUOTES) ?>">
  <div class="feature-media__content">
    <?php if ($eyebrow !== ''): ?>
      <p class="eyebrow"><?= htmlspecialchars($eyebrow, ENT_QUOTES) ?></p>
    <?php endif; ?>
    <?php if ($title !== ''): ?>
      <h2><?= htmlspecialchars($title, ENT_QUOTES) ?></h2>
    <?php endif; ?>
    <?php if ($summary !== ''): ?>
      <p><?= htmlspecialchars($summary, ENT_QUOTES) ?></p>
    <?php endif; ?>
  </div>
  <div class="feature-media__visual">
    <?= apes_render_picture($media, 'feature-media__image') ?>
  </div>
</section>
        <?php

        return (string) ob_get_clean();
    }

    function apes_page_url(array $page): string
    {
        return rtrim(APES_PRIMARY_DOMAIN, '/') . $page['route'];
    }

    function apes_render_link(array $link, string $class = 'text-link'): string
    {
        $href = htmlspecialchars($link['href'], ENT_QUOTES);
        $label = htmlspecialchars($link['label'], ENT_QUOTES);
        $attributes = [];

        if (!empty($link['external'])) {
            $attributes[] = 'target="_blank"';
            $relParts = !empty($link['nofollow'])
                ? ['nofollow', 'noopener', 'noreferrer']
                : ['noopener', 'noreferrer'];

            $attributes[] = 'rel="' . implode(' ', $relParts) . '"';
        }

        if (!empty($link['target']) && empty($link['external'])) {
            $attributes[] = 'target="' . htmlspecialchars((string) $link['target'], ENT_QUOTES) . '"';
        }

        if (!empty($link['rel'])) {
            $attributes[] = 'rel="' . htmlspecialchars((string) $link['rel'], ENT_QUOTES) . '"';
        }

        return '<a class="' . $class . '" href="' . $href . '"' . (empty($attributes) ? '' : ' ' . implode(' ', $attributes)) . '>' . $label . '</a>';
    }

    function apes_render_breadcrumb_item(array $crumb): string
    {
        $label = htmlspecialchars((string) ($crumb['label'] ?? ''), ENT_QUOTES);

        if (!empty($crumb['current'])) {
            return '<span aria-current="page">' . $label . '</span>';
        }

        if (!empty($crumb['href'])) {
            $href = htmlspecialchars((string) $crumb['href'], ENT_QUOTES);

            return '<a href="' . $href . '">' . $label . '</a>';
        }

        return '<span>' . $label . '</span>';
    }

    function apes_render_shared_sidebar_cards(array $site): string
    {
        ob_start();
        ?>
<div class="mini-panel brand-feature-panel">
  <picture>
    <source srcset="<?= htmlspecialchars($site['brand']['logo_feature_webp'], ENT_QUOTES) ?>" type="image/webp" />
    <img src="<?= htmlspecialchars($site['brand']['logo_feature_png'], ENT_QUOTES) ?>" alt="Association of Protecting Exotic Species CIC logo" width="320" height="277" />
  </picture>
</div>
<div class="mini-panel">
  <p class="eyebrow">Contact APES</p>
  <ul class="clean-list">
    <li><?= apes_render_contact_email_link() ?></li>
    <li><?= apes_render_contact_phone_link() ?></li>
    <li><?= htmlspecialchars((string) ($site['postal_address_display'] ?? APES_POSTAL_ADDRESS_DISPLAY), ENT_QUOTES) ?></li>
  </ul>
</div>
<div class="mini-panel">
  <p class="eyebrow">Connected services</p>
  <ul class="clean-list">
    <li><a href="https://www.apesshelter.org.uk/" target="_blank" rel="noopener noreferrer">APES Shelter &amp; Rescue</a></li>
    <li><a href="https://www.apespetcare.org.uk/" target="_blank" rel="noopener noreferrer">APES Pet Care Clinic</a></li>
    <li><a href="https://www.apesshop.co.uk/" target="_blank" rel="noopener noreferrer">APES Pet Shop</a></li>
    <li><a href="https://www.myapes.me.uk/" target="_blank" rel="noopener noreferrer">MyAPES</a></li>
  </ul>
</div>
        <?php

        return (string) ob_get_clean();
    }

    function apes_search_results(array $pages): array
    {
        $query = trim((string) ($_GET['q'] ?? ''));

        if ($query === '') {
            return [$query, []];
        }

        $needle = mb_strtolower($query);
        $results = [];

        foreach ($pages as $key => $page) {
            if (!apes_page_is_indexable($page) || !empty($page['exclude_from_search'])) {
                continue;
            }

            $haystack = mb_strtolower(
                $page['title'] . ' ' .
                $page['description'] . ' ' .
                $page['route'] . ' ' .
                strip_tags($page['body_html'])
            );

            if (str_contains($haystack, $needle)) {
                $results[$key] = $page;
            }
        }

        return [$query, $results];
    }

    function apes_primary_nav_group(string $pageKey): string
    {
        return match ($pageKey) {
            'about-us', 'news', 'change-log-hub', 'search', 'socials', 'apes-communities', 'our-main-mission-statement', 'support-ethical-rehabilitation', 'the-center', 'opening-times', 'policies', 'terms-of-service', 'privacy', 'cookies', 'adoption-policy', 're-homing-policy', 'euthanasia-policy' => 'information',
            'sponsors', 'volunteer', 'donate', 'enterprise-mailing-list', 'help-us-move' => 'support',
            'contact' => 'contact',
            'home' => 'home',
            default => 'services',
        };
    }

    function apes_social_links_for_placement(array $profiles, string $placement): array
    {
        return array_values(array_filter(
            $profiles,
            static fn (array $profile): bool => in_array($placement, $profile['placements'] ?? [], true)
        ));
    }

    function apes_render_social_icon_link(array $profile, string $class = 'social-icon-link'): string
    {
        $href = htmlspecialchars((string) $profile['href'], ENT_QUOTES);
        $label = htmlspecialchars((string) $profile['label'], ENT_QUOTES);
        $icon = apes_social_icon_markup((string) ($profile['icon'] ?? ''));
        $rel = !empty($profile['external']) ? ' target="_blank" rel="noopener noreferrer"' : '';

        return '<a class="' . $class . '" href="' . $href . '"' . $rel . ' aria-label="' . $label . '"><span class="social-icon" aria-hidden="true">' . $icon . '</span><span class="sr-only">' . $label . '</span></a>';
    }

    function apes_render_route_finder(array $items, string $mode = 'compact', ?array $media = null): string
    {
        $isExpanded = $mode === 'expanded';
        $wrapperClass = $isExpanded ? 'route-finder route-finder-expanded' : 'route-finder route-finder-compact';
        $defaultItem = $items[0] ?? null;

        if ($defaultItem === null) {
            return '';
        }

        $filters = ['all' => 'All'];

        foreach ($items as $item) {
            foreach ($item['filters'] as $filter) {
                $filters[$filter] = ucfirst(str_replace('-', ' ', $filter));
            }
        }

        ob_start();
        ?>
<section class="<?= $wrapperClass ?>" data-route-finder data-route-mode="<?= htmlspecialchars($mode, ENT_QUOTES) ?>">
  <div class="<?= $media !== null ? 'feature-media feature-media--route-finder' : '' ?>">
    <div class="route-finder__intro<?= $media !== null ? ' feature-media__content' : '' ?>">
      <p class="eyebrow"><?= $isExpanded ? 'Services hub' : 'Fast route finder' ?></p>
      <h2><?= $isExpanded ? 'Find the right APES route before you contact us.' : 'Find the right APES route in under a minute.' ?></h2>
      <p><?= $isExpanded ? 'Search, filter or choose a common situation to see the most likely APES route, alternative routes and any safety-first guidance.' : 'Choose the situation that best matches what you need. APES will point you to the most appropriate public route without asking for case details here.' ?></p>
    </div>
    <?php if ($media !== null): ?>
      <div class="feature-media__visual route-finder__visual">
        <?= apes_render_picture($media, 'feature-media__image') ?>
      </div>
    <?php endif; ?>
  </div>
  <?php if ($isExpanded): ?>
    <div class="route-finder__tools">
      <label class="search-label" for="service-route-search">Search routes</label>
      <input id="service-route-search" class="release-search" type="search" placeholder="Search adopt, surrender, lost pet, boarding, clinic, donate or urgent help" data-route-search />
      <div class="filter-row">
        <?php foreach ($filters as $value => $label): ?>
          <button class="chip-button<?= $value === 'all' ? ' is-active' : '' ?>" type="button" data-route-filter="<?= htmlspecialchars($value, ENT_QUOTES) ?>"><?= htmlspecialchars($label, ENT_QUOTES) ?></button>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
  <div class="route-finder__layout">
    <div class="route-finder__options">
      <?php foreach ($items as $index => $item): ?>
        <?php
          $filtersValue = implode(' ', $item['filters']);
          $searchValue = strtolower($item['label'] . ' ' . implode(' ', $item['keywords']) . ' ' . $item['recommendation_title'] . ' ' . implode(' ', array_column($item['alternatives'], 'label')));
        ?>
        <button
          class="route-option<?= $index === 0 ? ' is-active' : '' ?>"
          type="button"
          data-route-option
          data-route-filters="<?= htmlspecialchars($filtersValue, ENT_QUOTES) ?>"
          data-route-search="<?= htmlspecialchars($searchValue, ENT_QUOTES) ?>"
          data-route-title="<?= htmlspecialchars($item['recommendation_title'], ENT_QUOTES) ?>"
          data-route-description="<?= htmlspecialchars($item['description'], ENT_QUOTES) ?>"
          data-route-primary-label="<?= htmlspecialchars($item['primary']['label'], ENT_QUOTES) ?>"
          data-route-primary-href="<?= htmlspecialchars($item['primary']['href'], ENT_QUOTES) ?>"
          data-route-primary-external="<?= !empty($item['primary']['external']) ? 'true' : 'false' ?>"
          data-route-secondary-label="<?= htmlspecialchars($item['secondary']['label'], ENT_QUOTES) ?>"
          data-route-secondary-href="<?= htmlspecialchars($item['secondary']['href'], ENT_QUOTES) ?>"
          data-route-secondary-external="<?= !empty($item['secondary']['external']) ? 'true' : 'false' ?>"
          data-route-note="<?= htmlspecialchars($item['note'], ENT_QUOTES) ?>"
          data-route-alternatives="<?= htmlspecialchars(json_encode($item['alternatives'], JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT), ENT_QUOTES) ?>"
        >
          <span class="route-option__title"><?= htmlspecialchars($item['label'], ENT_QUOTES) ?></span>
          <span class="route-option__summary"><?= htmlspecialchars($item['summary'], ENT_QUOTES) ?></span>
        </button>
      <?php endforeach; ?>
    </div>
    <div class="route-finder__result" aria-live="polite" data-route-result>
      <p class="eyebrow">Most likely route</p>
      <h3 data-route-result-title><?= htmlspecialchars($defaultItem['recommendation_title'], ENT_QUOTES) ?></h3>
      <p data-route-result-description><?= htmlspecialchars($defaultItem['description'], ENT_QUOTES) ?></p>
      <div class="action-row" data-route-result-actions>
        <?= apes_render_link($defaultItem['primary'], 'button button-primary') ?>
        <?= apes_render_link($defaultItem['secondary'], 'button button-secondary') ?>
      </div>
      <div class="route-finder__alt">
        <h4>Alternative routes</h4>
        <ul class="clean-list" data-route-result-alternatives>
          <?php foreach ($defaultItem['alternatives'] as $alternative): ?>
            <li><?= apes_render_link($alternative) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <p class="route-finder__note" data-route-result-note><?= htmlspecialchars($defaultItem['note'], ENT_QUOTES) ?></p>
    </div>
  </div>
  <?php if ($isExpanded): ?>
    <div class="route-finder__service-cards" data-route-card-grid>
      <?php foreach ($items as $item): ?>
        <?php $filtersValue = implode(' ', $item['filters']); ?>
        <article class="service-card route-service-card" data-route-card data-route-filters="<?= htmlspecialchars($filtersValue, ENT_QUOTES) ?>" data-route-search="<?= htmlspecialchars(strtolower($item['label'] . ' ' . implode(' ', $item['keywords'])), ENT_QUOTES) ?>">
          <h3><?= htmlspecialchars($item['recommendation_title'], ENT_QUOTES) ?></h3>
          <p><?= htmlspecialchars($item['description'], ENT_QUOTES) ?></p>
          <div class="action-stack">
            <?= apes_render_link($item['primary'], 'button button-primary') ?>
            <?= apes_render_link($item['secondary'], 'button button-secondary') ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <noscript>
    <div class="route-finder__fallback">
      <h3>All APES route options</h3>
      <ul class="clean-list">
        <?php foreach ($items as $item): ?>
          <li><strong><?= htmlspecialchars($item['label'], ENT_QUOTES) ?>:</strong> <?= apes_render_link($item['primary']) ?><?php if (!empty($item['note'])): ?>. <?= htmlspecialchars($item['note'], ENT_QUOTES) ?><?php endif; ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </noscript>
</section>
        <?php

        return (string) ob_get_clean();
    }

    function apes_render_social_cards(array $profiles): string
    {
        ob_start();
        ?>
<div class="card-grid card-grid-three social-card-grid">
  <?php foreach ($profiles as $profile): ?>
    <article class="info-card social-profile-card">
      <div class="social-profile-card__head">
        <span class="social-icon social-icon-large" aria-hidden="true"><?= apes_social_icon_markup((string) ($profile['icon'] ?? '')) ?></span>
        <div>
          <h3><?= htmlspecialchars((string) $profile['platform'], ENT_QUOTES) ?></h3>
          <p class="social-profile-card__handle"><?= htmlspecialchars((string) $profile['handle'], ENT_QUOTES) ?></p>
        </div>
      </div>
      <p><?= htmlspecialchars((string) $profile['summary'], ENT_QUOTES) ?></p>
      <?= apes_render_link(['label' => 'Open ' . $profile['platform'], 'href' => $profile['href'], 'external' => !empty($profile['external'])], 'button button-secondary') ?>
    </article>
  <?php endforeach; ?>
</div>
        <?php

        return (string) ob_get_clean();
    }

    function apes_render_page_body(string $bodyHtml, array $site, array $page): string
    {
        $replacements = [
            '[[ROUTE_FINDER_COMPACT]]' => apes_render_route_finder(
                $site['route_finder_items'] ?? [],
                'compact',
                !empty($page['route_finder_media']) ? apes_image_by_key($site, (string) $page['route_finder_media']) : null
            ),
            '[[ROUTE_FINDER_EXPANDED]]' => apes_render_route_finder(
                $site['route_finder_items'] ?? [],
                'expanded',
                !empty($page['route_finder_media']) ? apes_image_by_key($site, (string) $page['route_finder_media']) : null
            ),
            '[[SOCIAL_PRIMARY_GRID]]' => apes_render_social_cards(apes_social_links_for_placement($site['social_profiles'] ?? [], 'socials-primary')),
            '[[SOCIAL_COMMUNITY_GRID]]' => apes_render_social_cards(apes_social_links_for_placement($site['social_profiles'] ?? [], 'socials-community')),
        ];

        if (preg_match_all('/\[\[FEATURE_MEDIA:([a-z0-9\-]+)\]\]/i', $bodyHtml, $matches)) {
            foreach (array_unique($matches[1]) as $featureKey) {
                $feature = $site['feature_sections'][$featureKey] ?? null;

                if ($feature !== null) {
                    $replacements['[[FEATURE_MEDIA:' . $featureKey . ']]'] = apes_render_feature_media($feature, $site);
                }
            }
        }

        return strtr($bodyHtml, $replacements);
    }

    function apes_social_icon_markup(string $icon): string
    {
        return match ($icon) {
            'facebook' => <<<'SVG'
<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
  <path d="M13.4 21v-7.7h2.6l.4-3.1h-3v-2c0-.9.2-1.5 1.5-1.5h1.6V4c-.3 0-1.2-.1-2.3-.1-2.3 0-3.8 1.4-3.8 4v2.3H8v3.1h2.4V21h3Z" fill="currentColor" />
</svg>
SVG,
            'instagram' => <<<'SVG'
<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
  <rect x="3.5" y="3.5" width="17" height="17" rx="4.2" fill="none" stroke="currentColor" stroke-width="1.8" />
  <circle cx="12" cy="12" r="3.8" fill="none" stroke="currentColor" stroke-width="1.8" />
  <circle cx="17.4" cy="6.7" r="1.1" fill="currentColor" />
</svg>
SVG,
            'x' => <<<'SVG'
<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
  <path d="M4.4 4h4.2l4.3 5.8L18 4h1.8l-6 6.8L20 20h-4.2l-4.7-6.3L5.6 20H3.8l6.3-7.1L4.4 4Zm3 1.6 8.8 12.8h1.4L8.8 5.6H7.4Z" fill="currentColor" />
</svg>
SVG,
            'apes-social' => <<<'SVG'
<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
  <circle cx="12" cy="12" r="3.2" fill="currentColor" />
  <circle cx="5.5" cy="8" r="1.7" fill="currentColor" opacity="0.85" />
  <circle cx="18.2" cy="7.4" r="1.7" fill="currentColor" opacity="0.85" />
  <circle cx="17.5" cy="17" r="1.7" fill="currentColor" opacity="0.85" />
  <path d="M7 8.8 10.1 10.7M14.4 10.4 16.8 8.5M14.1 14.8 16.1 15.9M8.8 13.8 6.8 10.1" fill="none" stroke="currentColor" stroke-width="1.45" stroke-linecap="round" stroke-linejoin="round" />
</svg>
SVG,
            'discord' => <<<'SVG'
<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
  <path d="M7.6 6.2c1.3-.9 2.7-1.3 4.4-1.4.2 0 .4.1.6.2.2.1.4.2.6.2 1.7.1 3.1.5 4.4 1.4 1.1 1.8 1.7 3.9 1.8 6.2-.8 1.6-1.8 2.8-3 3.8l-.4-.7c.8-.4 1.4-.9 1.9-1.5-.5.2-1 .4-1.6.6-.5.2-1 .3-1.5.4-.9.2-1.9.2-2.8.2s-1.9-.1-2.8-.2c-.5-.1-1-.2-1.5-.4-.6-.2-1.1-.4-1.6-.6.5.6 1.1 1.1 1.9 1.5l-.4.7c-1.2-1-2.2-2.2-3-3.8.1-2.3.7-4.4 1.8-6.2Zm2 4.2c-.6 0-1 .5-1 1.1s.5 1.1 1 1.1 1-.5 1-1.1-.4-1.1-1-1.1Zm4.8 0c-.6 0-1 .5-1 1.1s.5 1.1 1 1.1 1-.5 1-1.1-.4-1.1-1-1.1Z" fill="currentColor" />
</svg>
SVG,
            'youtube' => <<<'SVG'
<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
  <path d="M21.4 7.4c.3 1 .6 2.4.6 4.6s-.2 3.6-.6 4.6c-.2.6-.7 1-1.3 1.2-.9.3-4.5.5-8.1.5s-7.2-.2-8.1-.5c-.6-.2-1.1-.6-1.3-1.2-.3-1-.6-2.4-.6-4.6s.2-3.6.6-4.6c.2-.6.7-1 1.3-1.2.9-.3 4.5-.5 8.1-.5s7.2.2 8.1.5c.6.2 1.1.6 1.3 1.2ZM14.7 12l-4.8-2.8v5.6Z" fill="currentColor" />
</svg>
SVG,
            'threads' => <<<'SVG'
<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
  <path d="M14.9 10.2c-.2-1.8-1.3-2.7-3.3-2.7-2.2 0-3.7 1.3-4 3.4l2 .3c.2-1.1.8-1.8 2-1.8 1 0 1.5.4 1.6 1.4-3.6.3-5.4 1.6-5.4 4 0 1.8 1.4 3 3.5 3 1.3 0 2.4-.5 3.1-1.5.5.9 1.4 1.4 2.6 1.4 2.2 0 3.8-1.8 3.8-4.5 0-3.7-2.5-6.3-6.4-6.3-4.4 0-7.2 3-7.2 7.4 0 4.6 3 7.6 7.5 7.6 2 0 3.6-.5 5.1-1.5l-.9-1.6c-1.2.8-2.6 1.2-4.1 1.2-3.4 0-5.6-2.2-5.6-5.7 0-3.4 2.1-5.7 5.3-5.7 2.7 0 4.4 1.7 4.4 4.3 0 1.5-.7 2.5-1.8 2.5-.8 0-1.2-.5-1.2-1.4v-2.4Zm-1.9 2.7c0 1.7-.8 2.8-2.2 2.8-.9 0-1.5-.5-1.5-1.3 0-1.1.9-1.8 3.7-2.1v.6Z" fill="currentColor" />
</svg>
SVG,
            'bluesky' => <<<'SVG'
<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
  <path d="M12 10.5c1.4-2.2 5.3-6 7.2-7.1 1.3-.8 3-.1 3 1.4 0 3.1-2 8.3-4.5 9.9 2 0 3.9 1.8 4.5 3.7.3 1-.2 2.1-1.3 2.1-2.8 0-5-2.8-6.5-5.3-1.5 2.5-3.7 5.3-6.5 5.3-1.1 0-1.6-1.1-1.3-2.1.6-1.9 2.5-3.7 4.5-3.7C3.8 13.1 1.8 7.9 1.8 4.8c0-1.5 1.7-2.2 3-1.4C6.7 4.5 10.6 8.3 12 10.5Z" fill="currentColor" />
</svg>
SVG,
            default => '',
        };
    }

    function apes_absolute_url(string $path): string
    {
        if (preg_match('/^https?:\/\//i', $path) === 1) {
            return $path;
        }

        return rtrim(APES_PRIMARY_DOMAIN, '/') . '/' . ltrim($path, '/');
    }

    function apes_organization_schema(array $site): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $site['site_name'],
            'url' => APES_PRIMARY_DOMAIN . '/',
            'logo' => apes_absolute_url((string) $site['brand']['logo_feature_png']),
            'telephone' => APES_CONTACT_PHONE,
            'identifier' => APES_CIC_NUMBER,
            'sameAs' => array_values(array_map(
                static fn (array $profile): string => (string) $profile['href'],
                array_filter(
                    $site['social_profiles'] ?? [],
                    static fn (array $profile): bool => !empty($profile['external'])
                )
            )),
        ];
    }

    function apes_website_schema(array $page, array $site): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $site['site_short_name'],
            'url' => APES_PRIMARY_DOMAIN . '/',
            'inLanguage' => 'en-GB',
        ];

        if (($page['route'] ?? '') === '/search/') {
            $schema['potentialAction'] = [
                '@type' => 'SearchAction',
                'target' => APES_PRIMARY_DOMAIN . '/search/?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ];
        }

        return $schema;
    }

    function apes_breadcrumb_schema(array $breadcrumbs): ?array
    {
        if ($breadcrumbs === []) {
            return null;
        }

        $items = [];

        foreach ($breadcrumbs as $index => $crumb) {
            $item = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => (string) ($crumb['label'] ?? ''),
            ];

            if (!empty($crumb['href'])) {
                $item['item'] = apes_absolute_url((string) $crumb['href']);
            }

            $items[] = $item;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
}

[$site, $page] = apes_current_page();
$canonical_url = apes_page_url($page);
$robots_directive = apes_page_robots($page);
$is_search_page = ($page_key ?? '') === 'search';
[$search_query, $search_results] = $is_search_page ? apes_search_results($site['pages']) : ['', []];
$active_nav_group = apes_primary_nav_group((string) ($page_key ?? ''));
$absolute_og_image = rtrim(APES_PRIMARY_DOMAIN, '/') . $site['brand']['og_image'];
$absolute_twitter_image = rtrim(APES_PRIMARY_DOMAIN, '/') . $site['brand']['twitter_image'];
$breadcrumbs = apes_breadcrumbs_for_page($page, isset($page_key) ? (string) $page_key : null);
$schema_graph = [
    apes_organization_schema($site),
    apes_website_schema($page, $site),
];
$breadcrumb_schema = apes_breadcrumb_schema($breadcrumbs);

if ($breadcrumb_schema !== null) {
    $schema_graph[] = $breadcrumb_schema;
}
?><!DOCTYPE html>
<html lang="en-GB">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($page['meta_title'], ENT_QUOTES) ?></title>
  <meta name="description" content="<?= htmlspecialchars($page['description'], ENT_QUOTES) ?>" />
  <meta name="robots" content="<?= htmlspecialchars($robots_directive, ENT_QUOTES) ?>" />
  <link rel="canonical" href="<?= htmlspecialchars($canonical_url, ENT_QUOTES) ?>" />
  <meta property="og:title" content="<?= htmlspecialchars($page['meta_title'], ENT_QUOTES) ?>" />
  <meta property="og:description" content="<?= htmlspecialchars($page['description'], ENT_QUOTES) ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?= htmlspecialchars($canonical_url, ENT_QUOTES) ?>" />
  <meta property="og:image" content="<?= htmlspecialchars($absolute_og_image, ENT_QUOTES) ?>" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= htmlspecialchars($page['meta_title'], ENT_QUOTES) ?>" />
  <meta name="twitter:description" content="<?= htmlspecialchars($page['description'], ENT_QUOTES) ?>" />
  <meta name="twitter:image" content="<?= htmlspecialchars($absolute_twitter_image, ENT_QUOTES) ?>" />
  <meta name="theme-color" content="#008C8C" />
  <link rel="me" href="https://social.apes.org.uk/@apes" />
  <link rel="me" href="https://exoticsatlarge.apes.org.uk/@apesorguk" />
  <link rel="me" href="https://mastodon.social/@apescic" />
  <link rel="icon" href="/assets/favicons/favicon.ico" sizes="any" />
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicons/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicons/favicon-16x16.png" />
  <link rel="apple-touch-icon" href="/assets/favicons/apple-touch-icon.png" />
  <link rel="manifest" href="/site.webmanifest" />
  <link rel="stylesheet" href="/theme/site.css" />
  <script type="application/ld+json"><?= json_encode($schema_graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?></script>
  <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" defer></script>
  <script>
    window.OneSignal = window.OneSignal || [];
    OneSignal.push(function () {
      OneSignal.init({
        appId: "9455eb99-6098-4d96-84fb-451af5a1a029"
      });
    });
  </script>
  <script type="module" src="https://donorbox.org/widgets.js" async></script>
</head>
<body data-page-key="<?= htmlspecialchars((string) ($page_key ?? 'unknown'), ENT_QUOTES) ?>">
  <a class="skip-link" href="#main-content">Skip to main content</a>
  <dbox-widget interval="1 M" campaign="donations-909664" type="popup" button-label="Donate Now!" button-type="sticky" button-color="#008000" button-size="medium" sticky-position="left" show-icon=""></dbox-widget>
  <?php require __DIR__ . '/header.php'; ?>

  <main id="main-content" class="site-main">
    <?php if (!empty($breadcrumbs)): ?>
      <nav class="breadcrumb-shell" aria-label="Breadcrumb">
        <ol class="breadcrumb-list">
          <?php foreach ($breadcrumbs as $crumb): ?>
            <li class="breadcrumb-item"><?= apes_render_breadcrumb_item($crumb) ?></li>
          <?php endforeach; ?>
        </ol>
      </nav>
    <?php endif; ?>

    <section class="hero-shell<?= !empty($page['hero_media']) ? ' hero-shell--media' : '' ?>">
      <div class="hero-panel<?= !empty($page['hero_media']) ? ' hero-panel--media' : '' ?>">
        <p class="eyebrow"><?= htmlspecialchars($page['hero_kicker'], ENT_QUOTES) ?></p>
        <h1><?= htmlspecialchars($page['hero_title'], ENT_QUOTES) ?></h1>
        <p class="hero-summary"><?= htmlspecialchars($page['hero_summary'], ENT_QUOTES) ?></p>
        <?php if (!empty($page['pills'])): ?>
          <div class="pill-row">
            <?php foreach ($page['pills'] as $pill): ?>
              <span class="pill"><?= htmlspecialchars($pill, ENT_QUOTES) ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <?php if (!empty($page['hero_actions'])): ?>
          <div class="action-row">
            <?php foreach ($page['hero_actions'] as $action): ?>
              <?php $class = ($action['variant'] ?? 'secondary') === 'primary' ? 'button button-primary' : 'button button-secondary'; ?>
              <?= apes_render_link($action, $class) ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <?php if (!empty($page['hero_media'])): ?>
        <?php $heroMedia = apes_image_by_key($site, (string) $page['hero_media']); ?>
        <?php if ($heroMedia !== null): ?>
          <div class="hero-media-panel">
            <?= apes_render_picture($heroMedia, 'feature-media__image hero-media__image', false, true) ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </section>

    <section class="page-shell">
      <article class="page-body">
        <?= apes_render_page_body($page['body_html'], $site, $page) ?>

        <?php if ($is_search_page): ?>
          <section class="section-shell">
            <div class="section-heading">
              <p class="eyebrow">Results</p>
              <h2><?= $search_query === '' ? 'Search the site to see results.' : 'Search results for "' . htmlspecialchars($search_query, ENT_QUOTES) . '"' ?></h2>
            </div>
            <?php if ($search_query !== '' && $search_results === []): ?>
              <p>No pages matched that search. Try broader terms such as donate, policy, contact or volunteer.</p>
            <?php elseif ($search_query !== ''): ?>
              <div class="card-grid card-grid-two">
                <?php foreach ($search_results as $result): ?>
                  <article class="info-card">
                    <h3><?= htmlspecialchars($result['title'], ENT_QUOTES) ?></h3>
                    <p><?= htmlspecialchars($result['description'], ENT_QUOTES) ?></p>
                    <a class="text-link" href="<?= htmlspecialchars($result['route'], ENT_QUOTES) ?>">Open page</a>
                  </article>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </section>
        <?php endif; ?>
      </article>

      <aside class="page-sidebar">
        <div class="page-sidebar__shared">
          <?= apes_render_shared_sidebar_cards($site) ?>
        </div>
        <?php if (!empty($page['related_links'])): ?>
          <div class="mini-panel">
            <p class="eyebrow">Related links</p>
            <ul class="clean-list">
              <?php foreach ($page['related_links'] as $link): ?>
                <li><?= apes_render_link($link) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div class="mini-panel">
            <p class="eyebrow">Need help?</p>
            <p>Use the APES contact centre or Help Centre if you are unsure which route fits your situation best.</p>
            <div class="action-stack">
                <a class="button button-secondary" href="https://contact.apes.org.uk/" target="_blank" rel="noopener noreferrer">Contact centre</a>
                <a class="button button-secondary" href="https://help.apes.org.uk/" target="_blank" rel="noopener noreferrer">Help Centre</a>
            </div>
          </div>
        <?php endif; ?>
      </aside>
    </section>
  </main>

  <?php require __DIR__ . '/footer.php'; ?>
</body>
</html>
