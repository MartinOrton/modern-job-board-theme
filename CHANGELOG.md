# Changelog

Canonical version: `Version` in `style.css` and `MJB_THEME_VERSION` in `functions.php`.

## [0.9.0-beta.98] - 2026-11-24

### Changed
- **Footer background:** the hero's aurora gradient, flipped vertically. It rises from the bottom, with the accent orange at the bottom right, fades into white at the top, and has the same slow drift and film grain. The drift stops for visitors who prefer reduced motion.

## [0.9.0-beta.97] - 2026-11-24

### Changed
- Pricing cards: style for a plan's first row ("All features in Free", "All features in Pro"…), in bold with a divider under it, so each paid plan lists only what it adds.

## [0.9.0-beta.96] - 2026-11-24

### Changed
- Email notifications: UTM tags on every link to the site, and the Powered by switch for Pro and Business (plugin 0.9.0-beta.196).

## [0.9.0-beta.95] - 2026-11-24

### Changed
- Email notifications: the {site_link} footer tag, Powered by Modern Job Board, and UTM tags on footer links (plugin 0.9.0-beta.195).

## [0.9.0-beta.94] - 2026-11-24

### Changed
- Email notifications and Admin console: the Email Delivery check in Tools › Health, and the site name as the default sender name (plugin 0.9.0-beta.194).

## [0.9.0-beta.93] - 2026-11-24

### Changed
- Posting and managing jobs: recruiters can delete and restore their jobs from Deleted Jobs (plugin 0.9.0-beta.193).

## [0.9.0-beta.92] - 2026-11-24

### Changed
- Admin console: how Trash, Restore and Delete Permanently work for jobs, applications and companies (plugin 0.9.0-beta.192).

## [0.9.0-beta.91] - 2026-11-24

### Changed
- Companies and careers mode: describes the new company editor (plugin 0.9.0-beta.191).
- Jobs map and promotions: how to choose featured companies (Feature this company, Featured until).

## [0.9.0-beta.90] - 2026-11-24

### Changed
- Settings reference: **Board staff** (was Collaborators): what staff can and can't do, and the Remove button (plugin 0.9.0-beta.190).

## [0.9.0-beta.89] - 2026-11-24

### Changed
- Admin console docs: the jobs console's Recruiter / Posted by column and filter, the Columns menu, and the poster in the CSV export (plugin 0.9.0-beta.189).

## [0.9.0-beta.88] - 2026-11-24

### Changed
- Docs follow plugin 0.9.0-beta.187, which moved the hidden admin pages into the Modern Job Board menus:
  - **Settings reference:** new Branding, Text overrides and Collaborators sections; Blocked submissions in Application Security; the Payments overview; API Keys and Embed widget under Integrations.
  - **Spam protection:** the Blocked submissions list.
  - **The admin console:** Tools now covers partner feeds, the analytics export and the Health tab; Board Health checks background tasks; Shortcodes Setup lists the optional shortcodes.
  - **Import and export:** Tools → Schedules (with partner feeds) and Export Analytics to CSV.
  - **REST API authentication:** a section on API keys, including what a key does and doesn't do.
  - **Shortcodes and blocks:** `[mjb_related_jobs]` described correctly (a list of open jobs, optionally from one category), plus a "Jobs on your blog" section with the advice from the retired Blog + jobs page.
  - Feature pages: Board Health also checks background tasks.

## [0.9.0-beta.87] - 2026-11-23

### Changed
- Ad banner docs point to the new place in the plugin (0.9.0-beta.186): **Settings → Plans & payments → Ad Banners**. Updated in the Jobs map & promotions article (with how positions and `[mjb_ad_banner position="0"]` / `label="Name"` work), the Shortcodes & blocks table, and the feature pages.

## [0.9.0-beta.86] - 2026-11-23

### Changed
- Screenshots are no longer links. The seeded feature pages stop linking the desktop screenshot to its file, and a render filter (`mjb_theme_feature_shot_render()`) drops any link that only opens a screenshot file, so pages saved earlier lose it too. Links to anything else are kept.
- Screenshot addresses carry the file's modification time (`?ver=`), so a replaced screenshot shows at once instead of a cached copy.
- Device screenshots fade in when they come into view: the browser window first, then the phone, a 12px rise over about 0.7 seconds. The page is marked in the head (`mjb_theme_intro_flag()`) so nothing flashes; nothing moves for visitors who prefer less motion, and without JavaScript the screenshots simply show.
- New admin screenshots (Admin Dashboard, Shortcodes Setup, Custom Fields, Resume Privacy, Google Jobs; desktop and phone) and a new phone screenshot of recruiter applications, taken with Modern Job Board 0.9.0-beta.179: the admin bar avatar shows instead of a broken image, the phone tables are cards with icon buttons, and the filter pills wrap.

## [0.9.0-beta.85] - 2026-11-22

