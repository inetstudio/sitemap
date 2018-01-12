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
                'categories' => '\InetStudio\Categories\Services\Front\CategoriesService@getSiteMapItems',
                'ingredients' => '\InetStudio\Ingredients\Services\Front\IngredientsService@getSiteMapItems',
                'pages' => '\InetStudio\Pages\Services\Front\PagesService@getSiteMapItems',
                'tags' => '\InetStudio\Tags\Services\Front\TagsService@getSiteMapItems',
            ],
            'except' => [],
        ],
    ],

];
