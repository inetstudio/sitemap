<?php

return [

    'maps' => [
        'sitemap' => [
            'options' => [
                'format' => 'xml',
                'style' => 'sitemap',
                'limit' => 0,
            ],
            'sources' => [
                'articles' => '\InetStudio\Articles\Services\Front\ArticlesService@getSiteMapItems',
                'categories' => 'InetStudio\CategoriesPackage\Categories\Contracts\Services\Front\SitemapServiceContract@getItems',
                'ingredients' => 'InetStudio\IngredientsPackage\Ingredients\Contracts\Services\Front\SitemapServiceContract@getSiteMapItems',
                'pages' => 'InetStudio\PagesPackage\Pages\Contracts\Services\Front\SitemapServiceContract@getItems',
                'tags' => 'InetStudio\TagsPackage\Tags\Contracts\Services\Front\SitemapServiceContract@getItems',
            ],
            'except' => [],
        ],
    ],

];
