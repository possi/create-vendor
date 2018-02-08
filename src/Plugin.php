<?php

namespace Meeva\CreateVendor;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;

class Plugin implements PluginInterface, Capable
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $composer->getRepositoryManager()->addRepository(new Repository($io, self::createConfig()));
    }

    public function getCapabilities()
    {
        return array(
            'Composer\Plugin\Capability\CommandProvider' => 'Meeva\CreateVendor\CommandProvider',
        );
    }

    public static function createConfig()
    {
        $config = Factory::createConfig();

        $path = $config->get('home') . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . 'create-vendor';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $config->merge(array(
            'config' => array(
                'createVendorHome' => $path,
            ),
        ));

        return $config;
    }
}
