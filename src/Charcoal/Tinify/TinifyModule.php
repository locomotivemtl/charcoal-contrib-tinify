<?php

namespace Charcoal\Tinify;

// from charcoal-app
use Charcoal\App\Module\AbstractModule;

/**
 * Tinify Module
 */
class TinifyModule extends AbstractModule
{
    const ADMIN_CONFIG = 'vendor/locomotivemtl/charcoal-contrib-tinify/config/admin.json';
    const APP_CONFIG = 'vendor/locomotivemtl/charcoal-contrib-tinify/config/config.json';

    /**
     * Setup the module's dependencies.
     *
     * @return AbstractModule
     */
    public function setup()
    {
        $container = $this->app()->getContainer();

        $tinifyServiceProvider = new TinifyServiceProvider();
        $container->register($tinifyServiceProvider);

        $tinifyConfig = $container['tinify/config'];
        $this->setConfig($tinifyConfig);

        return $this;
    }
}