### Changed
- "Recruiter MCP" is renamed "AI assistant for recruiters" in the seed copy, matching the feature page title and menu label ("AI Assistant for Recruiters (Business)"): the feature page headings and REST API card, the plans table, the docs article title ("AI assistant for recruiters (MCP)") and its excerpt, the docs home and Integrations topic descriptions, the Recruiter accounts, Introduction, Plans, REST API overview and Authentication docs, About Us and the License & Refund Policy. "MCP" stays where developers need it.
- ChatGPT and Claude can now connect, with the one-click sign-in in Modern Job Board 0.9.0-beta.178. The copy now names ChatGPT alongside Claude and Gemini, and:
  - the feature page's "Which AI assistants can I use?" section, connection steps and "Do recruiters need to be technical?" FAQ describe the one-click sign-in, and a new FAQ explains how to disconnect an assistant
  - the docs article has a "Connect with one-click sign-in" walkthrough (dashboard AI assistant tab, custom connector, sign in, Allow) and the technical details of the OAuth sign-in, and keeps the application-password route for Claude Code and Gemini CLI under "Connect with an application password"
  - the hero text names ChatGPT, Claude or Gemini.

## [0.9.0-beta.84] - 2026-11-22

### Changed
- Recruiter MCP is now explained in plain language wherever it appears, for recruiters as well as developers: recruiters manage their jobs and applications by chatting with an AI assistant such as Claude or Gemini, with example requests, and MCP (Model Context Protocol) is explained as the open standard AI assistants use to work with other systems. Seed copy updated in:
  - the feature page's hero, answer box and FAQ, plus a new "Which AI assistants can I use?" section and a "Do recruiters need to be technical?" FAQ
  - the REST API feature card and the plans table
  - the docs (Recruiter MCP, Recruiter accounts, Introduction, Plans, REST API overview and Authentication)
  - About Us, the License & Refund Policy and the docs index
- The compatibility notes are accurate as of November 2026. Claude Code, Claude Desktop (through a bridge) and Gemini CLI can connect, because they can send the application-password header. ChatGPT and the Claude website cannot connect yet, because they accept only OAuth or no sign-in. The Recruiter MCP guide shows how to build the header and set up Claude Code and Gemini CLI, and says to use plugin 0.9.0-beta.177 or later.
- Code highlighting recognises `claude`, `gemini`, `npx`, `printf` and `base64` commands as shell.

## [0.9.0-beta.83] - 2026-11-22

### Changed
- The code samples on the SEO Ready, REST API, Feeds & Webhooks and Recruiter MCP feature pages now use the same code window as the docs: the slate panel and three-dot bar, the home page's colour coding, and the copy icon with its tooltip. They replace the dark-teal panel and keep their 30rem scroll height. Without JavaScript they show the same slate colours.
- The code window lives in a shared script, `js/code.js`, moved out of `js/kb.js`. It loads only on pages with a Code block. Its strings are now localized as `mjbCode`.
- HTTP samples (a request line, then a JSON body) now colour the body as JSON too.

## [0.9.0-beta.82] - 2026-11-22

### Changed
- Documentation code blocks now look like the home page's code window:
  - The same slate panel and three-dot title bar.
  - The same colour coding: comments grey, keys cyan, strings green, numbers and keywords pink, functions purple.
  - `js/kb.js` works out each block's language from its content: JSON, PHP, JavaScript, shell/curl, HTTP requests and headers, or nginx. Plain URL lists stay plain.
  - All 35 code blocks in the docs are covered. Copying still gives the original text.
- The "Copy" text button is now a copy icon in the title bar. On hover or keyboard focus it shows the plugin's teal pill tooltip ("Copy", styled like `.mjb-icon-btn__tooltip`). After copying, the icon becomes a tick, the tooltip says "Copied" for 1.8 seconds, and screen readers hear "Copied". As in the plugin, the hover tooltip is hidden on touch screens.
- Added a `.screen-reader-text` utility class.

## [0.9.0-beta.81] - 2026-11-22

### Fixed
- FAQ answers could stay part-closed when the page wasn't drawing frames, such as a background tab, because the close waited for the animation's finish event. A timer now completes the close after the slide's duration (480ms) if that event hasn't fired.

## [0.9.0-beta.80] - 2026-11-22

### Changed
- FAQ sections built from Details blocks (`.mjb-fb-faq`, used on all 14 feature pages, the Features page and About Us) now behave like the home page FAQ:
  - Opening an answer closes the one that was open, so only one is open at a time. This also holds when an answer is opened some other way, such as find-in-page.
  - Answers slide open and closed with the home page timing (0.4s, `cubic-bezier(0.16, 1, 0.3, 1)`), and the answer fades in.
  - While an answer closes, the + icon and the border change straight away (class `is-closing`).
  - With reduced motion, answers open and close instantly, still one at a time.
  - Without JavaScript, the Details blocks still open and close as before.

## [0.9.0-beta.79] - 2026-11-21

### Changed
- Content now fills the full width of its block, in line with the content column, instead of stopping short with a gap on the right. Removed caps:
  - page intros under titles (`.mjb-content-hero__intro`, 42rem)
  - the home hero text (`.hero-text` 32rem, and 34rem in `home.css`)
  - the feature hero lead (35rem)
  - feature, About and FAQ headings and paragraphs (46rem)
  - answer-box paragraphs (52rem)
  - legal page bodies (50rem)
  - the KB search box (44rem)
  - KB article bodies, lists, help box and pager (46rem)
  - the CTA band text (34rem; it still has its own column beside the buttons)
  - the footer brand blurb (22rem; unchanged on desktop, where its grid column sets the width)
