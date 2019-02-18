<?php

namespace Charcoal\Tinify\Template\System;

// form charcoal-admin
use Charcoal\Admin\AdminTemplate;

// local dependencies
use Charcoal\Tinify\TinifyServiceTrait;

// from pimple
use Pimple\Container;

/**
 * Image Compression Template
 */
class ImageCompressionTemplate extends AdminTemplate
{
    use TinifyServiceTrait;

    /**
     * Set common dependencies (services) used in all admin templates.
     *
     * @param Container $container DI Container.
     * @return void
     */
    protected function setDependencies(Container $container)
    {
        parent::setDependencies($container);

        $this->setTinifyService($container['tinify']);
    }

    /**
     * @return array
     */
    public function tinifyData()
    {
        $data = [];

        $data['key'] = $this->tinifyService()->key();
        $data['compressions_count'] = $this->tinifyService()->compressionCount();
        $data['max_compressions'] = $this->tinifyService()->tinifyConfig()->maxCompressions();

        return $data;
    }

    /**
     * Retrieve the title of the page.
     *
     * @return \Charcoal\Translator\Translation|string|null
     */
    public function title()
    {
        if ($this->title === null) {
            $this->setTitle($this->translator()->translation('Image Compression'));
        }

        return $this->title;
    }
}
