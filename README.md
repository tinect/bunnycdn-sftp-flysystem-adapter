# Flysystem Adapter for BunnyCDN Storage with SFTP

> :warning: Check HTTP-flysystem for 10x better performance: https://github.com/tinect/flysystem-bunnycdn/tree/v1 
> ```
> composer require tinect/flysystem-bunnycdn
> ```
> :warning: 

[![Test V1](https://github.com/tinect/bunnycdn-sftp-flysystem-adapter/actions/workflows/test_v1.yml/badge.svg)](https://github.com/tinect/bunnycdn-sftp-flysystem-adapter/actions/workflows/test_v1.yml)

This adapter supports Flysystem with version 1 for BunnyCDN.  

## Installation

```bash
composer require tinect/bunnycdn-sftp-flysystem-adapter:^1.0
```

## Usage

```php
use League\Flysystem\Filesystem;
use Tinect\Flysystem\BunnyCDN\BunnyCDNAdapter;

$client = new BunnyCDNAdapter('storageName', 'api-key-or-ftp-passwort', 'storage.bunnycdn.com', 'optionalSubfolder');
$filesystem = new Filesystem($client);
```