- Kept, because they are centred and leave no right-hand gap: home section headers, the home FAQ list, the comparison table and the device screenshots.
- A scan of all 37 pages at 1600, 1280, 1024, 768 and 390px widths finds no remaining left-aligned element that is capped narrower than its block.

## [0.9.0-beta.78] - 2026-11-21

### Added
- About Us page (`inc/pages/about-us.html`), created once by the site set-up (option `mjb_theme_page_about`). It uses the same blocks as the feature pages: a hero with the company illustration, an answer box on who is behind Modern Job Board, icon cards on the problems it solves and what we believe in, the Complete Site build steps, company details, an FAQ and the shared call-to-action band. Every product claim is taken from the feature pages and the pricing table. The company facts come from 4mation.digital. Page set-up content can use `{{cta}}` for the call-to-action band.

### Changed
- Footer, Company column: About Us replaces the "Author" link (martinorton.com), in the same position. This is a one-off step (`mjb_theme_site_footer_about()`, option `mjb_theme_footer_about_v1`). If there is no Author link, About Us is added after Contact. On a new site, the footer menu builder adds About Us directly.
- Feature-style pages: a second paragraph, or a short intro paragraph before a card list, step list or table, now gets the same 20px gap as a section heading above its list. No existing feature page uses either pattern, so none of them change.

## [0.9.0-beta.77] - 2026-11-21

### Changed
- Contact form: the Topic dropdown is now the plugin's dropdown instead of the browser's native list. It has a field-style trigger with a chevron that rotates, a floating white menu with a soft shadow, and teal-tinted options on hover and for the selected item. It is a port of the plugin's `enhanceSelect`, with the same keyboard handling: arrows, Home and End, Enter or Space, Escape, Tab, and typing a letter to jump to an option. It uses `aria-activedescendant` and points the label at the trigger. The native `<select>` stays in the form, so posting without JavaScript still works, and it gets the same box and chevron.
- Contact form fields now match the plugin's forms, checked value by value against the registration and login forms in every state: labels, the red required asterisk, the "Optional" pill badge, inputs and textarea (normal, hover, focus, error), error messages, the consent checkbox, and the success and error status boxes with their icons. When the plugin is active, the fields use its tokens (`--mjb-line`, `--mjb-primary` and so on), with the same values as fallbacks.

### Fixed
- The contact form's field CSS was unscoped (`.mjb-field`), so it also restyled the plugin's candidate dashboard fields. All contact form rules are now scoped to `.mjb-contact-form`.

## [0.9.0-beta.76] - 2026-11-21

### Fixed
- Text links now use the site's standard text-link style everywhere: teal, semi-bold, a faint underline that fills in from the left on hover, and darker teal on hover, as on the feature pages. This covers the Contact page (quick links, company details, the consent checkbox), the legal pages, KB articles and the KB help box. Before, each of these had its own plain-underline style. All of them are now one rule in `css/site.css`.

## [0.9.0-beta.75] - 2026-11-21

### Changed
- Contact form: "Your name" is now two required fields, First name and Last name. "Company or website" is now two optional fields, Company and Website. The website field is named `company_url`, because `website` is the honeypot. It takes a bare domain (`example.com`), adds `https://`, and rejects other schemes and addresses without a dot, both in the browser and on the server (`mjb_theme_contact_normalise_url()`).
- Contact messages store `_mjb_contact_first_name`, `_mjb_contact_last_name` and `_mjb_contact_website`. `_mjb_contact_name` keeps the full name, so the admin list and older messages still work. The notification email and the message screen show Company and Website on separate lines.
- The Send button now matches the other call-to-action buttons: same type, 1px border and hover, plus the nudging arrow that speeds up on hover. While sending, the arrow becomes a spinner.
- Contact page: the quick links (Documentation, Live demo, Pricing) have icons in a tinted tile. They use the `mjb-i-{name}` list-item classes, and the editor's icon picker now also works on lists with the class `mjb-contact-links`.
- Contact page, Company details: the new address (Spaces Broadacres, Sandton) and a line saying Modern Job Board is developed by 4Mation Digital®, which replaces the "designed and developed by Martin Orton" line. Links in the contact cards are now underlined in the primary colour.

## [0.9.0-beta.74] - 2026-11-20

### Added
- Navigation progress on every public page (`js/nav-progress.js`, styles in `css/site.css`). When a visitor follows an internal link or sends a form that loads a new page, a thin bar runs along the top of the window (teal to orange). After 150 ms the link or button they used shows a small spinner, which replaces the arrow on call-to-action buttons. The next page completes the bar, so the wait reads as one movement.
- It leaves alone anything another script already handles: AJAX job search, dashboard tabs, the contact form, KB search suggestions. It also skips new-tab links, downloads (including CV links), same-page anchors, clicks with a modifier key, and links or forms marked `data-no-progress`.
- It works with WordPress speculative loading: a page prerendered on hover finishes the bar when it is shown. It resets when a page comes back from the back/forward cache, and gives up after 12 seconds if no new page arrives. With reduced motion the bar jumps rather than glides.

