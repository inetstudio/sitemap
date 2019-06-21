<?php

namespace InetStudio\SitemapPackage\Sitemap\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class GenerateSitemap.
 */
class GenerateSitemap extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $signature = 'inetstudio:sitemap-package:sitemap:generate';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Sitemap generator';

    /**
     * Запуск команды.
     *
     * @throws BindingResolutionException
     */
    public function handle(): void
    {
        $maps = config('sitemap.maps');

        if ($maps) {
            $sitemap = app()->make('sitemap');

            foreach ($maps as $map) {
                $mapFormat = $map['options']['format'] ?? 'xml';
                $mapStyle = $map['options']['style'] ?? 'sitemap';
                $limit = $map['options']['limit'] ?? 0;

                $items = $this->getItems($map);

                $counter = 0;
                $sitemapCounter = 0;

                foreach ($items as $item) {
                    if ($limit > 0 && $counter == $limit) {
                        $sitemap->store('xml', 'sitemap-'.$sitemapCounter);
                        $sitemap->addSitemap(url('sitemap-'.$sitemapCounter.'.xml'));
                        $sitemap->model->resetItems();
                        $counter = 0;
                        $sitemapCounter++;
                    }

                    $sitemap->add($item['loc'], $item['lastmod'], $item['priority'], $item['freq']);

                    $counter++;
                }

                $mapFormat = ($sitemapCounter > 0) ? 'sitemapindex' : $mapFormat;

                $sitemap->store($mapFormat, $mapStyle);
            }
        }
    }

    /**
     * Получаем материалы.
     *
     * @param $map
     *
     * @return static
     */
    private function getItems($map)
    {
        $except = $map['except'] ?? [];
        $sources = $map['sources'] ?? [];

        $except = collect($except)->map(function ($item) {
            return ($item !== '/') ? trim($item, '/') : $item;
        });

        $items = [];

        foreach ($sources as $source) {
            $items = array_merge($items, $this->getItemsFromSource($source));
        }

        $items[] = [
            'loc' => url('/'),
            'lastmod' => Carbon::now()->toW3cString(),
            'priority' => '1.0',
            'freq' => 'daily',
        ];

        return collect($items)->filter(function ($value) use ($except) {
            foreach ($except as $pattern) {
                if (Str::is($pattern, trim(parse_url($value['loc'], PHP_URL_PATH), '/'))) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Получаем материалы из источника.
     *
     * @param $source
     *
     * @return mixed
     */
    private function getItemsFromSource($source)
    {
        $resolver = Arr::wrap($source);

        $items = app()->call(
            array_shift($resolver), $resolver
        );

        return $items;
    }
}
