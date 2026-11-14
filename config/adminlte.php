<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    */

    'title' => 'AYFC Admin',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    */

    'logo' => '<b>AYFC</b> Admin',
    'logo_img' => 'images/logo.jpg',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'AYFC Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    */

    'auth_logo' => [
        'enabled' => true,
        'img' => [
            'path' => 'images/logo.jpg',
            'alt' => 'AYFC Logo',
            'class' => 'img-circle',
            'width' => 90,
            'height' => 90,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'images/logo.jpg',
            'alt' => 'AYFC Logo',
            'effect' => 'animation__wobble',
            'width' => 90,
            'height' => 90,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    */

    'use_route_url' => false,
    'dashboard_url' => 'dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    */

    'menu' => [

    // Top bar: open the public website in a new tab
    [
        'text' => 'View Website',
        'url' => '/',
        'icon' => 'fas fa-external-link-alt',
        'topnav_right' => true,
        'target' => '_blank',
    ],

    // Dashboard
    [
        'text' => 'Dashboard',
        'route' => 'dashboard',          // was 'url' => 'dashboard'
        'icon' => 'fas fa-tachometer-alt',
    ],

    // Community Management
    [
        'text' => 'Management',
        'icon' => 'fas fa-users',
        'can' => 'view members',
        'submenu' => [
            [
                'text' => 'Members',
                'route' => 'admin.members.index',
                'icon' => 'fas fa-user',
                'can' => 'view members',
            ],
            [
                'text' => 'Academic Institutions',
                'route' => 'academic-institutions.index',
                'icon' => 'fas fa-university',
                'can' => 'view academic institutions',
            ],
            [
                'text' => 'Membership Types',
                'route' => 'membership-types.index',
                'icon' => 'fas fa-id-card',
                'can' => 'view membership types',
            ],
        ],
    ],

    // Events Management
    [
        'text' => 'Events',
        'icon' => 'fas fa-calendar-alt',
        'can' => 'view events',
        'submenu' => [
            [
                'text' => 'Event List',
                'route' => 'events.index',
                'icon' => 'far fa-list-alt',
                'can' => 'view events',
            ],
            [
                'text' => 'Add Event',
                'route' => 'events.create',
                'icon' => 'fas fa-plus',
                'can' => 'create events',
            ],
            [
                'text' => 'Event Categories',
                'route' => 'event-categories.index',
                'icon' => 'fas fa-tags',
                'can' => 'view event categories',
            ],
        ],
    ],

    // Document Library
    [
        'text' => 'Document Library',
        'icon' => 'fas fa-folder',
        'can' => 'view documents',
        'submenu' => [
            [
                'text' => 'Documents',
                'route' => 'documents.index',
                'icon' => 'fas fa-file-alt',
                'can' => 'view documents',
            ],
            [
                'text' => 'Document Categories',
                'route' => 'document-categories.index',
                'icon' => 'fas fa-list',
                'can' => 'view document categories',
            ],
        ],
    ],

    // Election System
    [
        'text' => 'Election System',
        'icon' => 'fas fa-vote-yea',
        'can' => 'view elections',
        'submenu' => [
            [
                'text' => 'Elections',
                'route' => 'elections.index',
                'icon' => 'fas fa-calendar-check',
                'can' => 'view elections',
            ],
            [
                'text' => 'Candidates',
                'route' => 'candidates.index',
                'icon' => 'fas fa-user-check',
                'can' => 'view candidates',
            ],
            [
                'text' => 'Positions',
                'route' => 'positions.index',
                'icon' => 'fas fa-sitemap',
                'can' => 'view positions',
            ],
            [
                'text' => 'Voting',
                'route' => 'voting.index',
                'icon' => 'fas fa-vote-yea',
                'can' => 'vote',
            ],
            [
                'text' => 'Voting Analytics',
               // 'route' => 'voting.analytics',   // fixed: use route name, with parameter
                'icon' => 'fas fa-chart-line',
                'permission' => 'view voting analytics',
                // If parameter is required, you can pass a default, e.g., ['id' => 1]
                'params' => ['id' => 1],
            ],
        ],
    ],

    // Public Website content (sliders, pages, news...). The "Website Messages"
    // item with its unread counter is added right after this one in
    // AppServiceProvider (it needs a live count from the database).
    [
        'key' => 'website',
        'text' => 'Website',
        'icon' => 'fas fa-globe-africa',
        'can' => 'manage website',
        'submenu' => [
            ['text' => 'Site Settings', 'route' => 'admin.settings.edit', 'icon' => 'fas fa-sliders-h', 'can' => 'manage website', 'active' => ['admin/website-settings*']],
            ['text' => 'Homepage Slider', 'route' => 'admin.sliders.index', 'icon' => 'fas fa-images', 'can' => 'manage website', 'active' => ['admin/sliders*']],
            ['text' => 'Programs', 'route' => 'admin.programs.index', 'icon' => 'fas fa-hands-helping', 'can' => 'manage website', 'active' => ['admin/programs*']],
            ['text' => 'News & Articles', 'route' => 'admin.posts.index', 'icon' => 'fas fa-newspaper', 'can' => 'manage website', 'active' => ['admin/news*']],
            ['text' => 'Team', 'route' => 'admin.team.index', 'icon' => 'fas fa-user-tie', 'can' => 'manage website', 'active' => ['admin/team*']],
            ['text' => 'Testimonials', 'route' => 'admin.testimonials.index', 'icon' => 'fas fa-quote-left', 'can' => 'manage website', 'active' => ['admin/testimonials*']],
            ['text' => 'Photo Gallery', 'route' => 'admin.gallery.index', 'icon' => 'fas fa-camera', 'can' => 'manage website', 'active' => ['admin/gallery*']],
            ['text' => 'Partners', 'route' => 'admin.partners.index', 'icon' => 'fas fa-handshake', 'can' => 'manage website', 'active' => ['admin/partners*']],
            ['text' => 'Impact Numbers', 'route' => 'admin.stats.index', 'icon' => 'fas fa-chart-bar', 'can' => 'manage website', 'active' => ['admin/impact-stats*']],
            ['text' => 'Newsletter Subscribers', 'route' => 'admin.subscribers.index', 'icon' => 'fas fa-at', 'can' => 'manage website', 'active' => ['admin/subscribers*']],
        ],
    ],

    // Account Settings
    [
        'text' => 'Account Settings',
        'icon' => 'fas fa-user-cog',
        'submenu' => [
            [
                'text' => 'Change Password',
                'route' => 'password.change',
                'icon' => 'fas fa-key',
                'permission' => 'change password',
            ],
            [
                'text' => 'Roles & Permissions',
                'route' => 'admin.roles.index',
                'icon' => 'fas fa-lock',
                'permission' => 'view roles',
            ],
        ],
    ],

],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    */

    'plugins' => [
        // Styles for the Website section of the admin panel (always loaded).
        'WebsiteAdmin' => [
            'active' => true,
            'files' => [
                ['type' => 'css', 'asset' => true, 'location' => 'css/admin-website.css'],
            ],
        ],
        // Rich-text editor for news articles and program pages (self-hosted).
        'Summernote' => [
            'active' => false,
            'files' => [
                ['type' => 'css', 'asset' => true, 'location' => 'vendor/summernote/summernote-bs4.min.css'],
                ['type' => 'js', 'asset' => true, 'location' => 'vendor/summernote/summernote-bs4.min.js'],
            ],
        ],
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    */

    'livewire' => false,

];