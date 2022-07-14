<?php declare(strict_types=1);

namespace Tinect\Flysystem\BunnyCDN;

use League\Flysystem\Adapter\Polyfill\NotSupportingVisibilityTrait;
use League\Flysystem\Config;
use League\Flysystem\Sftp\SftpAdapter;

class BunnyCDNAdapter extends SftpAdapter
{
    use NotSupportingVisibilityTrait;

    protected string $subfolder = '';

    public function __construct($storageName, $apiKey, $endpoint, $subfolder = '')
    {
        if ($subfolder !== '') {
            $this->subfolder = rtrim($subfolder, '/') .  '/';
        }

        $endpoint = \str_replace(['https://', 'http://'], '', $endpoint);

        parent::__construct([
            'host' => rtrim($endpoint, '/'),
            'port' => 22,
            'username' => $storageName,
            'password' => $apiKey,
            'timeout' => 10,
        ]);

        if (!$this->has($this->subfolder)) {
            $this->createDir($this->subfolder, new Config());
        }

        $this->setRoot($this->subfolder);
        $this->setConnectionRoot();
    }

    public function rename($path, $newpath)
    {
        $this->copy($path, $newpath);

        return $this->delete($path);
    }
}
