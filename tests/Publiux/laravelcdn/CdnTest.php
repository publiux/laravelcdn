<?php

namespace Publiux\laravelcdn\Tests;

use Illuminate\Support\Collection;
use Mockery as M;

/**
 * Class CdnTest.
 *
 * @category Test
 *
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 */
class CdnTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->m_spl_file_info = M::mock('Symfony\Component\Finder\SplFileInfo');
    }

    public function tearDown()
    {
        M::close();
        parent::tearDown();
    }

    public function testPushCommandReturnTrue()
    {
        $this->m_asset = M::mock('Publiux\laravelcdn\Contracts\AssetInterface');
        $this->m_asset->shouldReceive('init')
            ->once()
            ->andReturn($this->m_asset);
        $this->m_asset->shouldReceive('setAssets')
            ->once();

        $this->m_asset->shouldReceive('getAssets')
            ->once()
            ->andReturn(new Collection());

        $this->m_finder = M::mock('Publiux\laravelcdn\Contracts\FinderInterface');
        $this->m_finder->shouldReceive('read')
            ->with($this->m_asset)
            ->once()
            ->andReturn(new Collection());

        $this->m_provider = M::mock('Publiux\laravelcdn\Providers\Provider');
        $this->m_provider->shouldReceive('upload')
            ->once()
            ->andReturn(true);

        $this->m_provider_factory = M::mock('Publiux\laravelcdn\Contracts\ProviderFactoryInterface');
        $this->m_provider_factory->shouldReceive('create')
            ->once()
            ->andReturn($this->m_provider);

        $this->m_helper = M::mock('Publiux\laravelcdn\Contracts\CdnHelperInterface');
        $this->m_helper->shouldReceive('getConfigurations')
            ->once()
            ->andReturn([]);

        $this->cdn = new \Publiux\laravelcdn\Cdn(
            $this->m_finder,
            $this->m_asset,
            $this->m_provider_factory,
            $this->m_helper);

        $result = $this->cdn->push();

        assertEquals($result, true);
    }

    /**
     * Integration Test.
     */
    public function testPushCommand()
    {
        $configuration_file = [
            'bypass'    => false,
            'default'   => 'AwsS3',
            'url'       => 'https://s3.amazonaws.com',
            'threshold' => 10,
            'providers' => [
                'aws' => [
                    's3' => [
                        'region'      => 'us-standard',
                        'version'     => 'latest',
                        'buckets'     => [
                            'my-bucket-name' => '*',
                        ],
                        'acl'         => 'public-read',
                        'cloudfront'  => [
                            'use'     => false,
                            'cdn_url' => '',
                        ],
                        'metadata' => [],

                        'expires' => gmdate('D, d M Y H:i:s T', strtotime('+5 years')),

                        'cache-control' => 'max-age=2628000',

                        'version' => '',
                    ],
                ],
            ],
            'include'   => [
                'directories' => [__DIR__],
                'extensions'  => [],
                'patterns'    => [],
            ],
            'exclude'   => [
                'directories' => [],
                'files'       => [],
                'extensions'  => [],
                'patterns'    => [],
                'hidden'      => true,
            ],
        ];

        $m_consol = M::mock('Symfony\Component\Console\Output\ConsoleOutput');
        $m_consol->shouldReceive('writeln')
            ->atLeast(1);

        $finder = new \Publiux\laravelcdn\Finder($m_consol);

        $asset = new \Publiux\laravelcdn\Asset();

        $provider_factory = new \Publiux\laravelcdn\ProviderFactory();

        $m_config = M::mock('Illuminate\Config\Repository');
        $m_config->shouldReceive('get')
            ->with('cdn')
            ->once()
            ->andReturn($configuration_file);

        $helper = new \Publiux\laravelcdn\CdnHelper($m_config);

        $m_console = M::mock('Symfony\Component\Console\Output\ConsoleOutput');
        $m_console->shouldReceive('writeln')
            ->atLeast(2);

        $m_validator = M::mock('Publiux\laravelcdn\Validators\Contracts\ProviderValidatorInterface');
        $m_validator->shouldReceive('validate');

        $m_helper = M::mock('Publiux\laravelcdn\CdnHelper');

        $m_spl_file = M::mock('Symfony\Component\Finder\SplFileInfo');
        $m_spl_file->shouldReceive('getPathname')
            ->andReturn('Publiux\laravelcdn/tests/Publiux/laravelcdn/AwsS3ProviderTest.php');
        $m_spl_file->shouldReceive('getRealPath')
            ->andReturn(__DIR__.'/AwsS3ProviderTest.php');

        // partial mock
        $p_aws_s3_provider = M::mock('\Publiux\laravelcdn\Providers\AwsS3Provider[connect]', 
        [
            $m_console,
            $m_validator,
            $m_helper,
        ]);

        $m_s3 = M::mock('Aws\S3\S3Client')->shouldIgnoreMissing();
        $m_s3->shouldReceive('factory')
            ->andReturn('Aws\S3\S3Client');
        $m_command = M::mock('Aws\Command');
        $m_s3->shouldReceive('getCommand')
            ->andReturn($m_command);
        $m_s3->shouldReceive('execute');

        $p_aws_s3_provider->setS3Client($m_s3);

        $p_aws_s3_provider->shouldReceive('connect')
            ->andReturn(true);

        \Illuminate\Support\Facades\App::shouldReceive('make')
            ->once()
            ->andReturn($p_aws_s3_provider);

        $cdn = new \Publiux\laravelcdn\Cdn($finder,
            $asset,
            $provider_factory,
            $helper
        );

        $result = $cdn->push();

        assertEquals($result, true);
    }
}
