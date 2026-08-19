<header class="site-header">
  <div class="topbar">
    <div class="topbar-inner">
      <div class="topbar-contact" aria-label="APES contact details">
        <p><?= apes_render_contact_email_link() ?></p>
        <p><?= apes_render_contact_phone_link() ?></p>
      </div>
      <?php $headerSocialLinks = apes_social_links_for_placement($site['social_profiles'] ?? [], 'header'); ?>
      <?php if (!empty($headerSocialLinks)): ?>
        <div class="topbar-social" aria-label="APES social media links">
          <?php foreach ($headerSocialLinks as $socialLink): ?>
            <?= apes_render_social_icon_link($socialLink) ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?php $developmentNotice = $site['development_notice'] ?? null; ?>
  <?php if (!empty($developmentNotice['header_enabled'])): ?>
    <div class="development-notice" aria-label="Website development notice">
      <div class="development-notice__inner">
        <p class="development-notice__message"><?= htmlspecialchars((string) $developmentNotice['header_message'], ENT_QUOTES) ?></p>
        <a
          class="button button-secondary development-notice__action"
          href="<?= htmlspecialchars((string) $developmentNotice['fallback_href'], ENT_QUOTES) ?>"
          data-live-chat-open
        ><?= htmlspecialchars((string) $developmentNotice['live_chat_label'], ENT_QUOTES) ?></a>
      </div>
    </div>
  <?php endif; ?>
  <div class="nav-shell">
    <div class="brand-block">
      <a class="brand-mark" href="/">
        <picture class="brand-logo-wrap">
          <source srcset="<?= htmlspecialchars($site['brand']['logo_nav_webp'], ENT_QUOTES) ?>" type="image/webp" />
          <img class="brand-logo" src="<?= htmlspecialchars($site['brand']['logo_nav_png'], ENT_QUOTES) ?>" alt="Association of Protecting Exotic Species CIC logo" width="92" height="72" />
        </picture>
        <span class="brand-copy">
          <strong><?= htmlspecialchars($site['site_short_name'], ENT_QUOTES) ?></strong>
          <span><?= htmlspecialchars($site['brand']['subtitle'], ENT_QUOTES) ?></span>
        </span>
      </a>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-nav" aria-label="Open menu">Menu</button>
    </div>
    <button class="nav-overlay" type="button" aria-label="Close menu" hidden data-nav-overlay></button>
    <nav id="primary-nav" class="primary-nav" aria-label="Primary" aria-labelledby="primary-nav-title">
      <div class="primary-nav__panel">
        <div class="primary-nav__mobile-bar">
          <p id="primary-nav-title" class="primary-nav__mobile-title">Site navigation</p>
          <button class="menu-close" type="button" aria-label="Close menu" data-menu-close>Close</button>
        </div>
        <ul>
          <?php foreach ($site['nav'] as $item): ?>
            <?php
              $groupKey = match ($item['label']) {
                  'Home' => 'home',
                  'Services' => 'services',
                  'Support APES' => 'support',
                  'Information' => 'information',
                  'Contact' => 'contact',
                  default => '',
              };
              $isActive = $groupKey !== '' && $active_nav_group === $groupKey;
            ?>
            <li class="<?= isset($item['children']) ? 'has-children' : '' ?><?= !empty($item['cta']) ? ' nav-cta-item' : '' ?>">
              <?php if (isset($item['children'])): ?>
                <?php
                  $childCount = count($item['children']);
                  $megaColumns = $childCount >= 9 ? 3 : ($childCount >= 5 ? 2 : 1);
                  $panelTheme = $item['panel_theme'] ?? 'default';
                ?>
                <details class="mega-menu<?= $isActive ? ' is-active' : '' ?>">
                  <summary class="mega-summary"><?= htmlspecialchars($item['label'], ENT_QUOTES) ?></summary>
                  <div class="mega-panel mega-panel--<?= htmlspecialchars($panelTheme, ENT_QUOTES) ?>">
                    <div class="mega-panel-header">
                      <?php if (!empty($item['panel_eyebrow'])): ?>
                        <p class="mega-eyebrow"><?= htmlspecialchars($item['panel_eyebrow'], ENT_QUOTES) ?></p>
                      <?php endif; ?>
                      <p class="mega-heading"><?= htmlspecialchars($item['panel_heading'] ?? $item['label'], ENT_QUOTES) ?></p>
                      <?php if (!empty($item['panel_description'])): ?>
                        <p class="mega-description"><?= htmlspecialchars($item['panel_description'], ENT_QUOTES) ?></p>
                      <?php endif; ?>
                      <?php if (!empty($item['panel_pills'])): ?>
                        <ul class="mega-panel-pills" aria-label="<?= htmlspecialchars(($item['label'] ?? 'Menu') . ' quick topics', ENT_QUOTES) ?>">
                          <?php foreach ($item['panel_pills'] as $pill): ?>
                            <li><span class="mega-panel-pill"><?= htmlspecialchars($pill, ENT_QUOTES) ?></span></li>
                          <?php endforeach; ?>
                        </ul>
                      <?php endif; ?>
                    </div>
                    <ul class="mega-links" style="--mega-columns: <?= $megaColumns ?>;">
                      <?php foreach ($item['children'] as $child): ?>
                        <li>
                          <a class="mega-link" href="<?= htmlspecialchars($child['href'], ENT_QUOTES) ?>"<?php if (!empty($child['external'])): ?> target="_blank" rel="noopener noreferrer"<?php endif; ?>>
                            <?php if (!empty($child['badge'])): ?>
                              <span class="mega-link-badge" aria-hidden="true"><?= htmlspecialchars($child['badge'], ENT_QUOTES) ?></span>
                            <?php endif; ?>
                            <span class="mega-link-copy">
                              <span class="mega-link-title"><?= htmlspecialchars($child['label'], ENT_QUOTES) ?></span>
                              <span class="mega-link-description"><?= htmlspecialchars($child['description'] ?? '', ENT_QUOTES) ?></span>
                            </span>
                            <span class="mega-link-arrow" aria-hidden="true">&rarr;</span>
                          </a>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </details>
              <?php else: ?>
                <a class="<?= !empty($item['cta']) ? 'button button-primary nav-cta-link' : 'nav-link' ?>" href="<?= htmlspecialchars($item['href'], ENT_QUOTES) ?>"<?= $isActive ? ' aria-current="page"' : '' ?>><?= htmlspecialchars($item['label'], ENT_QUOTES) ?></a>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </nav>
  </div>
</header>
