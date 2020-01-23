<?php

namespace InetStudio\SitemapPackage\Sitemap\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class BindingsServiceProvider.
 */
class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * @var array
     */
    public $bindings = [
        'InetStudio\SitemapPackage\Sitemap\Contracts\Console\Commands\GenerateSitemapCommandContract' => 'InetStudio\SitemapPackage\Sitemap\Console\Commands\GenerateSitemapCommand',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
