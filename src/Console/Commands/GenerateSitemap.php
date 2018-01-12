<?php

namespace InetStudio\Sitemap\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $signature = 'inetstudio:sitemap:generate';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Sitemap generator';

    /**
     * Запуск команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $maps = config('sitemap.maps');

        if ($maps) {
            $sitemap = app()->make('sitemap');

            foreach ($maps as $map) {
                $mapFormat = (isset($map['options']['format'])) ? $map['options']['format'] : 'xml';
                $mapStyle = (isset($map['options']['style'])) ? $map['options']['style'] : 'sitemap';
                $limit = (isset($map['options']['limit'])) ? $map['options']['limit'] : 0;

                $except = (isset($map['except'])) ? $map['except'] : [];
                $except = collect($except)->map(function ($item) {
                    return ($item !== '/') ? trim($item, '/') : $item;
                });

                $items = [];

                foreach ($map['sources'] as $source) {
                    $items = array_merge($items, $this->getItems($source));
                }

                $items[] = [
                    'loc' => url('/'),
                    'lastmod' => Carbon::now()->toW3cString(),
                    'priority' => '1.0',
                    'freq' => 'daily',
                ];

                $items = collect($items)->filter(function ($value) use ($except) {
                    foreach ($except as $pattern) {
                        if (Str::is($pattern, trim(parse_url($value['loc'], PHP_URL_PATH), '/'))) {
                            return false;
                        }
                    }

                    return true;
                });

                $counter = 0;
                $sitemapCounter = 0;

                foreach ($items as $item) {
                    if ($limit > 0 && $counter == $limit) {
                        $sitemap->store('xml', 'sitemap-' . $sitemapCounter);
                        $sitemap->addSitemap(url('sitemap-' . $sitemapCounter . '.xml'));
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
     * @param $source
     *
     * @return mixed
     */
    private function getItems($source)
    {
        $resolver = array_wrap($source);

        $items = app()->call(
            array_shift($resolver), $resolver
        );

        return $items;
    }
}
