# Deploying updates to the server

These steps update the live server (`/var/www/ayfc`) to this version: the new
public website, the Website admin section, online membership applications and
the membership-system fixes.

## 1. Back up first

The live server has code that was never pushed to this repository (for
example the current `/admin/sliders` page). This update replaces those routes,
so keep a copy of both the code and the database:

```bash
cd /var/www/ayfc
mysqldump -u <db-user> -p <db-name> > ~/ayfc-backup-$(date +%F).sql
cp -a /var/www/ayfc ~/ayfc-code-backup-$(date +%F)
```

## 2. Update the code and database

Update the code (for example with `git pull`), then run:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate:status        # see which migrations will run
php artisan migrate --force
php artisan storage:link          # only needed once; makes uploaded website images public
php artisan documents:secure      # moves library documents out of the public folder (see below)
php artisan optimize:clear
php artisan route:cache           # optional, now works (speeds up the site)
```

### What `php artisan migrate` does

- Creates the website tables, the `membership_applications` table and the
  `manage website` permission (given to **Super Admin** and **Admin**, plus a
  new **Website Editor** role).
- Every migration now checks whether its table or column already exists.
  Migrations that were renamed in the past (and would otherwise try to create
  existing tables and stop with "table already exists") are skipped safely.
- A final catch-up migration adds anything older out-of-order migrations had
  to skip (for example `is_active` on event categories and the full
  document-category columns). No existing data is changed or removed.
- The event `status` column becomes a plain text column so "Draft" can be
  saved. Existing values are kept.
- If a `sliders` table already exists it is kept with its rows; only missing
  columns are added.

This was tested on MySQL/MariaDB with a database built from the original
migrations and containing data, and on a completely empty database.

### Protecting existing documents

Older versions stored library documents in `storage/app/public/documents`,
which is reachable from the internet at `/storage/documents/...`, even for
documents that were not meant to be public. New uploads now go to private
storage. Move the existing files with:

```bash
php artisan documents:secure --dry-run   # list what would be moved
php artisan documents:secure             # move them
```

Downloads keep working before and after the move.

### Regions and districts

The membership application form and the member wizard offer region and
district lists. If the lists are empty on the server, load them once (safe to
run again):

```bash
php artisan db:seed --class=RegionSeeder --force
php artisan db:seed --class=DistrictSeeder --force
```

## 3. Check roles and permissions

Each admin action now needs its own permission: `view …` to open lists,
`create …` to add, `edit …` to change and `delete …` to remove. Before, a
single `view …` permission allowed everything, so ordinary members could edit
and delete members, events and documents.

- **Super Admin** can always do everything.
- With the default roles, **Admin** can view, add and edit, but not delete,
  members, events, documents and elections. **Member** can view and vote.
- If someone is now refused an action they need, give their role the matching
  permission under **Account Settings → Roles & Permissions**. Running
  `php artisan db:seed --class=RolesAndPermissionsSeeder --force` restores the
  default permissions of the built-in roles (it only adds, never removes).

## 4. Server settings for uploads

Images up to 5 MB each are accepted, and the gallery accepts up to 20 photos
at once. Check these values in `php.ini` (or the hosting control panel):

```ini
upload_max_filesize = 8M
post_max_size = 64M
```

Large photos are scaled down automatically when the PHP **GD** extension is
available (most servers have it).

## 5. Tell existing members about passwords

New member accounts now get a random password that is shown once to the
administrator. Older accounts were given a password made from the last six
digits of the phone number plus `@Member`, which anyone who knows the number
can guess. Ask existing members to change their password under
**Account Settings → Change Password**.
