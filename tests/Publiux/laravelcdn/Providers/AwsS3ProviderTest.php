<?php

namespace Publiux\laravelcdn\Tests;

use Illuminate\Support\Collection;
use Mockery as M;

/**
 * Class AwsS3ProviderTest.
 *
 * @category Test
 *
 * @author   Mahmoud Zalt <mahmoud@vinelab.com>
 */
class AwsS3ProviderTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->url = 'http://www.google.com';
        $this->cdn_url = 'http://my-bucket-name.www.google.com/public/css/cool/style.css';
        $this->cloudfront_url_fullscheme = 'http://cool.cloudfront.net/public/css/cool/style.css';
        $this->cloudfront_url_noscheme = '//cool.cloudfront.net/public/css/cool/style.css';
        $this->path = 'public/css/cool/style.css';
        $this->path_url = 'http://www.google.com/public/css/cool/style.css';

        $this->m_console = M::mock('Symfony\Component\Console\Output\ConsoleOutput');
        $this->m_console->shouldReceive('writeln')->atLeast(2);

        $this->m_validator = M::mock('Publiux\laravelcdn\Validators\Contracts\ProviderValidatorInterface');
        $this->m_validator->shouldReceive('validate');

        $this->m_spl_file = M::mock('Symfony\Component\Finder\SplFileInfo');
        $this->m_spl_file->shouldReceive('getPathname')->andReturn('publiux/laravelcdn/tests/Publiux/laravelcdn/AwsS3ProviderTest.php');
        $this->m_spl_file->shouldReceive('getRealPath')->andReturn(__DIR__.'/AwsS3ProviderTest.php');

        

        $this->m_s3 = M::mock('Aws\S3\S3Client');
        $this->m_s3->shouldReceive('factory')->andReturn('Aws\S3\S3Client');
        $m_command = M::mock('Aws\Command');
        $this->m_s3->shouldReceive('getCommand')
            ->andReturn($m_command);

        $m_command1 = M::mock('Aws\Result')->shouldIgnoreMissing();
        $this->m_s3->shouldReceive('listObjects')
            ->andReturn($m_command1);

        $this->m_s3->shouldReceive('execute');
    }
    
    public function setupNonCloudfrontTest()
    {
        $this->m_helper = new \Publiux\laravelcdn\CdnHelper;
        $this->m_helper = M::mock('Publiux\laravelcdn\CdnHelper');
        $this->m_helper->shouldReceive('parseUrl')
                       ->andReturn(parse_url($this->url));
                       
        $this->p_awsS3Provider = M::mock('\Publiux\laravelcdn\Providers\AwsS3Provider[connect]', 
        [
            $this->m_console,
            $this->m_validator,
            $this->m_helper,
        ]);
        
        $this->p_awsS3Provider->setS3Client($this->m_s3);
        
        $this->p_awsS3Provider->shouldReceive('connect')->andReturn(true);
    }
    
    public function setupCloudfrontFullSchemeTest($fullScheme = false)
    {
        $this->m_helper = new \Publiux\laravelcdn\CdnHelper;
        $this->m_helper = M::mock('Publiux\laravelcdn\CdnHelper');
        
        if($fullScheme)
        {
            $this->m_helper->shouldReceive('parseUrl')
                       ->andReturn(parse_url($this->cloudfront_url_fullscheme));
        }
        else
        {
            $this->m_helper->shouldReceive('parseUrl')
                       ->andReturn(parse_url($this->cloudfront_url_noscheme));
        }
                       
        $this->p_awsS3Provider = M::mock('\Publiux\laravelcdn\Providers\AwsS3Provider[connect]', 
        [
            $this->m_console,
            $this->m_validator,
            $this->m_helper,
        ]);
        
        $this->p_awsS3Provider->setS3Client($this->m_s3);
        
        $this->p_awsS3Provider->shouldReceive('connect')->andReturn(true);
    }

    public function tearDown()
    {
        M::close();
        parent::tearDown();
    }

    public function testInitializingObject()
    {
        $configurations = [
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
                        'acl'           => 'public-read',
                        'cloudfront'    => [
                            'use'     => false,
                            'cdn_url' => null,
                        ],
                        'metadata'      => [],
                        'expires'       => gmdate('D, d M Y H:i:s T', strtotime('+5 years')),
                        'cache-control' => 'max-age=2628000',
                        'version'       => null,
                    ],
                ],
            ],
        ];
        
        $this->setupNonCloudfrontTest();

        $awsS3Provider_obj = $this->p_awsS3Provider->init($configurations);

        assertInstanceOf('Publiux\laravelcdn\Providers\AwsS3Provider', $awsS3Provider_obj);
    }

    public function testUploadingAssets()
    {
        $configurations = [
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
                        'acl'           => 'public-read',
                        'cloudfront'    => [
                            'use'     => false,
                            'cdn_url' => null,
                        ],
                        'metadata'      => [],
                        'expires'       => gmdate('D, d M Y H:i:s T', strtotime('+5 years')),
                        'cache-control' => 'max-age=2628000',
                        'version'       => null,
                    ],
                ],
            ],
        ];
        
        $this->setupNonCloudfrontTest();


        $this->p_awsS3Provider->init($configurations);

        $result = $this->p_awsS3Provider->upload(new Collection([$this->m_spl_file]));

        assertEquals(true, $result);
    }

    public function testUrlGeneratorS3()
    {
        $configurations = [
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
                        'acl'           => 'public-read',
                        'cloudfront'    => [
                            'use'     => false,
                            'cdn_url' => null,
                        ],
                        'metadata'      => [],
                        'expires'       => gmdate('D, d M Y H:i:s T', strtotime('+5 years')),
                        'cache-control' => 'max-age=2628000',
                        'version'       => null,
                    ],
                ],
            ],
        ];
        
        $this->setupNonCloudfrontTest();

        $this->p_awsS3Provider->init($configurations);

        $result = $this->p_awsS3Provider->urlGenerator($this->path);

        assertEquals($this->cdn_url, $result);
    }
    
    public function testUrlGeneratorCloudFrontFullScheme()
    {
        $configurations = [
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
                        'acl'           => 'public-read',
                        'cloudfront'    => [
                            'use'     => true,
                            'cdn_url' => 'http://cool.cloudfront.net',
                        ],
                        'metadata'      => [],
                        'expires'       => gmdate('D, d M Y H:i:s T', strtotime('+5 years')),
                        'cache-control' => 'max-age=2628000',
                        'version'       => null,
                    ],
                ],
            ],
        ];
        
        $this->setupCloudfrontTest(true);

        $this->p_awsS3Provider->init($configurations);

        $result = $this->p_awsS3Provider->urlGenerator($this->path);

        assertEquals($this->cloudfront_url_fullscheme, $result);
    }
    
    public function testUrlGeneratorCloudFrontNoScheme()
    {
        $configurations = [
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
                        'acl'           => 'public-read',
                        'cloudfront'    => [
                            'use'     => true,
                            'cdn_url' => '//cool.cloudfront.net',
                        ],
                        'metadata'      => [],
                        'expires'       => gmdate('D, d M Y H:i:s T', strtotime('+5 years')),
                        'cache-control' => 'max-age=2628000',
                        'version'       => null,
                    ],
                ],
            ],
        ];
        
        $this->setupCloudfrontTest(false);

        $this->p_awsS3Provider->init($configurations);

        $result = $this->p_awsS3Provider->urlGenerator($this->path);

        assertEquals($this->cloudfront_url_noscheme, $result);
    }

    public function testEmptyUrlGenerator()
    {
        $configurations = [
            'default'   => 'AwsS3',
            'url'       => 'https://s3.amazonaws.com',
            'threshold' => 10,
            'providers' => [
                'aws' => [
                    's3' => [
                        'region'      => 'us-standard',
                        'version'     => 'latest',
                        'buckets'     => [
                            '' => '*',
                        ],
                        'acl'           => 'public-read',
                        'cloudfront'    => [
                            'use'     => false,
                            'cdn_url' => null,
                        ],
                        'metadata'      => [],
                        'expires'       => gmdate('D, d M Y H:i:s T', strtotime('+5 years')),
                        'cache-control' => 'max-age=2628000',
                        'version'       => null,
                    ],
                ],
            ],
        ];
        
        $this->setupNonCloudfrontTest();


        $this->p_awsS3Provider->init($configurations);

        $result = $this->p_awsS3Provider->urlGenerator($this->path);

        assertEquals($this->path_url, $result);
    }
}
