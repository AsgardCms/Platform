<?php

namespace Modules\Core\Downloader;

use GuzzleHttp\Client;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

class Downloader
{
    /**
     * @var OutputInterface
     */
    private $output;
    /**
     * @var string
     */
    private $package;
    /**
     * @var Filesystem
     */
    private $finder;
    /**
     * @var string
     */
    private $tagName;
    /**
     * @var string
     */
    private $branchName;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
        $this->finder = app(Filesystem::class);
    }

    public function download($package)
    {
        if (! class_exists('ZipArchive')) {
            throw new RuntimeException('The Zip PHP extension is not installed. Please install it and try again.');
        }

        $this->package = $package;
        $latestVersionUrl = $this->getLatestVersionUrl();

        $this->output->writeln("<info>Downloading Module [{$this->package} {$this->tagName}{$this->branchName}]</info>");

        $directory = config('modules.paths.modules') . '/' . $this->extractPackageNameFrom($package);

        if ($this->finder->isDirectory($directory) === true) {
            $this->output->writeln("<error>The folder [Modules/{$this->extractPackageNameFrom($package)}] already exists.</error>");

            return;
        }

        $this->downloadFile($zipFile = $this->makeFilename(), $latestVersionUrl)
            ->extract($zipFile, $directory)
            ->cleanUp($zipFile);

        $this->output->writeln('<comment></comment>');

        $this->output->writeln("<comment>Module downloaded in the Modules/{$this->extractPackageNameFrom($package)} directory!</comment>");
    }

    /**
     * Extract the zip file into the given directory.
     * @param  string $zipFile
     * @param  string $directory
     * @return $this
     */
    protected function extract($zipFile, $directory)
    {
        $modulesPath = config('modules.paths.modules');

        $archive = new ZipArchive();
        $archive->open($zipFile);
        $archive->extractTo($modulesPath);

        $original = $modulesPath . '/' . $archive->getNameIndex(0);

        $this->finder->move($original, $directory);
        $archive->close();

        return $this;
    }

    /**
     * Download the temporary Zip to the given file.
     * @param  string $zipFile
     * @param string $latestVersionUrl
     * @return $this
     */
    protected function downloadFile($zipFile, $latestVersionUrl)
    {
        $progress = new ProgressBar($this->output);
        $progress->setFormat('[%bar%] %elapsed:6s%');

        $response = (new Client)->get($latestVersionUrl, [
            'progress' => function ($downloadTotal, $downloadedBytes, $uploadTotal, $uploadedBytes) use ($progress) {
                $progress->advance();
            },
        ]);
        file_put_contents($zipFile, $response->getBody());

        $progress->finish();

        return $this;
    }

    /**
     * Clean-up the Zip file.
     * @param  string $zipFile
     * @return $this
     */
    protected function cleanUp($zipFile)
    {
        @chmod($zipFile, 0777);
        @unlink($zipFile);

        return $this;
    }

    /**
     * Generate a random temporary filename.
     * @return string
     */
    protected function makeFilename()
    {
        return getcwd() . '/asgardcms_' . md5(time() . uniqid()) . '.zip';
    }

    private function getLatestVersionUrl()
    {
        if ($this->branchName !== null) {
            return "https://github.com/{$this->package}/archive/{$this->branchName}.zip";
        }
        $client = new Client([
            'base_uri' => 'https://api.github.com',
            'timeout'  => 2.0,
        ]);

        $githubReleases = $client->get("repos/{$this->package}/releases/latest", ['http_errors' => false]);

        if ($githubReleases->getStatusCode() === 404) {
            throw new \Exception('No releases were found for this package.');
        }
        $response = \GuzzleHttp\json_decode($githubReleases->getBody()->getContents());

        $this->tagName = $response->tag_name;

        return $response->zipball_url;
    }

    private function extractPackageNameFrom($package)
    {
        if (str_contains($package, '/') === false) {
            throw new \Exception('You need to use vendor/name structure');
        }

        return studly_case(substr(strrchr($package, '/'), 1));
    }

    public function forBranch($branchName)
    {
        $this->branchName = $branchName;

        return $this;
    }
}
