<?php

namespace Charcoal\Tinify;

// local dependencies.
use Charcoal\Tinify\Service\TinifyService;

// from pimple
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Tinify Service Provider
 */
class TinifyServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container Pimple DI container.
     * @return void
     */
    public function register(Container $container)
    {
        /**
         * @return TinifyConfig
         */
        $container['tinify/config'] = function () {
            return new TinifyConfig();
        };

        /**
         * @param Container $container Pimple DI container.
         * @return TinifyService
         */
        $container['tinify'] = function (Container $container) {
            return new TinifyService([
                'key'           => $container['config']->get('admin.apis.tinify.key'),
                'tinify/config' => $container['tinify/config']
            ]);
        };
    }
}
