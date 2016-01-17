<?php

namespace Publiux\laravelcdn\Tests;

use Mockery as M;

/**
 * Class ProviderFactoryTest.
 *
 * @category Test
 *
 * @author  Mahmoud Zalt <mahmoud@vinelab.com>
 */
class ProviderFactoryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->provider_factory = new \Publiux\laravelcdn\ProviderFactory();
    }

    public function tearDown()
    {
        M::close();
        parent::tearDown();
    }

    public function testCreateReturnCorrectProviderObject()
    {
        $configurations = ['default' => 'AwsS3'];

        $m_aws_s3 = M::mock('Publiux\laravelcdn\Providers\AwsS3Provider');

        \Illuminate\Support\Facades\App::shouldReceive('make')->once()->andReturn($m_aws_s3);

        $m_aws_s3->shouldReceive('init')
            ->with($configurations)
            ->once()
            ->andReturn($m_aws_s3);

        $provider = $this->provider_factory->create($configurations);

        assertEquals($provider, $m_aws_s3);
    }

    /**
     * @expectedException \Publiux\laravelcdn\Exceptions\MissingConfigurationException
     */
    public function testCreateThrowsExceptionWhenMissingDefaultConfiguration()
    {
        $configurations = ['default' => ''];

        $m_aws_s3 = M::mock('Publiux\laravelcdn\Providers\AwsS3Provider');

        \Illuminate\Support\Facades\App::shouldReceive('make')->once()->andReturn($m_aws_s3);

        $this->provider_factory->create($configurations);
    }
}
