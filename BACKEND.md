# The Untold Story — Laravel CMS Backend

API-first Laravel 12 backend for the Next.js frontend (`Advertising` project).

## Requirements

- PHP 8.2+
- Composer
- SQLite (default) or MySQL

## Quick Start

```bash
cd "c:\Users\Gohar\Documents\SEO Wolves\globaluntoldstory"
composer install
cp .env.example .env   # or copy manually on Windows
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

API base URL: `http://127.0.0.1:8000/api/v1`

## Default Admin

| Field | Value |
|-------|-------|
| Email | `admin@globaluntoldstory.com` |
| Password | `password` |

Login: `POST /api/v1/admin/login` → returns Sanctum bearer token.

## Environment

```env
FRONTEND_URL=http://localhost:3000
ADMIN_EMAIL=bendary@globaluntoldstory.com
```

## Public API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/home?locale=en` | Full home page payload |
| GET | `/layout?locale=en` | Navbar, footer, site config, announcement |
| GET | `/pages/{slug}?locale=en` | about, services, portfolio, blog, contact |
| GET | `/services` | All services |
| GET | `/services/{slug}` | Single service |
| GET | `/portfolio?category=&page=` | Portfolio listing |
| GET | `/portfolio/{slug}` | Single project |
| GET | `/blog?category=&tag=&search=&page=` | Blog listing |
| GET | `/blog/{slug}` | Full blog post |
| GET | `/faqs?locale=en` | FAQ list |
| GET | `/seo/{type}/{slug?}` | SEO meta (type: page, blog, service, portfolio) |
| GET | `/sitemap.xml` | XML sitemap |
| POST | `/contact` | Contact form |
| POST | `/leads/quote` | Services quote form |
| POST | `/newsletter/subscribe` | Newsletter signup |
| POST | `/newsletter/unsubscribe` | Unsubscribe by token |

**Locale:** `?locale=en|ar` or `Accept-Language: ar`

**Response envelope:**
```json
{ "success": true, "locale": "en", "data": { ... } }
```

## Admin API (Sanctum)

```
Authorization: Bearer {token}
```

| Method | Endpoint |
|--------|----------|
| POST | `/admin/login` |
| POST | `/admin/logout` |
| GET | `/admin/me` |
| GET | `/admin/dashboard` |
| GET/PATCH | `/admin/contact-requests` |
| GET/PATCH | `/admin/leads` |

## Frontend Integration (minimal changes)

1. Set `NEXT_PUBLIC_API_URL=http://127.0.0.1:8000/api/v1`
2. In `LanguageContext.tsx`, replace `fetch("/api/ads")` with `fetch(\`${API_URL}/layout?locale=${locale}\`)`
3. Wire forms:
   - Contact → `POST /contact`
   - Services quote → `POST /leads/quote`
   - Newsletter → `POST /newsletter/subscribe`

## Architecture

```
app/
├── Enums/           Locale, LeadStatus
├── Http/
│   ├── Controllers/Api/V1/   Public REST
│   ├── Controllers/Admin/    Protected admin
│   ├── Middleware/SetLocale.php
│   └── Requests/             Form validation
├── Models/          50+ Eloquent models + translations
├── Services/
│   ├── Content/     HomePage, Layout, Page, Portfolio, Blog
│   ├── Forms/       Contact, Lead, Newsletter
│   ├── Seo/         SEO + Sitemap
│   └── Settings/    SettingService
└── Traits/HasTranslations.php
```

## Database

- 54 CMS tables (content + forms + SEO)
- Bilingual via `{entity}_translations` tables (en/ar)
- Seeded from Next.js `app/lib/data.ts` + component hardcoded data

## Commands

```bash
php artisan migrate:fresh --seed   # Reset + reseed
php artisan route:list --path=api
php artisan tinker
```

## Next Steps (optional)

- Filament admin panel for full CRUD UI
- Replace Next.js `/dashboard` with Laravel admin or Filament
- Media upload endpoint + S3 storage
- Redis caching for `/home` and `/layout`
