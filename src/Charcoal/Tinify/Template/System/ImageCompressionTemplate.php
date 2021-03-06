<?php

namespace Charcoal\Tinify\Template\System;

// form charcoal-admin
use Charcoal\Admin\AdminTemplate;

// local dependencies
use Charcoal\Admin\Widget\TableWidget;
use Charcoal\Tinify\TinifyServiceTrait;

// from pimple
use Charcoal\Tinify\Widget\CompressionDashboardWidget;
use Charcoal\Tinify\Widget\RegistriesTableWidget;
use Charcoal\Tinify\Widget\UsageWidget;
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
     * @return mixed
     */
    public function registryWidget()
    {
        return $this->widgetFactory()->create(RegistriesTableWidget::class)->setData([
            'type' => 'charcoal/tinify/widget/registries-table',
            'obj_type' => $this->tinifyService()->tinifyConfig()->registryObject()
        ]);
    }

    /**
     * @return mixed
     */
    public function CompressionDashboardWidget()
    {
        $widget = $this->widgetFactory()->create(CompressionDashboardWidget::class);

        return $widget;
    }

    /**
     * @return mixed
     */
    public function usageWidget()
    {
        return $this->widgetFactory()->create(UsageWidget::class);
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
