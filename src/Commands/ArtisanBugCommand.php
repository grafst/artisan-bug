<?php

namespace Grafst\ArtisanBug\Commands;

use Illuminate\Console\Command;

class ArtisanBugCommand extends Command
{
    public $signature = 'bug';

    public $description = 'report a bug';

    function list_installed_packages(): array
    {
        $composer = json_decode(file_get_contents(base_path('composer.lock')), true);
        $packages = array_column($composer['packages'], 'name');
        sort($packages);
        return $packages;
    }


    function find_issue_url($packageName):string
    {
        $composerLock = json_decode(file_get_contents('composer.lock'), true);

        $issueUrl = '';
        foreach ($composerLock['packages'] as $package) {
            if ($package['name'] === $packageName) {
                if (isset($package['support']['issues'])) {
                    $issueUrl = $package['support']['issues'];
                }
                break;
            }
        }
        return $issueUrl;
    }
    function find_package_version($package):string
    {
        $composer = json_decode(file_get_contents(base_path('composer.lock')), true);
        $packages = array_column($composer['packages'], 'name');
        $versions = array_column($composer['packages'], 'version');
        $index = array_search($package, $packages);
        return $versions[$index];
    }

    function find_repo_url($packageName):string
    {
        $composerLock = json_decode(file_get_contents('composer.lock'), true);

        $repoUrl = '';
        foreach ($composerLock['packages'] as $package) {
            if ($package['name'] === $packageName) {
                if (isset($package['source']['url'])) {
                    $repoUrl = $package['source']['url'];
                }
                break;
            }
        }
        return $repoUrl;
    }

    function copy_to_clipboard($text):void{
        $os = PHP_OS;
        if (strpos($os, 'WIN') !== false) {
            // Windows
            file_put_contents('clipboard.txt', $text);
            exec('clip < clipboard.txt');
            unlink('clipboard.txt');
        } elseif (strpos($os, 'Linux') !== false) {
            // Linux (requires xclip or xsel to be installed)
            file_put_contents('clipboard.txt', $text);
            exec('xclip -selection clipboard < clipboard.txt');
            unlink('clipboard.txt');
        } elseif (strpos($os, 'Darwin') !== false) {
            // macOS
            file_put_contents('clipboard.txt', $text);
            exec('pbcopy < clipboard.txt');
            unlink('clipboard.txt');
        } else {
            echo "Operating system not supported.";
        }
    }

    function build_issue_body($package_version, $laravel_version, $php_version):string{
        $body= "### Description\n\n\n### Steps to reproduce\n\n\n### Expected behaviour\n\n\n### Actual behaviour\n\n\n### Versions\n- Laravel: $laravel_version\n- PHP: $php_version\n- Package: $package_version \n\n\n";
        return $body;
    }

    function open_url($url):void
    {
        $os = PHP_OS;

        if (strpos($os, 'WIN') !== false) {
            // Windows
            exec("start $url");
        } elseif (strpos($os, 'Linux') !== false) {
            // Linux
            exec("xdg-open $url");
        } elseif (strpos($os, 'Darwin') !== false) {
            // macOS
            exec("open $url");
        } else {
            echo "Operating system not supported.";
            echo "Open the url manually: $url";
        }
    }







    public function handle(): int
    {
        $laravel_version=app()->version();
        $php_version=phpversion();
        $this->info("Laravel version: $laravel_version");
        $this->info("PHP version: $php_version");
        $packags=$this->list_installed_packages();
        $package=$this->anticipate('Which package has the bug?',$packags);
        $this->info("Package: $package");
        $package_version=$this->find_package_version($package);
        $this->info("Package version: $package_version");
        $issue_url=$this->find_issue_url($package);
        $body=$this->build_issue_body($package_version,$laravel_version,$php_version);
        $url=$issue_url."/new?body=".urlencode($body);
        $this->open_url($url);
        return self::SUCCESS;
    }
}
