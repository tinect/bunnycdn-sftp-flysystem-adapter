<?php declare(strict_types=1);

namespace Tinect\Flysystem\BunnyCDN;

use League\Flysystem\Config;
use League\Flysystem\PhpseclibV3\SftpAdapter;
use League\Flysystem\PhpseclibV3\SftpConnectionProvider;
use League\Flysystem\UnableToMoveFile;

class BunnyCDNAdapter extends SftpAdapter
{
    protected string $subfolder = '';

    public function __construct($storageName, $apiKey, $endpoint, $subfolder = '')
    {
        if ($subfolder !== '') {
            $this->subfolder = rtrim($subfolder, '/') . '/';
        }

        $endpoint = \str_replace(['https://', 'http://'], '', $endpoint);

        parent::__construct(
            new SftpConnectionProvider(
            rtrim($endpoint, '/'),
            $storageName,
            $apiKey,
            null,
            null,
            22,
            false,
            30,
            10
        ),
            $this->subfolder
        );
    }

    public function move(string $source, string $destination, Config $config): void
    {
        if (!$this->fileExists($source)) {
            throw UnableToMoveFile::fromLocationTo($source, $destination);
        }

        $this->write($destination, $this->read($source), new Config());

        $this->delete($source);
    }
}
