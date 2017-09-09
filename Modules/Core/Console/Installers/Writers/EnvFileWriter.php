<?php

namespace Modules\Core\Console\Installers\Writers;

use Illuminate\Filesystem\Filesystem;

class EnvFileWriter
{
    /**
     * @var Filesystem
     */
    private $finder;

    /**
     * @var array
     */
    protected $search = [
        'DB_CONNECTION=mysql',
        'DB_HOST=127.0.0.1',
        'DB_PORT=3306',
        'DB_DATABASE=homestead',
        'DB_USERNAME=homestead',
        'DB_PASSWORD=secret',
    ];

    /**
     * @var string
     */
    protected $template = '.env.example';

    /**
     * @var string
     */
    protected $file = '.env';

    /**
     * @param Filesystem $finder
     */
    public function __construct(Filesystem $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param $driver
     * @param $host
     * @param $port
     * @param $name
     * @param $username
     * @param $password
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function write($driver, $host, $port, $name, $username, $password)
    {
        $environmentFile = $this->finder->get($this->template);

        $replace = [
            "DB_CONNECTION=$driver",
            "DB_HOST=$host",
            "DB_PORT=$port",
            "DB_DATABASE=$name",
            "DB_USERNAME=$username",
            "DB_PASSWORD=$password",
        ];

        $newEnvironmentFile = str_replace($this->search, $replace, $environmentFile);

        $this->finder->put($this->file, $newEnvironmentFile);
    }
}
