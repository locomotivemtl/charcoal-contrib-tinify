<?php

namespace Charcoal\Tinify\Template\System;

// form charcoal-admin
use Charcoal\Admin\AdminTemplate;

// local dependencies
use Charcoal\Tinify\TinifyServiceTrait;

// from pimple
use Pimple\Container;
use Psr\Http\Message\RequestInterface;

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
     * Template's init method is called automatically from `charcoal-app`'s Template Route.
     *
     * For admin templates, initializations is:
     *
     * - to start a session, if necessary
     * - to authenticate
     * - to initialize the template data with the PSR Request object
     *
     * @param RequestInterface $request The request to initialize.
     * @return boolean
     * @see \Charcoal\App\Route\TemplateRoute::__invoke()
     */
    public function init(RequestInterface $request)
    {
        $this->feedbacks = array_merge($this->feedbacks, $this->tinifyService()->feedbacks());

        return parent::init($request);
    }

    /**
     * @return array
     */
    public function tinifyData()
    {
        $data = [];

        $data['key']                = $this->tinifyService()->key();
        $data['compressions_count'] = $this->tinifyService()->compressionCount();
        $data['max_compressions']   = $this->tinifyService()->tinifyConfig()->maxCompressions();
        $data['total_size']         =
            number_format(
                ($this->tinifyService()->totalSize() / 1000000),
                '2'
            ).' MB';

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
