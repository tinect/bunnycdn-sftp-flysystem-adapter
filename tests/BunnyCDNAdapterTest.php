<?php declare(strict_types=1);

use League\Flysystem\AdapterTestUtilities\FilesystemAdapterTestCase;
use League\Flysystem\Config;
use League\Flysystem\Visibility;
use Tinect\Flysystem\BunnyCDN\BunnyCDNAdapter;

class BunnyCDNAdapterTest extends FilesystemAdapterTestCase
{
    private const TEST_FILE_CONTENTS = 'testing1982';

    public static function setUpBeforeClass(): void
    {
        $_SERVER['subfolder'] = 'ci/v3/' . time() . bin2hex(random_bytes(10));
    }

    public static function tearDownAfterClass(): void
    {
        self::createFilesystemAdapter('')->deleteDirectory($_SERVER['subfolder']);
    }

    public function testFileProcesses(): void
    {
        $adapter = $this->adapter();

        static::assertFalse(
            $adapter->fileExists('testing/test.txt')
        );

        $adapter->write('testing/test.txt', self::TEST_FILE_CONTENTS, new Config());

        static::assertTrue(
            $adapter->fileExists('testing/test.txt')
        );

        static::assertTrue(
            $adapter->fileExists('/testing/test.txt')
        );

        static::assertEquals(
            self::TEST_FILE_CONTENTS,
            $adapter->read('/testing/test.txt')
        );

        $adapter->delete('testing/test.txt');

        static::assertFalse(
            $adapter->fileExists('testing/test.txt')
        );
    }

    /**
     * @test
     */
    public function writingAFileWithAnEmptyStream(): void
    {
        static::markTestSkipped('BunnyCDN can not write empty streams? oO');
    }

    /**
     * @test
     */
    public function settingVisibility(): void
    {
        static::markTestSkipped('BunnyCDN does not support visibility');
    }

    /**
     * @test
     * We removed the check of visibility, BunnyCDN bunnyCDN does not support visibility
     */
    public function copyingAFile(): void
    {
        $this->runScenario(function (): void {
            $adapter = $this->adapter();
            $adapter->write(
                'source.txt',
                'contents to be copied',
                new Config([Config::OPTION_VISIBILITY => Visibility::PUBLIC])
            );

            $adapter->copy('source.txt', 'destination.txt', new Config());

            $this->assertTrue($adapter->fileExists('source.txt'));
            $this->assertTrue($adapter->fileExists('destination.txt'));
            //$this->assertEquals(Visibility::PUBLIC, $adapter->visibility('destination.txt')->visibility());
            $this->assertEquals('contents to be copied', $adapter->read('destination.txt'));
        });
    }

    /**
     * @test
     * We removed the check of visibility, BunnyCDN bunnyCDN does not support visibility
     */
    public function copyingAFileAgain(): void
    {
        $this->runScenario(function (): void {
            $adapter = $this->adapter();
            $adapter->write(
                'source.txt',
                'contents to be copied',
                new Config([Config::OPTION_VISIBILITY => Visibility::PUBLIC])
            );

            $adapter->copy('source.txt', 'destination.txt', new Config());

            $this->assertTrue($adapter->fileExists('source.txt'));
            $this->assertTrue($adapter->fileExists('destination.txt'));
            /*$this->assertEquals(Visibility::PUBLIC, $adapter->visibility('destination.txt')->visibility());*/
            $this->assertEquals('contents to be copied', $adapter->read('destination.txt'));
        });
    }

    /**
     * @test
     * We removed the check of visibility, BunnyCDN bunnyCDN does not support visibility
     */
    public function movingAFile(): void
    {
        $this->runScenario(function (): void {
            $adapter = $this->adapter();
            $adapter->write(
                'source.txt',
                'contents to be copied',
                new Config([Config::OPTION_VISIBILITY => Visibility::PUBLIC])
            );
            $adapter->move('source.txt', 'destination.txt', new Config());
            $this->assertFalse(
                $adapter->fileExists('source.txt'),
                'After moving a file should no longer exist in the original location.'
            );
            $this->assertTrue(
                $adapter->fileExists('destination.txt'),
                'After moving, a file should be present at the new location.'
            );
            /*$this->assertEquals(Visibility::PUBLIC, $adapter->visibility('destination.txt')->visibility());*/
            $this->assertEquals('contents to be copied', $adapter->read('destination.txt'));
        });
    }

    protected static function createFilesystemAdapter(?string $subfolder = null): BunnyCDNAdapter
    {
        if (!isset($_SERVER['STORAGENAME'], $_SERVER['APIKEY'])) {
            throw new RuntimeException('Running test without real data is currently not possible');
        }

        if ($subfolder === null && isset($_SERVER['subfolder'])) {
            $subfolder = $_SERVER['subfolder'];
        }

        return new BunnyCDNAdapter($_SERVER['STORAGENAME'], $_SERVER['APIKEY'], 'storage.bunnycdn.com', $subfolder);
    }
}
