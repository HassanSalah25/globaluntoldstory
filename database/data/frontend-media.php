<?php

/**
 * Frontend seed images — imported into the media library by MediaSeeder.
 *
 * Keys are referenced from ContentSeeder via FrontendMediaRegistry::url().
 * Images in the frontend/ and frontend/services/ folders are served from local storage.
 */
return [
    // ── Hero slider images ────────────────────────────────────────────────────
    'hero-slide-1' => [
        'source' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?q=80&w=1400&auto=format&fit=crop',
        'filename' => 'on-ground-production-services-egypt-giza-pyramids.webp',
        'folder' => 'frontend',
        'alt' => 'The Untold Story production team at the Giza Pyramids providing on-ground film production services in Egypt',
    ],
    'hero-slide-2' => [
        'source' => 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?q=80&w=1400&auto=format&fit=crop',
        'filename' => 'film-production-services-egypt-abu-simbel.webp',
        'folder' => 'frontend',
        'alt' => 'Professional film production services in Egypt by The Untold Story at Abu Simbel',
    ],

    // ── About page images ─────────────────────────────────────────────────────
    'about-story-photo' => [
        'source' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=800&auto=format&fit=crop',
        'filename' => 'cinema-camera-equipment.webp',
        'folder' => 'frontend',
        'alt' => 'Professional cinema camera equipment used in film and video production in Egypt',
    ],
    'about-hero' => [
        'source' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?q=80&w=1400&auto=format&fit=crop',
        'filename' => 'the-untold-story-film-production-egypt.webp',
        'folder' => 'frontend',
        'alt' => 'The Untold Story film production team at the Pyramids of Giza in Egypt',
    ],

    // ── Blog image ────────────────────────────────────────────────────────────
    'blog-film-production' => [
        'source' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1200&auto=format&fit=crop',
        'filename' => 'professional-cinema-camera-film-production.webp',
        'folder' => 'frontend',
        'alt' => 'RED Dragon cinema camera used for professional film and commercial video production',
    ],

    // ── 4 Featured service card images (800×540) ──────────────────────────────
    'svc-on-ground' => [
        'source' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?q=80&w=800&auto=format&fit=crop',
        'filename' => 'on-ground-production-services-egypt-pyramids.webp',
        'folder' => 'frontend',
        'alt' => 'Film crew providing on-ground production services at the Pyramids of Giza',
    ],
    'svc-commercial' => [
        'source' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=800&auto=format&fit=crop',
        'filename' => 'commercial-food-advertising-production-egypt.webp',
        'folder' => 'frontend',
        'alt' => 'Woman featured in a commercial food advertising production campaign',
    ],
    'svc-documentary' => [
        'source' => 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?q=80&w=800&auto=format&fit=crop',
        'filename' => 'documentary-production-egypt-pyramids.webp',
        'folder' => 'frontend',
        'alt' => 'Documentary production at the Pyramids of Giza in Egypt',
    ],
    'svc-corporate' => [
        'source' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop',
        'filename' => 'industrial-content-production-egypt.webp',
        'folder' => 'frontend',
        'alt' => 'Industrial facility used for corporate and industrial video production',
    ],

    // ── All services page images ──────────────────────────────────────────────
    'svc-all-on-ground' => [
        'source' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?q=80&w=800&auto=format&fit=crop',
        'filename' => 'on-ground-production-services-egypt.webp',
        'folder' => 'frontend/services',
        'alt' => 'Professional camera operator on location for on-ground production services in Egypt',
    ],
    'svc-all-commercial' => [
        'source' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=800&auto=format&fit=crop',
        'filename' => 'commercial-advertising-production-egypt.webp',
        'folder' => 'frontend/services',
        'alt' => 'Commercial advertising production featuring branded content and product placement',
    ],
    'svc-all-documentary' => [
        'source' => 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?q=80&w=800&auto=format&fit=crop',
        'filename' => 'documentary-production-egypt.webp',
        'folder' => 'frontend/services',
        'alt' => 'Documentary production concept inspired by Egyptian heritage and historical storytelling',
    ],
    'svc-all-corporate' => [
        'source' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop',
        'filename' => 'corporate-industrial-content-production.webp',
        'folder' => 'frontend/services',
        'alt' => 'Industrial facility used for corporate and industrial video production',
    ],
    'svc-all-events' => [
        'source' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=800&auto=format&fit=crop',
        'filename' => 'event-coverage-live-production.webp',
        'folder' => 'frontend/services',
        'alt' => 'Live event coverage and multi-camera production setup',
    ],
    'svc-all-tv-broadcast' => [
        'source' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop',
        'filename' => 'tv-shows-live-broadcast-production.png',
        'folder' => 'frontend/services',
        'alt' => 'TV shows and live broadcast production studio setup',
    ],
    'svc-all-podcast' => [
        'source' => 'https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?q=80&w=800&auto=format&fit=crop',
        'filename' => 'podcast-production-services.webp',
        'folder' => 'frontend/services',
        'alt' => 'Professional podcast production services with studio setup',
    ],
    'svc-all-post-production' => [
        'source' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?q=80&w=800&auto=format&fit=crop',
        'filename' => 'post-production-finishing-services.webp',
        'folder' => 'frontend/services',
        'alt' => 'Post-production and finishing services with color grading and editing',
    ],
    'svc-all-motion-cgi' => [
        'source' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop',
        'filename' => 'motion-cgi-ai-powered-visuals.webp',
        'folder' => 'frontend/services',
        'alt' => 'Motion graphics, CGI, and AI-powered visual effects production',
    ],
    'svc-all-dubbing' => [
        'source' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=800&auto=format&fit=crop',
        'filename' => 'voice-over-localization-services.png',
        'folder' => 'frontend/services',
        'alt' => 'Professional voice-over, dubbing, and localization services',
    ],
    'svc-all-photography' => [
        'source' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=800&auto=format&fit=crop',
        'filename' => 'professional-photography-services.webp',
        'folder' => 'frontend/services',
        'alt' => 'Professional photography services for brands, products, and events',
    ],
    'svc-all-marketing' => [
        'source' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=800&auto=format&fit=crop',
        'filename' => 'marketing-solutions-performance.webp',
        'folder' => 'frontend/services',
        'alt' => 'Marketing solutions and performance analytics dashboard',
    ],
    'svc-all-original-ip' => [
        'source' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?q=80&w=800&auto=format&fit=crop',
        'filename' => 'original-ip-development-creative-concepts.webp',
        'folder' => 'frontend/services',
        'alt' => 'Original IP development and creative concept production',
    ],

    // ── Legacy keys kept for backward compatibility ───────────────────────────
    'film-production' => [
        'source' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?q=80&w=1400&auto=format&fit=crop',
        'filename' => 'film-production.jpg',
        'folder' => 'frontend',
        'alt' => 'Film and video production studio',
    ],
    'cinema-camera' => [
        'source' => 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?q=80&w=1400&auto=format&fit=crop',
        'filename' => 'cinema-camera.jpg',
        'folder' => 'frontend',
        'alt' => 'Cinema camera on set',
    ],
    'portrait-man-1' => [
        'source' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=400&auto=format&fit=crop',
        'filename' => 'portrait-man-1.jpg',
        'folder' => 'frontend',
        'alt' => 'Professional portrait',
    ],
    'portrait-man-2' => [
        'source' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
        'filename' => 'portrait-man-2.jpg',
        'folder' => 'frontend',
        'alt' => 'Professional portrait',
        'legacy_photo_ids' => ['photo-1574717026530-2e874b7698a8'],
    ],
    'camera-equipment' => [
        'source' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=400&auto=format&fit=crop',
        'filename' => 'camera-equipment.jpg',
        'folder' => 'frontend',
        'alt' => 'Camera equipment',
    ],
    'executive-portrait' => [
        'source' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400&auto=format&fit=crop',
        'filename' => 'executive-portrait.jpg',
        'folder' => 'frontend',
        'alt' => 'Executive portrait',
    ],
    'digital-marketing' => [
        'source' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=800&auto=format&fit=crop',
        'filename' => 'digital-marketing.jpg',
        'folder' => 'frontend',
        'alt' => 'Digital marketing analytics',
    ],
    'branding-design' => [
        'source' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?q=80&w=800&auto=format&fit=crop',
        'filename' => 'branding-design.jpg',
        'folder' => 'frontend',
        'alt' => 'Branding and design',
    ],
    'gaming-video' => [
        'source' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop',
        'filename' => 'gaming-video.jpg',
        'folder' => 'frontend',
        'alt' => 'Video production',
    ],
    'social-media' => [
        'source' => 'https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?q=80&w=800&auto=format&fit=crop',
        'filename' => 'social-media.jpg',
        'folder' => 'frontend',
        'alt' => 'Social media content',
    ],
    'fashion-retail' => [
        'source' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=800&auto=format&fit=crop',
        'filename' => 'fashion-retail.jpg',
        'folder' => 'frontend',
        'alt' => 'Fashion retail store',
    ],
    'gaming-arena' => [
        'source' => 'https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=800&auto=format&fit=crop',
        'filename' => 'gaming-arena.jpg',
        'folder' => 'frontend',
        'alt' => 'Gaming and entertainment',
    ],
    'analytics-dashboard' => [
        'source' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1200&auto=format&fit=crop',
        'filename' => 'analytics-dashboard.jpg',
        'folder' => 'frontend',
        'alt' => 'Analytics dashboard',
    ],
];
