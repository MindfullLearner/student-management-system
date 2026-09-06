# Student Management System (Static HTML + PHP API)

Devixo Solutions — Week 3 Task 03

## Architecture

This version splits the app into two layers:

- **`/api/`** — pure PHP, returns only JSON. No PHP renders any HTML. Each
  endpoint checks the session, talks to MySQL via PDO, and responds with
  `json_encode(...)`.
- **Everything else (`.html` files)** — plain static HTML pages. Each one
  loads `assets/js/api.js` (a tiny `fetch()` wrapper) plus a page-specific
  `<script>` that calls the API and fills in the DOM.

So instead of PHP printing `<table>...</table>`, a page like `students.html`
sits there empty, its script calls `GET /api/students/list.php`, gets JSON
back, and builds the table rows in JavaScript. Login works the same way:
`login.html` posts the email/password to `/api/auth/login.php` via `fetch`,
and PHP starts a normal `$_SESSION` on success — the browser keeps sending
that session cookie automatically on every later `fetch`, which is how the
protected API endpoints still know who you are.

## Features implemented
- Authentication: register, login, logout — all via `fetch` + JSON
- Sessions + bcrypt password hashing (PHP session cookie, not a token you manage yourself)
- Role-based access (Admin / User) — enforced **both** in the API (403 if you
  call an admin-only endpoint as a user) and in the UI (buttons/links hidden)
- Student CRUD: add, edit, delete, view — each a separate HTML page + API call
- Search + pagination on the students list
- Dashboard stat cards populated from `/api/dashboard/stats.php`
- Responsive layout, client-side form validation, loading spinners

## Folder structure
```
sms-html-php/
├── api/                          <- PHP, JSON only, no HTML
│   ├── bootstrap.php              (session_start + db + helpers, included by every endpoint)
│   ├── helpers.php                (json_response, require_login, require_role)
│   ├── config/db.php
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── logout.php
│   │   └── session.php            (GET -> who am I / 401 if not logged in)
│   ├── students/
│   │   ├── list.php               (GET  ?search=&page=)
│   │   ├── get.php                (GET  ?id=)
│   │   ├── create.php             (POST, admin only)
│   │   ├── update.php             (POST, admin only)
│   │   └── delete.php             (POST, admin only)
│   └── dashboard/stats.php        (GET)
├── assets/
│   ├── css/style.css
│   └── js/
│       ├── api.js                 (apiFetch helper used everywhere)
│       ├── nav.js                 (auth guard + fills sidebar + logout button)
│       └── validation.js          (validateForm() for all forms)
├── index.html                     (redirects to dashboard or login)
├── login.html
├── register.html
├── dashboard.html
├── students.html
├── student-add.html
├── student-edit.html
├── student-view.html
├── database.sql
└── README.md
```

## How a protected page works (e.g. `dashboard.html`)
1. Loads `api.js` then `nav.js`.
2. `nav.js` immediately calls `GET /api/auth/session.php`.
   - If that comes back `401`, `nav.js` redirects to `/login.html`.
   - If it succeeds, `nav.js` fills in the sidebar's name/role, shows any
     `.admin-only` elements if you're an admin, and fires a `user-ready` event.
3. The page's own `<script>` waits for `user-ready`, then calls whichever
   API endpoint it needs (e.g. `/api/dashboard/stats.php`) and renders the
   result into the page.

## Setup

1. **Create the database**
   ```
   mysql -u root -p < database.sql
   ```
   Seeds one admin account: `admin@devixo.com` / `Admin@123`.

2. **Configure the DB connection** in `api/config/db.php` (username/password).

3. **Run it.** Because the front end calls `/api/...` with absolute paths,
   serve the whole `sms-html-php` folder as your web root — either drop it
   into XAMPP/WAMP's `htdocs`/`www`, or from inside the folder run:
   ```
   php -S localhost:8000
   ```
   Then open `http://localhost:8000/`.

4. Log in with the seeded admin account, or register a new account (new
   sign-ups get the `user` role — view/search only, no add/edit/delete).

## Note
I don't have a live PHP/MySQL environment to execute this end-to-end, so
please test it locally — especially the login flow — and let me know if
anything errors out.
