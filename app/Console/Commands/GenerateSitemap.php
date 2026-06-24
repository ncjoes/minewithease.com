<?php
declare(strict_types=1);

namespace App\Console\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

/**
 * Class GenerateSitemap
 * @package App\Console\Commands
 */
class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap {start}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $start = $this->argument('start');
        $now = Carbon::now();
        try {
            $app_host = rtrim(route($start), '/\\');
            $this->generate($app_host);
            $this->line($now.' => Sitemap refreshed for '.$app_host);
        }
        catch (Exception $exception) {
            $this->line($exception->getMessage());
        }
    }

    /**
     * @param $app_host
     */
    private function generate($app_host)
    {
        $links = [];
        $sitemap = SitemapGenerator::create($app_host)
                                   ->hasCrawled(function (Url $url) use ($links, $app_host) {
                                       $link = rtrim($url->url, '/\\');
                                       if (!in_array($link, $links)) {
                                           $priority = 0.5;
                                           $change_frequency = Url::CHANGE_FREQUENCY_MONTHLY;
                                           if ($link == $app_host) {
                                               $priority         = 1.0;
                                               $change_frequency = Url::CHANGE_FREQUENCY_ALWAYS;
                                           } elseif (Str::contains($link, 'login')) {
                                               return 0.9;
                                           }
                                           $links[] = $link;

                                           return $url->setPriority($priority)->setChangeFrequency($change_frequency);
                                       }

                                       return null;
                                   })->getSitemap();
        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
