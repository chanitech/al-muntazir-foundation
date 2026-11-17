# AYFC public website and Website admin

The public website (homepage, About, Programs, News, Events, Gallery, Contact)
is managed from the admin panel under **Website**. Nothing on the website is
hard-coded any more: names, logo, colours, contact details, texts, photos and
lists all come from the admin panel.

## Deploying

See [DEPLOYMENT.md](DEPLOYMENT.md) for the server update steps (backup,
migrations, permissions and upload settings).

## Using the Website admin

| Menu item | What it controls |
| --- | --- |
| **Site Settings** | Organisation name, tagline, motto, logo, favicon, website colours, contact details (phone, e-mail, WhatsApp, address, office hours, Google Map), social media links, About/Mission/Vision/Core values, homepage headings and the "Join Us" button. |
| **Homepage Slider** | The large rotating images at the top of the homepage: image, heading, text and an optional button. |
| **Programs** | Program cards (homepage and Programs page) with a full description page each. |
| **News & Articles** | News posts with a rich-text editor (headings, lists, images, YouTube videos). Untick *Published* to keep a draft; set a future date to schedule. |
| **Team** | People shown on the About page. |
| **Testimonials** | Quotes shown on the homepage. |
| **Photo Gallery** | Upload many photos at once and group them in albums. Visitors can filter by album. |
| **Partners** | Partner logos (homepage and About page). |
| **Impact Numbers** | The animated figures in the "Our Impact" band (first 4 visible ones). |
| **Newsletter Subscribers** | E-mails collected in the website footer, with a CSV download. |
| **Website Messages** | Messages from the Contact page. The red counter shows unread messages. |

Every list has a **Visible / Hidden** button to show or hide an item without
deleting it, and a **Display order** number (lower numbers first).

**Events.** Events are still managed under **Events**. Tick *Show this event
on the public website* (and optionally add a cover image) to publish an
event. Internal events stay private by default.

**Who can edit.** Users with the `manage website` permission can edit the
website. To give someone access to the website only, give them the
**Website Editor** role (Account Settings → Roles & Permissions).

Sections without content are hidden automatically. For example, the
testimonials section only appears once a testimonial has been added.

## For developers

- Public pages: `app/Http/Controllers/Website`, views in `resources/views/website`,
  styles in `public/css/website.css`, scripts in `public/js/website.js`.
- Admin: `app/Http/Controllers/Admin/Website` (all list/edit screens share
  `ContentController`), views in `resources/views/admin/website`, form
  components in `resources/views/components/admin`.
- Settings: defaults in `config/website.php`; saved values in the
  `site_settings` table, read through `App\Support\SiteSettings` (`$site` in views).
- Rich text from the editor is cleaned by `App\Support\HtmlSanitizer` before saving.
- Front-end libraries are self-hosted in `public/vendor/website` (Bootstrap 5.3.3,
  Bootstrap Icons 1.11.3, GLightbox 3.3.0) and `public/vendor/summernote` (0.8.20).
- Tests: `php artisan test tests/Feature/Website tests/Unit`. They migrate only
  the tables the website uses (see `tests/Concerns/RefreshesWebsiteDatabase.php`),
  because some older membership migrations cannot run on an empty database.
