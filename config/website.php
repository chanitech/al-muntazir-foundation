<?php

/*
|--------------------------------------------------------------------------
| Public Website Defaults
|--------------------------------------------------------------------------
|
| These are the fallback values for the public website. Every value here can
| be changed from the admin panel (Website → Site Settings); whatever is saved
| there takes priority over these defaults.
|
*/

return [

    'defaults' => [
        // General
        'site_name' => 'Al-Muntadhir Youth Forum',
        'site_short_name' => 'AYFC',
        'tagline' => 'Empowering young minds',
        'motto' => 'You shall have the upper hand, should you be faithful.',
        'motto_source' => "Qur'an 3:139",
        'footer_about' => 'A youth-led forum nurturing faith, knowledge, leadership and service among young people.',

        // Branding
        'logo' => null,
        'favicon' => null,
        'primary_color' => '#0b5cad',
        'accent_color' => '#f2b90c',
        'dark_color' => '#0b1f3a',

        // Contact
        'contact_email' => null,
        'contact_phone' => null,
        'contact_whatsapp' => null,
        'address' => null,
        'office_hours' => null,
        'map_embed_url' => null,

        // Social media
        'facebook_url' => null,
        'instagram_url' => null,
        'x_url' => null,
        'youtube_url' => null,
        'tiktok_url' => null,
        'linkedin_url' => null,

        // About
        'about_title' => 'Welcome to Al-Muntadhir Youth Forum',
        'about_text' => "Al-Muntadhir Youth Forum is a community of young people committed to growing in faith, knowledge and character, and to serving the wider community.\n\nThrough mentorship, dialogue, training and community projects, we give young people the space, skills and confidence to lead.",
        'about_image' => null,
        'mission' => 'To nurture young people who are grounded in faith, equipped with knowledge and skills, and committed to serving their communities.',
        'vision' => 'A generation of faithful, confident and responsible young leaders.',
        'core_values' => "Faith\nKnowledge\nIntegrity\nService\nUnity",

        // Homepage
        'hero_title' => 'Al-Muntadhir Youth Forum',
        'hero_subtitle' => 'Empowering young minds through faith, knowledge, leadership and service.',
        'hero_image' => null,
        'programs_title' => 'Our Programs',
        'programs_intro' => 'Activities designed by and for young people to learn, connect and lead.',
        'stats_title' => 'Our Impact',
        'news_title' => 'Latest News',
        'events_title' => 'Upcoming Events',
        'cta_title' => 'Be part of the movement',
        'cta_text' => 'Join a community of young people growing together in faith, knowledge and service.',
        'cta_button_text' => 'Join Us',
        'cta_button_link' => null,

        // SEO
        'meta_description' => 'Al-Muntadhir Youth Forum: empowering young minds through faith, knowledge, leadership and service.',
    ],

    // Fields stored as uploaded images on the public disk.
    'image_settings' => ['logo', 'favicon', 'about_image', 'hero_image'],

];
