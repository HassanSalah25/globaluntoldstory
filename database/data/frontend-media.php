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
        'local' => database_path('data/media/on-ground-production-services-egypt-giza-pyramids.webp'),
        'filename' => 'on-ground-production-services-egypt-giza-pyramids.webp',
        'folder' => 'frontend',
        'alt' => 'The Untold Story production team at the Giza Pyramids providing on-ground film production services in Egypt',
    ],
    'hero-slide-2' => [
        'local' => database_path('data/media/film-production-services-egypt-abu-simbel.webp'),
        'filename' => 'film-production-services-egypt-abu-simbel.webp',
        'folder' => 'frontend',
        'alt' => 'The Untold Story production team filming at Abu Simbel Temple as part of a professional film production project in Egypt',
    ],

    // ── About page images ─────────────────────────────────────────────────────
    'about-story-photo' => [
        'local' => database_path('data/media/professional-cinema-camera-equipment-egypt-arri-alexa-mini-lf-film-production-equipment.webp'),
        'filename' => 'professional-cinema-camera-equipment-egypt-arri-alexa-mini-lf-film-production-equipment.webp',
        'folder' => 'frontend',
        'alt' => 'ARRI Alexa Mini LF cinema camera and professional film production equipment',
    ],
    'about-hero' => [
        'local' => database_path('data/media/the-untold-story-film-production-egypt.webp'),
        'filename' => 'the-untold-story-film-production-egypt.webp',
        'folder' => 'frontend',
        'alt' => 'The Untold Story film production team at the Pyramids of Giza in Egypt',
    ],

    // ── Blog image ────────────────────────────────────────────────────────────
    'blog-film-production' => [
        'local' => database_path('data/media/professional-cinema-camera-film-production.webp'),
        'filename' => 'professional-cinema-camera-film-production.webp',
        'folder' => 'frontend',
        'alt' => 'RED Dragon cinema camera used for professional film and commercial video production',
    ],

    // ── 4 Featured service card images (800×540) ──────────────────────────────
    'svc-on-ground' => [
        'local' => database_path('data/media/on-ground-production-services-egypt-pyramids.webp'),
        'filename' => 'on-ground-production-services-egypt-pyramids.webp',
        'folder' => 'frontend',
        'alt' => 'Film crew providing on-ground production services at the Pyramids of Giza',
    ],
    'svc-commercial' => [
        'local' => database_path('data/media/commercial-food-advertising-production-egypt.webp'),
        'filename' => 'commercial-food-advertising-production-egypt.webp',
        'folder' => 'frontend',
        'alt' => 'Woman featured in a commercial food advertising production campaign',
    ],
    'svc-documentary' => [
        'local' => database_path('data/media/documentary-production-egypt-pyramids.webp'),
        'filename' => 'documentary-production-egypt-pyramids.webp',
        'folder' => 'frontend',
        'alt' => 'Documentary film crew filming at the Pyramids of Giza in Egypt',
    ],
    'svc-corporate' => [
        'local' => database_path('data/media/industrial-content-production-egypt.webp'),
        'filename' => 'industrial-content-production-egypt.webp',
        'folder' => 'frontend',
        'alt' => 'Industrial machinery used in a corporate and industrial content production project',
    ],

    // ── All services page images ──────────────────────────────────────────────
    'svc-all-on-ground' => [
        'local' => database_path('data/media/on-ground-production-services-egypt.webp'),
        'filename' => 'on-ground-production-services-egypt.webp',
        'folder' => 'frontend/services',
        'alt' => 'Professional camera operator on location',
    ],
    'svc-all-commercial' => [
        'local' => database_path('data/media/commercial-advertising-production-egypt.webp'),
        'filename' => 'commercial-advertising-production-egypt.webp',
        'folder' => 'frontend/services',
        'alt' => 'Food and beverage commercial advertising production featuring branded content and product placement',
    ],
    'svc-all-documentary' => [
        'local' => database_path('data/media/documentary-production-egypt.webp'),
        'filename' => 'documentary-production-egypt.webp',
        'folder' => 'frontend/services',
        'alt' => 'Documentary production concept inspired by Egyptian heritage and historical storytelling',
    ],
    'svc-all-corporate' => [
        'local' => database_path('data/media/corporate-industrial-content-production.webp'),
        'filename' => 'corporate-industrial-content-production.webp',
        'folder' => 'frontend/services',
        'alt' => 'Industrial facility used for corporate and industrial video production',
    ],
    'svc-all-events' => [
        'local' => database_path('data/media/event-coverage-live-production.webp'),
        'filename' => 'event-coverage-live-production.webp',
        'folder' => 'frontend/services',
        'alt' => 'Broadcast control room managing live event coverage and multi-camera production',
    ],
    'svc-all-tv-broadcast' => [
        'local' => database_path('data/media/tv-shows-live-broadcast-production.png'),
        'filename' => 'tv-shows-live-broadcast-production.png',
        'folder' => 'frontend/services',
        'alt' => 'Television broadcast studio with professional cameras and green screen production setup',
    ],
    'svc-all-podcast' => [
        'local' => database_path('data/media/podcast-production-services.webp'),
        'filename' => 'podcast-production-services.webp',
        'folder' => 'frontend/services',
        'alt' => 'Professional podcast recording studio with microphones and interview setup',
    ],
    'svc-all-post-production' => [
        'local' => database_path('data/media/post-production-finishing-services.webp'),
        'filename' => 'post-production-finishing-services.webp',
        'folder' => 'frontend/services',
        'alt' => 'Professional video editing and color grading workstation',
    ],
    'svc-all-motion-cgi' => [
        'local' => database_path('data/media/motion-cgi-ai-powered-visuals.webp'),
        'filename' => 'motion-cgi-ai-powered-visuals.webp',
        'folder' => 'frontend/services',
        'alt' => 'Artificial intelligence and CGI concept for advanced visual production',
    ],
    'svc-all-dubbing' => [
        'local' => database_path('data/media/voice-over-localization-services.png'),
        'filename' => 'voice-over-localization-services.png',
        'folder' => 'frontend/services',
        'alt' => 'Voice-over artist recording in a professional studio',
    ],
    'svc-all-photography' => [
        'local' => database_path('data/media/professional-photography-services.webp'),
        'filename' => 'professional-photography-services.webp',
        'folder' => 'frontend/services',
        'alt' => 'Professional photography equipment including cameras, lenses, and accessories',
    ],
    'svc-all-marketing' => [
        'local' => database_path('data/media/marketing-solutions-performance.webp'),
        'filename' => 'marketing-solutions-performance.webp',
        'folder' => 'frontend/services',
        'alt' => 'Digital marketing dashboard displaying performance analytics and growth metrics',
    ],
    'svc-all-original-ip' => [
        'local' => database_path('data/media/original-ip-development-creative-concepts.webp'),
        'filename' => 'original-ip-development-creative-concepts.webp',
        'folder' => 'frontend/services',
        'alt' => 'Creative character concept representing original intellectual property development and content creation',
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
