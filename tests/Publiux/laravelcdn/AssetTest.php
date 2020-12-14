<?php

namespace Publiux\laravelcdn\Tests;

use Mockery as M;

/**
 * Class AssetTest.
 *
 * @category Test
 *
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 */
class AssetTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->asset = new \Publiux\laravelcdn\Asset();
    }

    public function tearDown()
    {
        M::close();
        parent::tearDown();
    }

    public function testInitReturningAssetObject()
    {
        $dir = 'foo';

        $result = $this->asset->init([
            'include' => [
                'directories' => $dir,
            ],
        ]);

        // check the returned object is of type Publiux\laravelcdn\Asset
        $this->assertEquals($result, $this->asset);
    }

    public function testIncludedDirectories()
    {
        $dir = 'foo';

        $this->asset->init([
            'include' => [
                'directories' => $dir,
            ],
        ]);

        $result = $this->asset->getIncludedDirectories();

        $this->assertEquals($result, $dir);
    }

    public function testIncludedExtensions()
    {
        $ext = 'foo';

        $this->asset->init([
            'include' => [
                'extensions' => $ext,
            ],
        ]);

        $result = $this->asset->getIncludedExtensions();

        $this->assertEquals($result, $ext);
    }

    public function testIncludedPatterns()
    {
        $pat = 'foo';

        $this->asset->init([
            'include' => [
                'patterns' => $pat,
            ],
        ]);

        $result = $this->asset->getIncludedPatterns();

        $this->assertEquals($result, $pat);
    }

    public function testExcludedDirectories()
    {
        $dir = 'foo';

        $this->asset->init([
            'exclude' => [
                'directories' => $dir,
            ],
        ]);

        $result = $this->asset->getExcludedDirectories();

        $this->assertEquals($result, $dir);
    }

    public function testExcludedFiles()
    {
        $dir = 'foo';

        $this->asset->init([
            'exclude' => [
                'files' => $dir,
            ],
        ]);

        $result = $this->asset->getExcludedFiles();

        $this->assertEquals($result, $dir);
    }

    public function testExcludedExtensions()
    {
        $dir = 'foo';

        $this->asset->init([
            'exclude' => [
                'extensions' => $dir,
            ],
        ]);

        $result = $this->asset->getExcludedExtensions();

        $this->assertEquals($result, $dir);
    }

    public function testExcludedPatterns()
    {
        $dir = 'foo';

        $this->asset->init([
            'exclude' => [
                'patterns' => $dir,
            ],
        ]);

        $result = $this->asset->getExcludedPatterns();

        $this->assertEquals($result, $dir);
    }

    public function testExcludedHidden()
    {
        $bol = true;

        $this->asset->init([
            'exclude' => [
                'hidden' => $bol,
            ],
        ]);

        $result = $this->asset->getExcludeHidden();

        $this->assertEquals($result, $bol);
    }
}
