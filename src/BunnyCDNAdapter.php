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
    }

    public function connect() {
        parent::connect();
        $this->setSubfolder();
    }

    public function rename($path, $newpath): bool
    {
        $this->copy($path, $newpath);

        return $this->delete($path);
    }

    /**
     * While SftpAdapter is ALWAYS setting visiblity there are many errors thrown.
     * We should just ignore them!
     */
    public function upload($path, $contents, Config $config): bool
    {
        try {
            return parent::upload($path, $contents, $config);
        } catch (\LogicException $e) {
            return true;
        }
    }

    private function setSubfolder(): void
    {
        if ($this->subfolder === '') {
            return;
        }

        if (!$this->has($this->subfolder)) {
            $this->createDir($this->subfolder, new Config());
        }

        $this->setRoot($this->subfolder);
        $this->setConnectionRoot();
    }
}
