<?php

declare(strict_types=1);

return [
    'meta' => [
        'title' => 'Markdown to SPIP - Free online converter',
        'description' => 'Convert your Markdown to SPIP syntax instantly. Free online tool, no signup, privacy-friendly.',
        'keywords' => 'markdown, spip, converter, conversion, online, free',
        'og_title' => 'Markdown to SPIP - Online converter',
        'og_description' => 'Convert your Markdown to SPIP syntax instantly. Free and privacy-friendly.',
        'og_locale' => 'en_GB',
        'twitter_description' => 'Online Markdown to SPIP converter, free and tracking-free.',
        'image_alt' => 'Desktop interface of the Markdown to SPIP converter',
        'description_short' => 'Real-time online converter from Markdown to SPIP syntax.',
        'organization_description' => 'Open source project dedicated to converting Markdown to SPIP syntax. Free, no signup, no tracking.',
    ],

    'skip_to_content' => 'Skip to main content',

    'session' => [
        'expired_strong' => 'Session expired',
        'expired_text' => 'Reload the page to continue. Your text is saved.',
        'reload' => 'Reload',
    ],

    'theme' => [
        'switch_to_light_title' => 'Switch to light mode',
        'switch_to_dark_title' => 'Switch to dark mode',
        'switch_to_light_aria' => 'Enable light mode',
        'switch_to_dark_aria' => 'Enable dark mode',
    ],

    'home' => [
        'subtitle' => 'Free, instant online converter',
        'markdown_label' => 'Markdown',
        'spip_label' => 'Spip',
        'placeholder' => 'Paste or type your Markdown here...',
        'counter_unit' => '/ 100k chars',
        'clear_title' => 'Clear all text',
        'clear_aria' => 'Clear all Markdown text',
        'cleared_status' => 'Text cleared',
        'copy_title' => 'Copy the SPIP result',
        'copy_aria' => 'Copy the SPIP result to clipboard',
        'copied_status' => 'Text copied to clipboard',
        'empty_state' => 'The SPIP result will appear here',
        'output_region_aria' => 'SPIP conversion result',
    ],

    'footer' => [
        'open_source' => 'Open source project',
        'created_by' => 'Created by',
        'year_suffix' => 'in 2026',
        'github_aria' => 'Source code on GitHub',
        'stats' => 'Stats',
        'legal' => 'Legal notice',
    ],

    'help' => [
        'open_title' => 'Help',
        'open_aria' => 'Show help on supported conversions',
        'title' => 'Supported conversions',
        'close_aria' => 'Close the help dialog',
        'limit_label' => 'Limit:',
        'limit_value' => ':count characters',
    ],

    'legal' => [
        'page_title' => 'Legal notice - Markdown to SPIP',
        'back' => 'Back to the converter',
        'h1' => 'Legal notice',
        'editor' => 'Site editor',
        'editor_phone_label' => 'Phone:',
        'editor_email_label' => 'Email:',
        'editor_website_label' => 'Website:',
        'hosting' => 'Hosting',
        'hosting_intro' => 'This site is hosted by:',
        'hosting_phone_label' => 'Phone:',
        'free_software' => 'Free software',
        'free_software_intro' => 'This application is free software distributed under',
        'free_software_freedom' => 'You are free to use, modify and redistribute it under the terms of this license.',
        'free_software_source' => 'Source code available at:',
        'personal_data' => 'Personal data',
        'personal_data_intro' => 'This site collects',
        'personal_data_intro_strong' => 'no personal data',
        'personal_data_list' => [
            'No tracking cookies',
            'No traffic analytics',
            'No storage of Markdown/SPIP conversions',
            'No user accounts',
        ],
        'personal_data_online_label' => 'Online version:',
        'personal_data_online_text' => 'The text you convert is sent to the server to perform the real-time conversion, but',
        'personal_data_online_text_strong' => 'it is never stored',
        'personal_data_online_text_after' => '. No trace of your conversions is kept.',
        'personal_data_local_label' => 'Local version:',
        'personal_data_local_text' => 'If you install the application locally, all conversions stay on your machine.',
        'cookies' => 'Cookies',
        'cookies_p1' => 'This site only uses technical cookies essential to the application (Laravel session, CSRF protection). No tracking or advertising cookies are stored.',
        'cookies_p2_intro' => 'Following CNIL (French data protection authority) recommendations,',
        'cookies_p2_strong' => 'this type of cookie is exempt from consent collection',
        'cookies_p2_outro' => 'because it is strictly necessary to provide the service.',
        'responsibility' => 'Liability',
        'responsibility_p1' => 'The editor strives to ensure the accuracy and reliability of Markdown to SPIP conversions, but cannot guarantee the absolute correctness of the results.',
        'responsibility_p2' => 'The user remains responsible for verifying the generated SPIP code before using it in production.',
        'last_update' => 'Last update:',
    ],

    'stats' => [
        'page_title' => 'Statistics - Markdown to SPIP',
        'back' => 'Back to the converter',
        'h1' => 'Usage statistics',
        'kpi_visits' => 'visits',
        'kpi_conversions' => 'conversions',
        'kpi_copies' => 'copies',
        'kpi_chars' => 'characters copied',
        'chart_title' => 'Last 30 days',
        'chart_legend_visits' => 'Visits',
        'chart_legend_conversions' => 'Conversions',
        'chart_legend_copies' => 'Copies',
        'chart_label_aria' => 'Curves of visits, conversions and copies over 30 days',
        'chart_kpi_aria' => 'Key indicators',
        'chart_activity_aria' => 'Daily activity',
        'empty' => 'No data yet. Come back in a few days!',
        'footer_note' => 'No personal data is collected. Only anonymous counters are stored server-side.',
    ],

    'errors' => [
        'too_long' => 'Text too long (maximum :max characters).',
        'rate_limit' => 'Too many requests (:max/min). Please wait a few seconds.',
    ],

    'switcher' => [
        'aria' => 'Select language',
        'fr' => 'Français',
        'en' => 'English',
    ],
];
