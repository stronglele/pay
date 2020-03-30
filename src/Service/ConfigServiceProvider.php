<?php

namespace Yansongda\Pay\Service;

use Yansongda\Pay\Contract\ConfigInterface;
use Yansongda\Pay\Contract\ServiceProviderInterface;
use Yansongda\Pay\Pay;
use Yansongda\Supports\Config;

class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @var array
     */
    private $baseConfig = [
        'log' => [
            'enable' => true,
            'file' => null,
            'identify' => 'yansongda.pay',
            'level' => 'debug',
            'type' => 'daily',
            'max_files' => 30,
        ],
        'http' => [
            'timeout' => 5.0,
            'connect_timeout' => 3.0,
        ],
        // 是否处于命令行模式
        'cli' => false,
        // 当前支付体系
        'mode' => Pay::MODE_NORMAL,
    ];

    /**
     * @var array
     */
    private $userConfig = [];

    /**
     * {@inheritdoc}
     */
    public function prepare(array $data): void
    {
        $this->userConfig = $data;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Yansongda\Pay\Exception\ContainerDependencyException
     * @throws \Yansongda\Pay\Exception\ContainerException
     * @throws \Yansongda\Pay\Exception\ContainerNotFoundException
     * @throws \Yansongda\Pay\Exception\ServiceNotFoundException
     */
    public function register(Pay $pay): void
    {
        $service = function () {
            $config = array_replace_recursive($this->baseConfig, $this->userConfig);

            return new Config($config);
        };

        $pay::set(ConfigInterface::class, $service);
    }
}
