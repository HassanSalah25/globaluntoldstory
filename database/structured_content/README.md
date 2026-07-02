# Structured Website Content (for Laravel seeding)

This folder is a clean, machine-readable rebuild of every `.docx` translation
file in this delivery, organized by **page/content-type → language**, ready
to seed a Laravel database. It was generated automatically from the 199
`.docx` files with `_tools/extract_content.py` — see [COVERAGE.md](COVERAGE.md)
for exactly which language exists for which page.

## Folder layout

```
structured_content/
  content/
    pages/<slug>/<locale>.json          Home, About Us, Contact, Services overview
    services/<slug>/<locale>.json       The 13 individual service detail pages
    articles/<slug>/<locale>.json       The 3 blog articles
    portfolios/<slug>/<locale>.json     The 5 portfolio/case-study category pages
  combined/
    all_content.json                    Everything in one file (category -> slug -> locale -> data)
    manifest.json                       Flat list of every generated file
  COVERAGE.md                           Which languages exist for which page
  EXTRACTION_REPORT.txt                 Processing log / warnings
```

Locale codes used: `en` English, `de` German, `es` Spanish, `fr` French,
`it` Italian, `pt` Portuguese (BR), `tr` Turkish, `ru` Russian, `ar` Arabic.
Not every page has all 9 languages — the client's source folders themselves
are missing a few translations (see COVERAGE.md); those simply have no JSON
file for that locale.

## JSON shape per content type

**Pages** (`content/pages/*.json`) — Home Page, About Us, Contact Page,
Services overview. These are free-form marketing pages (nav, hero, footer,
etc.), so they're kept as an ordered list of content blocks:

```json
{
  "locale": "en",
  "slug": "home-page",
  "type": "page",
  "blocks": [
    { "heading": "About Us", "body": ["Our Portfolio", "Get in touch"] },
    { "heading": "Egypt", "body": ["Egyptian Media Production City", "+201001299639"] }
  ]
}
```

**Services** (`content/services/*.json`) — the 13 service detail pages
(Photography, Documentary Production, Podcast Production, etc.):

```json
{
  "title": "Commercial Advertising Production",
  "subtitle": "TVC • Digital • Performance",
  "intro": ["..."],
  "sections": [
    { "heading": "Our Experience in Commercial Advertising Production", "body": ["..."] },
    { "heading": "Scriptwriting", "body": ["..."] }
  ]
}
```
Each named sub-service is its own entry in `sections`, in the same order as
the source document. Some sections also carry a `meta` array when the source
copy used a `Label: description` bullet format.

**Articles** (`content/articles/*.json`) — the 3 blog posts, with FAQs and
the conclusion automatically separated out:

```json
{
  "title": "How to Choose a Media Production Agency in Egypt?",
  "sections": [{ "heading": "...", "body": ["..."] }],
  "faqs": [{ "question": "...", "answer": "..." }],
  "conclusion": { "heading": "Conclusion", "body": ["..."] }
}
```

**Portfolios** (`content/portfolios/*.json`) — the 5 case-study/portfolio
listing pages, split into individual project items:

```json
{
  "items": [
    {
      "title": "A Corporate Film | Apache Egypt",
      "description": ["..."],
      "client": "Apache Egypt",
      "service": "Corporate Content",
      "industry": "Energy"
    }
  ]
}
```

## How the extraction works (and its limits)

Only the English `.docx` files use real Word heading styles; every
translated file is flat "Normal" style text. So the parser doesn't rely on
Word styles — it classifies each line as a **heading** (short, or ending in
`:`/`?`) or **body** (a full sentence, usually ending in `.`), then groups
each heading with the body text that follows it. This works consistently
across all 9 languages and correctly reproduces the same section structure
in German, Spanish, Arabic, etc. as in the English version, without ever
needing to hand-translate labels.

Known limitations to be aware of when wiring this into your Laravel app:
- On free-form pages (Home/About/Contact) the split into `blocks` is
  literal/positional, not hand-labelled — you'll want to map specific
  blocks to specific template slots yourself (e.g. block 5 = hero heading).
- Occasionally a short lead-in sentence absorbs a couple of unrelated
  paragraphs that follow it into the same section body — no content is
  lost or reordered, just grouped slightly coarser than the "ideal" split.
- Client/Service/Industry fields on portfolio items are detected generically
  from `Label: value` lines, so this works regardless of language.

If anything looks off for a specific page, the original `.docx` is always
the source of truth — re-open it and compare against `source_file` in the
JSON.

## Seeding a Laravel project

A ready-to-use migration/model/seeder set is included in
`../laravel-seed-kit/` (outside this folder) — see its README for setup
steps. It reads directly from `content/` and populates `pages`,
`services`, `articles`, and `portfolio_categories` tables, each with a
matching `*_translations` table keyed by locale.
