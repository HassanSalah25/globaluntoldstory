# Frontend Integration

The Next.js app in `Advertising` is wired to this Laravel API.

## Setup

### 1. Start Laravel API

```bash
cd globaluntoldstory
php artisan serve
# http://127.0.0.1:8000
```

### 2. Start Next.js

```bash
cd Advertising
cp .env.local.example .env.local   # if not already present
npm run dev
# http://localhost:3000
```

`.env.local`:

```env
NEXT_PUBLIC_API_URL=http://127.0.0.1:8000/api/v1
```

### 3. CORS

Laravel `.env` must include:

```env
FRONTEND_URL=http://localhost:3000
```

## What is integrated

| Frontend | Laravel endpoint |
|----------|------------------|
| `LanguageContext` | `GET /layout`, `/home`, `/services` |
| `HeroCarousel` | Hero slides from `/home` |
| `WorkShowcase` | Work showcase from `/home` |
| Contact form | `POST /contact` |
| Services quote form | `POST /leads/quote` |
| Blog newsletter | `POST /newsletter/subscribe` |
| Navbar / Footer / announcement | Merged from `/layout` |

Static fallbacks in `data.ts` remain if the API is unreachable.

## Files changed (Advertising)

- `app/lib/api.ts` — API client
- `app/lib/cmsMerge.ts` — CMS → translation merge
- `app/components/LanguageContext.tsx`
- `app/components/HeroCarousel.tsx`
- `app/components/WorkShowcase.tsx`
- `app/contact/page.tsx`
- `app/services/page.tsx`
- `app/blog/page.tsx`
- `.env.local.example`

## Production

**Laravel `.env`:**

```env
FRONTEND_URL=https://frontend.globaluntoldstory.com
```

Optional: allow every subdomain with a regex pattern:

```env
CORS_ALLOWED_ORIGINS_PATTERNS=#^https://([a-z0-9-]+\.)?globaluntoldstory\.com$#
```

After changing `.env`, run `php artisan config:clear` on the server.

**Next.js `.env`:**

```env
NEXT_PUBLIC_API_URL=https://globaluntoldstory.com/api/v1
```

Do **not** include `/public/` in the API URL. The browser should call `/api/v1/...`, not `/api/public/api/v1/...`.

| Wrong | Correct |
|-------|---------|
| `https://globaluntoldstory.com/api/public/api/v1` | `https://globaluntoldstory.com/api/v1` |
