# AYFC public website and Website admin

The public website (homepage, About, Programs, News, Events, Gallery, Contact)
is managed from the admin panel under **Website**. Nothing on the website is
hard-coded any more: names, logo, colours, contact details, texts, photos and
lists all come from the admin panel.

## Deploying this update to the server

> **Back up first.** Copy the server folder and dump the database before
> updating. The live server has code (for example the existing
> `/admin/sliders` page) that is not in this repository, and this update
> replaces those routes.

```bash
cd /var/www/ayfc
mysqldump -u <db-user> -p <db-name> > ~/ayfc-backup-$(date +%F).sql
cp -a /var/www/ayfc ~/ayfc-code-backup-$(date +%F)
```

Then update the code (for example with `git pull`) and run:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate:status        # check which migrations will run
php artisan migrate --force
php artisan storage:link          # only needed once; makes uploaded images public
php artisan optimize:clear
```

`php artisan migrate` creates the website tables and the `manage website`
permission, and gives that permission to the existing **Super Admin** and
**Admin** roles. The **Website** menu appears for those users straight away.

If a `sliders` table already exists, the migration keeps it and its rows and
only adds the missing columns. Existing slides stay visible.

### Server settings for photo uploads

Images up to 5 MB each are accepted, and the gallery accepts up to 20 photos
at once. Check these values in `php.ini` (or your hosting control panel):

```ini
upload_max_filesize = 8M
post_max_size = 64M
```

Large photos are automatically scaled down (e.g. slides to 1920 px wide).
This needs the PHP **GD** extension, which most servers have. Without GD,
images are stored unchanged.

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
