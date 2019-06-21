<?php

namespace InetStudio\SitemapPackage\Sitemap\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;

/**
 * Class SetupCommand.
 */
class SetupCommand extends BaseSetupCommand
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:sitemap-package:sitemap:setup';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Setup sitemap package';

    /**
     * Инициализация команд.
     */
    protected function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'Publish config',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => 'InetStudio\SitemapPackage\Sitemap\Providers\ServiceProvider',
                    '--tag' => 'config',
                ],
            ],
        ];
    }
}
