<?php

use Webkul\Theme\Enums\SectionTypeEnum;

return [
    'beauty' => [
        'name'            => 'Beauty & Nails',
        'assets_path'     => 'public/themes/shop/beauty',
        'views_path'      => 'resources/themes/beauty/views',
        'views_namespace' => 'beauty',
        'parent'          => 'default',

        'vite' => [
            'hot_file'                 => 'shop-beauty-vite.hot',
            'build_directory'          => 'themes/shop/beauty/build',
            'package_assets_directory' => 'src/Resources/assets',
        ],

        'customize' => [
            'sections' => class_exists(SectionTypeEnum::class) ? [
                SectionTypeEnum::IMAGE_CAROUSEL,
                SectionTypeEnum::PRODUCT_CAROUSEL,
                SectionTypeEnum::CATEGORY_CAROUSEL,
                SectionTypeEnum::FOOTER_LINKS,
                SectionTypeEnum::STATIC_CONTENT,
                SectionTypeEnum::SERVICES_CONTENT,
            ] : [
                'image_carousel',
                'product_carousel',
                'category_carousel',
                'footer_links',
                'static_content',
                'services_content',
            ],

            'image_cache' => [
                'templates'       => [],
                'product_images'  => [],
                'category_images' => [],
                'swatch_images'   => [],
                'placeholders'    => [],
            ],
        ],
    ],
];