## [0.9.0-beta.73] - 2026-11-20

### Changed
- Theme font style handles renamed to `mjb-theme-fonts` and `mjb-theme-fonts-editor`. The plugin used `mjb-fonts` for its own (Google-hosted) font up to plugin 0.9.0-beta.173, so on job board pages the plugin's file won and the theme's local font was skipped.
- On job board pages, where the plugin (0.9.0-beta.174 and later) loads its own local DM Sans (`mjb-dm-sans`), the theme's font stylesheet is emptied so the font faces are declared once (`mjb_theme_share_plugin_font()`).

### Removed
- The 72 unused DM Sans TTF files in `fonts/` (4.1 MB). The theme uses the variable woff2 files in `fonts/dm-sans/`.

## [0.9.0-beta.72] - 2026-11-20

### Changed
- DM Sans is served from the theme instead of Google Fonts: `css/fonts.css` with the variable font (optical size and weight axes, normal and italic, latin and latin-ext) in `fonts/dm-sans/` (from @fontsource-variable/dm-sans 5.3.0, SIL Open Font License, `fonts/dm-sans/OFL.txt`). Front end, editor styles and editor iframe no longer request fonts.googleapis.com. The handles are now `mjb-fonts` and `mjb-fonts-editor`.
- Footer copyright: "Copyright © {year} Modern Job Board. All rights reserved. Powered by 4Mation Digital®", with 4Mation Digital® linking to https://4mation.digital/ in a new tab (change the address with the `mjb_theme_company_url` filter). Used by both footer layouts. On screens under 1024px the bottom bar keeps clear of the back-to-top button.
- One-off `mjb_theme_site_legal_drop_google_fonts()` (option `mjb_theme_legal_fonts_v1`) removes the Google Fonts item from the published Privacy Policy and Cookie Policy and moves their "Last updated" date to 20 November 2026. The page sources in `inc/pages/` match.

### Notes
- The 72 DM Sans TTF files in `fonts/` (4.1 MB) are not used anywhere; left in place.

## [0.9.0-beta.71] - 2026-11-19

### Added
- Knowledge base at `/docs/`: articles (post type `mjb_kb`, under **Knowledge Base** in wp-admin) grouped in topics (`mjb_kb_topic`, with an order and an icon per topic). URLs: `/docs/{article}/`, `/docs/topic/{topic}/`, and search at `/?s=…&post_type=mjb_kb`.
- 36 articles in 7 topics (Getting started, Running your board, Candidates and recruiters, Monetization, Hosting and security, REST API, Integrations), built from the old single-page documentation and expanded, with facts checked against the plugin code (settings names, admin tabs, hooks, WooCommerce product fields, the Free-plan limit).
- Article template (`single-mjb_kb.php`): breadcrumbs, topic, updated date and reading time; topic navigation (sticky on desktop, a "Browse all topics" toggle on phones); "On this page" with the current section highlighted (1280px and up); previous and next in reading order; a "Still need help?" box linking to the Contact page. Topic (`taxonomy-mjb_kb_topic.php`) and search results (`search-mjb_kb.php`) templates.
- Blocks: Knowledge Base Search (suggests articles as you type), Knowledge Base Topics (a card per topic) and Knowledge Base Articles (reading order, recently updated or A to Z, optionally one topic).
- Code blocks in articles get a Copy button, and shortcodes written inside code are shown, not run. Callouts: paragraphs with the class `mjb-callout mjb-callout-note`, `-tip` or `-warning`.
- One-off `mjb_theme_kb_setup()` (option `mjb_theme_kb_v1`) creates the topics and articles from `inc/kb/`, keeps the old single-page documentation as a draft page ("Documentation (old single page)"), and gives the Documentation page the knowledge base home content. Links to the old page's sections (`/docs/#webhooks`, `/docs/#v2-application-update`, …) are sent to the matching article and section.

### Changed
- `mjb_theme_get_breadcrumbs()` is filterable (`mjb_theme_breadcrumbs`).
- The Documentation page now uses the normal content page layout; only a page still containing the old `docs-shell` markup keeps the full-width layout.
- One-off set-up steps that stop half-way are tried again after 10 minutes instead of staying blocked.

## [0.9.0-beta.70] - 2026-11-19

### Added
- Legal pages, drafted from what the site and plugin actually do: Privacy Policy (POPIA and GDPR), Terms of Use, Cookie Policy, and License & Refund Policy, with 4Mation Digital (Pty) Ltd as the operator and seller. Company details we do not have yet (registration number, physical address, telephone, email) are highlighted placeholders (`<mark class="mjb-todo">`). The Privacy Policy reuses WordPress's draft page (slug `privacy-policy`) and is set as the site's privacy page (Settings → Privacy). These are drafts for legal review before launch.
- Tables in page content with the class `mjb-stack-table` stack into labelled cards on phones (`mjb_theme_site_table_labels()`).
- Legal pages use a narrower reading column and underlined links (`body.mjb-page-legal`).

## [0.9.0-beta.69] - 2026-11-19

