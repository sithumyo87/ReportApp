<?php

return [
    'mode'                     => '',
    'format'                   => 'A4',
    'default_font_size'        => '12',
    'default_font'             => 'sans-serif',
    'margin_left'              => 10,
    'margin_right'             => 10,
    'margin_top'               => 30,
    'margin_bottom'            => 20,
    'margin_header'            => 10,
    'margin_footer'            => 5,
    'orientation'              => 'P',
    'title'                    => 'Laravel mPDF',
    'subject'                  => '',
    'author'                   => '',
    'watermark'                => '',
    'show_watermark'           => false,
    'show_watermark_image'     => false,
    'watermark_font'           => 'sans-serif',
    'display_mode'             => 'fullpage',
    'watermark_text_alpha'     => 0.1,
    'watermark_image_path'     => '',
    'watermark_image_alpha'    => 0.2,
    'watermark_image_size'     => 'D',
    'watermark_image_position' => 'P',
    'custom_font_dir'          => '',
    'custom_font_data'         => [],
    'auto_language_detection'  => false,
    'temp_dir'                 => storage_path('app'),
    'pdfa'                     => false,
    'pdfaauto'                 => false,
    'use_active_forms'         => false,

    'custom_font_dir'          => base_path('resources/fonts/'), // don't forget the trailing slash!
    'custom_font_data' => [
        'nunito' => [ // must be lowercase and snake_case
            'R'  => 'Nunito.ttf' // optional: bold-italic font
        ],
        'pyidaungsu' => [ // must be lowercase and snake_case
            'R'  => 'Pyidaungsu.ttf' // optional: bold-italic font
        ]
      // ...add as many as you want.
    ]
];
