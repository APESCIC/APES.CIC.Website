<footer class="site-footer">
  <div class="footer-shell">
    <div class="footer-grid">
      <?php foreach ($site['footer_columns'] as $column): ?>
        <section class="footer-card">
          <h2><?= htmlspecialchars($column['title'], ENT_QUOTES) ?></h2>
          <ul class="clean-list">
            <?php foreach ($column['items'] as $item): ?>
              <?php if (($item['type'] ?? 'link') === 'subheading'): ?>
                <li class="footer-item footer-item-subheading"><span class="footer-subheading"><?= htmlspecialchars($item['label'], ENT_QUOTES) ?></span></li>
              <?php else: ?>
                <?php $linkClass = 'text-link footer-link-tile'; ?>
                <li class="footer-item"><?= apes_render_link($item, $linkClass) ?></li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </section>
      <?php endforeach; ?>
    </div>
    <div class="footer-partners">
      <span class="footer-partners-label">Working in partnership with:</span>
      <div class="footer-partner-list">
        <?php foreach ($site['footer_partners'] as $partner): ?>
          <a class="partner-chip" href="<?= htmlspecialchars($partner['href'], ENT_QUOTES) ?>" target="_blank" rel="noopener noreferrer">
            <img src="<?= htmlspecialchars($partner['logo'], ENT_QUOTES) ?>" alt="<?= htmlspecialchars($partner['logo_alt'], ENT_QUOTES) ?>" />
            <span><?= htmlspecialchars($partner['label'], ENT_QUOTES) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
      <?php $footerSocialLinks = apes_social_links_for_placement($site['social_profiles'] ?? [], 'footer'); ?>
      <?php if (!empty($footerSocialLinks)): ?>
        <div class="footer-social">
          <span class="footer-partners-label">Follow APES:</span>
          <div class="footer-social-list">
            <?php foreach ($footerSocialLinks as $socialLink): ?>
              <?= apes_render_social_icon_link($socialLink, 'social-icon-link footer-social-link') ?>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
    <div class="footer-bar">
      <div class="footer-bar__identity">
        <p>Part of <?= htmlspecialchars(APES_SITE_NAME, ENT_QUOTES) ?>.</p>
        <p>&copy; <?= htmlspecialchars((string) $site['year'], ENT_QUOTES) ?> <?= htmlspecialchars(APES_SITE_NAME, ENT_QUOTES) ?> &middot; CIC No: <?= htmlspecialchars(APES_CIC_NUMBER, ENT_QUOTES) ?></p>
        <p>Registered office: <?= htmlspecialchars($site['registered_address'], ENT_QUOTES) ?></p>
        <p>Public contact: <?= apes_render_contact_email_link('footer-inline-link') ?> &middot; <?= apes_render_contact_phone_link('footer-inline-link') ?></p>
      </div>
      <p class="footer-bar__links">
        <?php foreach ($site['footer_required_links'] as $index => $link): ?>
          <?= apes_render_link($link, 'footer-inline-link') ?><?= $index < count($site['footer_required_links']) - 1 ? '<span aria-hidden="true"> &middot; </span>' : '' ?>
        <?php endforeach; ?>
      </p>
      <p class="footer-bar__version">Website version: <?= apes_render_link(['label' => 'APES CIC ' . $site['version'], 'href' => '/change-log-hub/'], 'footer-inline-link') ?> &middot; Change Log Hub</p>
    </div>
  </div>
</footer>

<?php $developmentNotice = $site['development_notice'] ?? null; ?>
<?php if (!empty($developmentNotice['popup_enabled'])): ?>
  <div class="development-popup" data-development-popup data-storage-key="<?= htmlspecialchars((string) $developmentNotice['storage_key'], ENT_QUOTES) ?>" hidden>
    <div class="development-popup__backdrop" data-development-popup-close tabindex="-1"></div>
    <div
      class="development-popup__dialog"
      role="dialog"
      aria-modal="true"
      aria-labelledby="development-popup-title"
      aria-describedby="development-popup-message"
      tabindex="-1"
      data-development-popup-dialog
    >
      <button class="development-popup__close" type="button" aria-label="Close development notice" data-development-popup-close>&times;</button>
      <p class="development-popup__eyebrow">Website update</p>
      <h2 id="development-popup-title">Some parts of this website are still being built.</h2>
      <p id="development-popup-message"><?= htmlspecialchars((string) $developmentNotice['popup_message'], ENT_QUOTES) ?></p>
      <div class="development-popup__actions">
        <a
          class="button button-primary"
          href="<?= htmlspecialchars((string) $developmentNotice['fallback_href'], ENT_QUOTES) ?>"
          data-live-chat-open
        ><?= htmlspecialchars((string) $developmentNotice['live_chat_label'], ENT_QUOTES) ?></a>
        <button class="button button-secondary" type="button" data-development-popup-close>Continue to website</button>
      </div>
    </div>
  </div>
<?php endif; ?>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v15.0&appId=670420541399530&autoLogAppEvents=1"></script>
<script>
  window.chatwootSettings = {"position":"right","type":"expanded_bubble","launcherTitle":"Talk to an advisor"};
  (function(d, t) {
    var BASE_URL = "https://chatwoot.workspace.apes.org.uk";
    var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
    g.src = BASE_URL + "/packs/js/sdk.js";
    g.async = true;
    s.parentNode.insertBefore(g, s);
    g.onload = function() {
      window.chatwootSDK.run({
        websiteToken: "J6xS2UVkJZf8EebizBvGYBxV",
        baseUrl: BASE_URL
      });
    };
  })(document, "script");
</script>
<script type="module" src="/theme/js/site.js" defer></script>