### Added
- Contact page (`/contact/`) with a new Contact Form block (`mjb/contact-form`, theme block category): name, email, company or website (optional), topic, message and a consent tick box linked to the Privacy Policy. Recipient, topics, button text and the thank-you message are set in the block sidebar; the recipient defaults to the site admin email and is read on the server, never printed in the page.
- Works without JavaScript (post, redirect back with the result and the fields kept) and with it (`js/contact.js`: inline validation, sends with fetch, shows the result in place).
- Spam protection: hidden honeypot field, signed timestamp (forms sent within 3 seconds of loading, or more than a day later, are refused), and 5 messages per hour per visitor (keyed by a hash, not the IP address).
- Every message is saved under **Contact messages** in wp-admin (private post type `mjb_contact_msg`) with an "Email sent" column, so nothing is lost if mail fails, and a Reply by email button. Messages older than 12 months are deleted by a daily WP-Cron job, as the Privacy Policy states.
- New editor script `blocks/site-editor.js` for the site blocks.

## [0.9.0-beta.68] - 2026-11-19

### Changed
- Footer rebuilt as columns driven by the Footer menu (Appearance → Menus): each top-level item with sub-items is a column (link it to `#` for a plain heading), and a column with more than 8 links spreads over two columns on wide screens. Top-level items without sub-items show in the bottom bar. The tagline under the logo is the site tagline (Settings → General). A footer menu without sub-items keeps the old single-row footer.
- One-off `mjb_theme_site_build_footer_menu()` (option `mjb_theme_footer_menu_v2`) arranges the Footer menu into Features (every feature link from the Primary menu), Resources (Documentation, Developers, Compare, Live Demo), Company (Pricing, Contact, Author) and Legal. The existing Pricing, Documentation and Author links are moved into the columns, not recreated.
- Copyright line: "Modern Job Board, a product of 4Mation Digital (Pty) Ltd. Developed by Martin Orton."
- New `inc/site.php` (loader and one-off page set-up), `inc/footer.php`, `inc/contact.php` and `css/site.css` (footer, contact, legal and knowledge base styles, also loaded in the editor).

## [0.9.0-beta.67] - 2026-11-17

### Fixed
- Home page badge version was still missing. On mjb.local the badge is a `<div class="badge">` in a Custom HTML block, but beta.63's restore and beta.64's version filter only looked for a Paragraph block. New one-off `mjb_theme_restore_home_badge_version_any()` (option `mjb_theme_home_badge_restored_any`) puts " • v0.9.0-beta.104" back exactly where beta.62 removed it, and `mjb_theme_badge_plugin_version()` now also filters Custom HTML blocks, so the badge shows the installed plugin version.

### Changed
- Home hero badge: the pulsing dot is orange (`--accent`, the Featured pill colour) instead of teal, pulse included.

## [0.9.0-beta.66] - 2026-11-17

### Changed
- Phone frame bezel halved (15 to 7.5 units all round). The screen area is unchanged at 370 x 724, so phone screenshots stay the same size; the frame is now 391 x 851 and `.mjb-devices__mobile` uses the new proportions.
- Phone screenshots of the five admin screens retaken with plugin 0.9.0-beta.171, which fixes the admin layout running 40px past the right edge.

## [0.9.0-beta.65] - 2026-11-17

### Changed
- Feature page screenshots are now shown on devices: a desktop screenshot in a browser window with the phone screenshot on top of it, bottom right. On phones (below 640px) only the phone version is shown, since a shrunken desktop screenshot is unreadable there. The frames are SVG backgrounds (`images/frames/desktop.svg`, `images/frames/phone.svg`) and the screenshots are layered over their screen areas by CSS (`.mjb-devices`), so a screenshot is updated by replacing its WebP file only: desktop 16:10 (1920 x 1200 at 1.5x of 1280 x 800) in `images/features/desktop/`, phone 780 x 1526 (2x of 390 x 763) in `images/features/mobile/`.
- New screenshots for all 12 shots, desktop and phone, from plugin 0.9.0-beta.170 (no admin version badge). The wp-admin desktop shots are taken at 1600 x 1000 because the plugin's admin wrapper overflows by 40px below about 1500px wide.
- In the editor each device screenshot is a Group (`mjb-devices`) with two core Image blocks (`mjb-devices__desktop`, `mjb-devices__mobile`) and a caption paragraph; replace either image from the block toolbar. New pattern "Screenshot on desktop and phone".
- One-off `mjb_theme_upgrade_feature_devices()` (option `mjb_theme_feature_devices`) converts each screenshot block that is still exactly as the theme wrote it; edited ones are left and named in a one-time admin notice.

### Fixed
- Relative URLs in `styles.css` now resolve in the block editor (`baseURL` on the editor styles added by `mjb_landing_block_editor_styles()`).

## [0.9.0-beta.64] - 2026-11-17

### Changed
- Home page badge shows the installed plugin's version ("WordPress Plugin • v" + `MJB_VERSION`) instead of the number stored in the page, so it stays current with each plugin release. Done at render (`mjb_theme_badge_plugin_version()`, `render_block_core/paragraph`, paragraphs with the class `badge`); stored content is unchanged. Without the plugin, or if the version is removed from the badge in the editor, the badge is left as stored.

## [0.9.0-beta.63] - 2026-11-17

