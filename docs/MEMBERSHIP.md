# Membership system guide

## Online membership applications

Visitors apply at **/membership/apply** (every "Join Us" button on the website
leads there). The form asks for personal details, contact and location
(region and district lists), education, occupation and why they want to join.
After sending it, the applicant sees a reference number such as `APP-000012`.

Applications appear under **Management → Membership Applications**, with a
counter of the ones waiting for review, and a reminder on the dashboard.

- **Approve** creates the member with the next membership number
  (`AYFC-000123`). You choose the membership type and, optionally, create a
  login account with a role (normally **Member**). The login e-mail and a
  temporary password are shown once: pass them to the new member.
- **Reject** keeps the application with an optional internal note.
- If a member with the same phone number already exists, the application is
  flagged as a duplicate and cannot be approved.

Approving or rejecting needs the `create members` permission; seeing
applications needs `view members`.

## Members

- The registration wizard and the edit form now save every field: first,
  middle and last name, current region / district / village and employment
  details (these were previously lost), so the filters on the members list
  work.
- Spouse details are only required when the marital status is "Married".
- Validation errors are shown on the form and the typed information is kept.
- Membership numbers are never reused, also after a member is deleted.
- Changing a member's role on the edit form is now saved.
- New login accounts get a random password, shown once after registration.

## Elections and voting

- Members see one choice per position on the ballot and can vote for all
  positions with one click. Positions already voted for are marked.
- If voting is refused, the member sees why (voting not started, ended,
  election closed, already voted).
- Elections can be edited (name, voting period) and closed early.
- Positions can be edited; duplicate names are refused.
- Nominations without votes can be removed.
- **Voting Analytics** in the menu now opens the analytics page.

## Documents

- Files are stored privately and can only be downloaded by logged-in users
  with the `view documents` permission.
- Members can read documents; uploading and deleting need `create documents`
  and `delete documents`.
- Document categories can be edited (the edit page was missing).

## Other fixes

- Edit pages for membership types and academic institutions were missing.
- Event categories can be switched on and off; only active ones are offered
  when creating an event. Events can be saved as drafts.
- The Events menu was hidden for everyone because of a typo in its permission.
- Two routes shared the name `password.update`, which broke
  `php artisan route:cache` and the password form on the profile page. Both
  password forms (Account Settings → Change Password and the profile page)
  now work.
- The forgot / reset password pages no longer need a front-end build.
- `php artisan route:cache` works (duplicate route names were removed).

## For developers

- `php artisan test` runs the whole suite (98 tests) on SQLite; it also passes
  on MySQL/MariaDB. Membership tests are in `tests/Feature/Membership`.
- `tests/Concerns/SeedsMembership.php` seeds the real roles and permissions
  and has helpers to create members.
- Routes and their permissions: `routes/web.php` ("Membership system" section).
