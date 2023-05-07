<?php

return [
    /****************************************************************************************
     * Librarian main config
     * Values set here will overwrite default configuration from the /config dir.
     *****************************************************************************************/

    # Site Information
    'site_name' => 'Sponsor Open Source Developers',
    'site_author' => envconfig('SITE_AUTHOR', '@erikaheidi'),
    'site_description' => envconfig('SITE_DESC', 'A public list of underrepresented open source developers that can be sponsored via GitHub'),
    'site_url' => envconfig('SITE_URL', 'http://localhost:8000'),
    'site_root' => envconfig('SITE_ROOT', '/'),
    'site_about' => envconfig('SITE_ABOUT', 'page/about'),
    # Set site_index if you want a custom index page
    #'site_index' => 'getting-started/introduction',

    'posts_per_page' => 10,

    # Optional: Social links that show up on the top right
    'social_links' => [
        'Twitter' => envconfig('LINK_TWITTER'),
        'Github'  => envconfig('LINK_GITHUB', 'https://github.com/erikaheidi/sponsoropensource'),
        'YouTube' => envconfig('LINK_YOUTUBE'),
        'LinkedIn' => envconfig('LINK_LINKEDIN'),
        'Twitch' => envconfig('LINK_TWITCH'),
    ],
    'profile_dir' => __DIR__ . '/content/profile',
    'templates_path' => __DIR__ . '/app/Resources/themes/portfolio',
    'GITHUB_API_TOKEN' => envconfig('GITHUB_API_TOKEN'),
];