### Changed
- Home page badge has its version back ("WordPress Plugin • v0.9.0-beta.104"), reversing beta.62. One-off `mjb_theme_restore_home_badge_version()` (option `mjb_theme_home_badge_restored`) re-inserts exactly the text beta.62 removed, only on sites where beta.62 changed the page. beta.62's one-off is removed. The plugin's admin screens stay without a version (plugin 0.9.0-beta.170).

## [0.9.0-beta.62] - 2026-11-17

### Removed
- Plugin version from the badge at the top of the home page ("WordPress Plugin • v0.9.0-beta.104" now reads "WordPress Plugin"). One-off `mjb_theme_remove_home_badge_version()` (option `mjb_theme_home_badge_version`) edits the front page content on the next page load; only the version is removed, and the previous content stays in the page's revisions.

## [0.9.0-beta.61] - 2026-11-16

### Removed
- The 1px rule along the bottom of the content page hero (added in beta.57). The hero shadow from beta.60 stays.

### Changed
- The hero shadow uses only the soft part of the card shadow; its 1px edge read as a line once the rule was gone.

## [0.9.0-beta.60] - 2026-11-16

### Changed
- Content page hero: a soft shadow under its bottom edge, the same shadow as the content cards (answer box), spanning the content width rather than the full page. Cast by `.mjb-content-hero__inner::after` and clipped to the part below the hero; the hero now clips its gradient sideways only (`overflow: clip visible`). Inside `@supports (overflow: clip)`, so older browsers keep the previous look. The 1px rule along the bottom stays.

## [0.9.0-beta.59] - 2026-11-16

### Fixed
- Gutenberg Blocks page (and four other feature pages) rendered with the job board header and layout. beta.58 wrote shortcodes quoted in the copy as `[[mjb_jobs]]`; `has_shortcode()` counts that as a real shortcode, and both the theme and the plugin use it to decide that a page is a job board page. Quoted shortcodes are now stored as `[<strong>mjb_jobs</strong>]` (`mjb_theme_quote_shortcodes()`), which nothing matches; readers still see and can copy `[mjb_jobs]`. One-off `mjb_theme_fix_quoted_shortcodes()` (option `mjb_theme_quoted_shortcodes`) rewrites the affected pages on the next page load; it touches only those tokens, so edited pages are safe.
- Quoted shortcodes in card lists no longer pick up the bold card-title style.

## [0.9.0-beta.58] - 2026-11-16

### Changed
- Body content of all 14 feature pages and the Features page rewritten. Each page is now an ordered list of sections chosen for that feature (answer, real screenshot or code sample, steps, icon cards, reference table, plan, FAQ) instead of one fixed template. No heading, FAQ question or sentence is shared between pages (checked by script), and every claim was re-checked against plugin 0.9.0-beta.167. Seed copy moved from PHP to `inc/feature-content.json`; `inc/feature-content.php` is now the generator.
- Card lists use an icon per item instead of a check mark. The icon is a class on the List Item (`mjb-i-{name}`, masks in `css/list-icons.css`, 112 Lucide icons) and is chosen in the block editor sidebar under "Card icon". New "Icon cards" pattern.
- Feature examples are real screenshots of the plugin (12 WebP files in `images/features/`, core Image blocks with captions) or real request/response samples (core Code blocks) on the SEO, REST API and recruiter MCP pages. Wide screenshots scroll sideways on phones and open full size on tap.
- Reference tables on feature pages size their columns to the content; on phones each row becomes a card and three-column tables label each value with its column heading (`data-label`, added at render).

### Removed
- The "Back to all features" link, and the row it sat in, from every feature page.

### Upgrade
- One-off `mjb_theme_upgrade_feature_bodies()` (option `mjb_theme_feature_bodies_v2`, one-time admin notice) runs on the next page load, no login needed. A page body is replaced only while it is still exactly what beta.57 wrote (`inc/feature-content-v1.php` is kept for that comparison only); bodies edited by hand are left alone and named in the notice. Previous content stays in each page's revisions.

## [0.9.0-beta.57] - 2026-11-15

### Added
- Body content under the hero of all 14 feature pages, written for search and answer engines and sourced from the plugin's readme, docs and code: a direct answer to a "What is..." question, four numbered steps, a list of what is included, which plan includes it (with prices), and four FAQs. The Features page gets an overview before the grid, a plans table and an FAQ after it. Seed copy lives in `inc/feature-content.php`; it is written into each page once as core blocks by `mjb_theme_seed_feature_bodies()` (option `mjb_theme_feature_bodies`, one-time admin notice) and is edited in the block editor from then on. New feature pages with copy get it on creation.
- FAQPage JSON-LD built at render time from the FAQ blocks on the page (`mjb_theme_faq_schema()`), so it always matches the edited content.
- Meta description from the page excerpt when no SEO plugin is active (`mjb_theme_meta_description()`).
- Subtle 1px rule along the bottom of the content page hero.

## [0.9.0-beta.56] - 2026-11-15

### Changed
- Feature page hero: the space above and below the gradient H2 is 40px on desktop (new `--mjb-hero-h2-gap`, 2.5rem). Breadcrumbs to title and text to buttons stay at 60px. Phones unchanged at 32px.

## [0.9.0-beta.55] - 2026-11-15

