<?php
declare(strict_types=1);

namespace Tests\Unit;

use Longman\LaravelLodash\Redis\RedisManager;

class RedisTest extends TestCase
{
    use InteractsWithRedis;

    const PHPREDIS_OPT_SERIALIZER = 1;
    const PHPREDIS_OPT_PREFIX = 2;
    const PHPREDIS_OPT_READ_TIMEOUT = 3;
    const PHPREDIS_OPT_SCAN = 4;

    const PHPREDIS_SERIALIZER_NONE = 0;
    const PHPREDIS_SERIALIZER_PHP = 1;
    const PHPREDIS_SERIALIZER_IGBINARY = 2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpRedis();

        if (! isset($this->redis['phpredis'])) {
            $this->markTestSkipped('PhpRedis should be enabled to run the tests');
        }

        if (! isset($this->redis['predis'])) {
            $this->markTestSkipped('Predis should be enabled to run the tests');
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tearDownRedis();
    }

    /** @test */
    public function it_should_return_instance_with_custom_serializer()
    {
        $redis = $this->createConnection('phpredis', [
            'default' => [
                'serializer' => 'igbinary',
            ],
        ]);
        $client = $redis->connection()->client();

        $this->assertEquals($client->getOption(self::PHPREDIS_OPT_SERIALIZER), self::PHPREDIS_SERIALIZER_IGBINARY);
    }

    public function createConnection(string $driver, array $config = []): RedisManager
    {
        $host = getenv('REDIS_HOST') ?: '127.0.0.1';
        $port = getenv('REDIS_PORT') ?: 6379;
        $defaultConfig = [
            'cluster' => false,
            'default' => [
                'host'         => $host,
                'port'         => $port,
                'database'     => 5,
                'options'      => ['prefix' => 'lodash:'],
                'timeout'      => 0.5,
                'read_timeout' => 1.5,
            ],
        ];
        $options = array_replace_recursive($defaultConfig, $config);

        $redis = new RedisManager($this->app, $driver, $options);

        return $redis;
    }
}
