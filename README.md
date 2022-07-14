# Flysystem Adapter for BunnyCDN Storage with SFTP

[![Test V3](https://github.com/tinect/bunnycdn-sftp-flysystem-adapter/actions/workflows/test_v3.yml/badge.svg)](https://github.com/tinect/bunnycdn-sftp-flysystem-adapter/actions/workflows/test_v3.yml)

This adapter supports Flysystem with version 3 for BunnyCDN.  

## Installation

```bash
composer require tinect/bunnycdn-sftp-flysystem-adapter:^3.0
```

## Usage

```php
use League\Flysystem\Filesystem;
use Tinect\Flysystem\BunnyCDN\BunnyCDNAdapter;

$client = new BunnyCDNAdapter('storageName', 'api-key-or-ftp-passwort', 'storage.bunnycdn.com', 'optionalSubfolder');
$filesystem = new Filesystem($client);
```