### Changed
- Content hero spacing: one value, `--mjb-hero-rhythm`, now sets every gap. It equals the visible space between the top bar and the breadcrumbs (hero edge plus the bar's bottom padding, 3.75rem), and is used for breadcrumbs to title, intro to H2, H2 to text and text to buttons. Gaps are measured to the letters (half-leading subtracted). On phones (767px and below) all five gaps, including the one under the top bar, are 2rem.

## [0.9.0-beta.54] - 2026-11-15

### Changed
- Hero gradient mirrored on the home page and on content pages: the accent orange is now top right.
- Feature page hero: the space above the H2 now equals the space between the text and the buttons (1.75rem; 1.4rem on phones). The text column starts at the top of the row instead of being vertically centred.
- Feature page hero H2 uses the same animated teal gradient text as the highlighted words in the home headline.
- Feature illustrations are as wide as the home hero card: the hero uses the same two equal columns and gap as the home hero and the illustration fills its column. The SVG viewBox is tightened so the drawing fills its box.

## [0.9.0-beta.53] - 2026-11-15

### Fixed
- The one-off block conversion (beta.51) and the feature page heroes (beta.52) only ran for a logged-in administrator, so nothing appeared when browsing the site logged out. Both now run on the next page load of any kind. Writes still bypass the HTML save filters and are verified against what was built, and the synced call-to-action pattern is saved the same way.

## [0.9.0-beta.52] - 2026-11-15

### Added
- Feature page hero: under the title and breadcrumbs, a two-column section with a heading (H2), supporting text and two buttons on the left and an animated illustration on the right. It is a core Columns block with the class `mjb-feature-hero`; when it is the first block of a page, `index.php` shows it inside the page hero (`mjb_theme_the_hero_blocks()`) and leaves it out of the body. One column on phones: words and full-width stacked buttons first, a smaller illustration after.
- `inc/feature-art.php` + Feature Illustration block (`mjb/feature-art`): 15 animated inline-SVG illustrations in the site teals and accent orange (job form, search, accounts, private documents, pricing, editor blocks, search visibility, imports, API, alerts, map, dashboard, company pages, AI assistant, job board), chosen in the block sidebar or matched to the page automatically.
- Starting copy for the hero of each of the 14 feature pages (`mjb_theme_feature_hero_copy()`), added once to pages that have no hero yet by `mjb_theme_seed_feature_heroes()` on the first logged-in admin page view; a one-time admin notice reports the count. New feature pages start with the hero.
- Animated icons on call-to-action buttons on the marketing pages: an arrow that nudges forward, or a play symbol that beats on buttons leading to the live demo. Buttons with their own inline SVG animate that icon. Hidden in the top bar below 1200px; not applied under `body.mjb-demo`.
- `mjb-btn-lg` class for larger core Button blocks.

## [0.9.0-beta.51] - 2026-11-15

### Added
- `inc/blocks.php` + `blocks/editor.js`: a Feature Grid block (`mjb/feature-grid`, server-rendered, no build step) and block patterns built from core blocks (call-to-action band, More features, Feature card, Theme buttons). Core Button blocks take the site's button style through the `mjb-btn*` classes. Page excerpts are enabled, so the intro line under a page title is editable in the editor sidebar. The file pair is self-contained and can move into a plugin unchanged.
- `inc/menus.php`: the top bar, mobile menu and footer links are managed under Appearance > Menus (new Footer location, `MJB_Theme_Nav_Walker`). Primary and Footer menus are created once from the previously hard-coded links, only for locations with no menu assigned. Button items use the CSS classes `btn btn-outline btn-sm` / `btn btn-primary btn-sm`.
- Mega menu from the menu editor: any top-level item with sub-items opens a mega menu built from them (label, link, Description or the linked page's excerpt, icon from the matching home feature card). Sub-items with the class `mjb-mega-footer` go in the bottom strip. The Primary menu is created with every feature page as a sub-item of Features.
- `inc/conversion.php`: one-off conversion of generated content into blocks, run on the first logged-in admin page view. Home feature cards get a Buttons block for Learn More; the Features page gets the Feature Grid block and the call-to-action band; each feature page gets the More features section and the band. The band is a synced pattern, so one edit updates every page. Previous content is kept in `_mjb_content_before_blocks` post meta and in revisions; the home page is only changed if its blocks re-serialize byte for byte. A one-time admin notice reports what was changed.
- Comparison table: a first-column icon can be chosen with a class on the label cell (`mjb-icon-coin`, `-server`, `-sliders`, `-card`, `-lock`, `-list`, `-search`, `-users`, `-clock`, `-chart`, `-code`, `-globe`, `-shield`, `-bolt`); row-position defaults remain.

### Changed
- After conversion the theme no longer generates the Learn More buttons, the Features page grid or the More features rows at render time; they are page content.
- `js/mega-menu.js` handles any number of mega menus.

## [0.9.0-beta.50] - 2026-11-15

### Added
- Features page (/features/): grid of feature tiles (icon, title, description, Learn More) plus a call-to-action band, rendered by `mjb_theme_feature_pages_content()` in `inc/feature-pages.php`. Individual feature pages get a "More features" row of three tiles and the same band.
- Mobile menu: Features is now a collapsible group listing every feature page with its icon, plus "All features". The open mobile menu scrolls when it is taller than the screen.

### Changed
- Mega menu icons use the same gradient tile as the home feature icons, and the menu no longer needs the home page to be viewed first: `mjb_theme_get_feature_cards()` builds the card list from the front page content when none is recorded.
- Breadcrumb links use the same hover as the nav and footer links (colour shift, underline growing from the left).
- One-off: the generated bulleted list on the Features page is cleared and the page given an excerpt, only if the content is still exactly the generated list (`mjb_theme_features_page_upgraded` option).

## [0.9.0-beta.49] - 2026-11-14

### Added
- Content page hero (`.mjb-content-hero`, including docs) now has the same aurora gradient as the home hero (appended to `css/styles.css`).
- Breadcrumbs in the content page hero (`mjb_theme_breadcrumbs()` in the new `inc/navigation.php`, called from `index.php`), with BreadcrumbList JSON-LD in the head on singular pages.
- Features mega menu in the top nav: every feature with its icon, title and description, plus links to all features and the live demo. Rendered by `mjb_theme_features_nav_item()` from `header.php` (and attached to the Features item when a Primary menu is assigned). Items come from the front page feature cards, recorded in the `mjb_theme_feature_cards` option by `inc/feature-pages.php`; falls back to the child pages of /features/.
- `js/mega-menu.js`: aria-expanded, Escape to close, tap-to-open on touch, click-outside.

## [0.9.0-beta.48] - 2026-11-14

### Fixed
- Feature card "Learn More" buttons did not appear: beta.47 only looked for Group blocks with a `feature-card` className. `inc/feature-pages.php` now works from the rendered front page HTML via `the_content` (any element with the `feature-card` class), for both the buttons and the page creation, which now runs on `shutdown` after the front page is viewed.

## [0.9.0-beta.47] - 2026-11-14

### Added
- `inc/feature-pages.php`: a "Learn More" button at the bottom left of each home feature card, added server-side via `render_block_core/group`, linking to `/features/{slug}/` where the slug is the card title through `sanitize_title()`.
- The same file creates the pages those links point to: a published "Features" parent page plus one child page per card (title from the card, card description as the excerpt). Runs once per card on the next page load; state is kept in the `mjb_theme_feature_pages` option. Existing pages are never modified and deleted pages are not recreated.

## [0.9.0-beta.46] - 2026-11-14

### Changed
- Home hero: the top-left glow of the background gradient is now the accent orange (`--accent`, `#F97316`, as in the Featured pill) instead of teal.

## [0.9.0-beta.45] - 2026-11-14

### Changed
- Home comparison table: icons beside the first-column labels (CSS masks assigned by row position in `css/home.css`), wider centre column so each Modern Job Board value stays on one line from 1024px up, ticks and the SaaS column left-aligned. Cells compacted on phones.

## [0.9.0-beta.44] - 2026-11-14

### Added
- Top menu: icons left of Features, Docs, Compare, Developers and Pricing (CSS masks keyed on link destination, appended to `css/styles.css`; also shown in the mobile menu). Hidden between 768px and 1099px, where the nav is also tightened so it fits on one line.
- Home: animated feature icons (draw-in on reveal, then a looping motion per icon), floating chart chip beside the hero card, accent underline under the headline highlight.
- `js/home.js` (front page only, `mjb-home-script`): hero card tilts towards the pointer; feature and pricing cards get a pointer spotlight. Skipped on touch devices and with reduced motion.

### Changed
- Home hero and FAQ: dot grid removed; background is now a drifting aurora gradient with light grain.

### Fixed
- Home headline: last letter of the gradient highlight no longer clipped.
- Home: no horizontal overflow from the Developers section on narrow screens.

## [0.9.0-beta.43] - 2026-11-14

### Added
- `css/home.css`: home page polish layer, enqueued on the front page only as `mjb-home-style` and scoped to `body.home`. Same fonts and palette. Layered hero background with dot grid, larger gradient headline, glass product card with a slight tilt, stats merged into one floating card over the hero, centred section headers with pill eyebrows, gradient feature icons and card hover accents, emphasised MJB column in the comparison table, glow behind the code window, gradient-bordered Pro pricing card, textured FAQ band.

### Fixed
- Home hero no longer overflows horizontally on narrow screens (grid columns use `minmax(0, 1fr)`).

## [0.9.0-beta.39] - 2026-08-21

### Changed
- Demo nav Job Seeker / Recruiter controls are icon-only again (no label, no chevron). Green pill tooltips sit to the left of the icons; Login / Register dropdowns are unchanged.

## [0.9.0-beta.38] - 2026-08-19

First GitHub snapshot after the initial commit (2026-07-21, then `0.9.0-beta.4`).

### Added
- Demo nav under `/jobs/` with job-seeker and recruiter Login / Register dropdowns; logo links to the marketing root.
- Docs page: sidebar TOC / reference shell.
- Landing CSS: hero job-preview card, comparison table, feature grids, DM Sans.

### Changed
- Footer GitHub link replaced with in-site Pricing.
- Demo URLs for companies, blog, post-a-job, and dashboards nest under `/jobs/`.
