<?php

namespace Charcoal\Tinify\Widget;

// from charcoal-app
use Charcoal\Admin\AdminWidget;
// from local
use Charcoal\Factory\FactoryInterface;
use Charcoal\Tinify\TinifyServiceTrait;
// from pimple
use Charcoal\Tinify\Widget\UsageWidget;
use Exception;
use Pimple\Container;

/**
 * Widget for rendering data on the actual compression state of the site
 * and to allow compression of images on request.
 *
 * Image Compression Widget
 */
class CompressionDashboardWidget extends AdminWidget
{
    use TinifyServiceTrait;

    /**
     * @var FactoryInterface $widgetFactory
     */
    private $widgetFactory;

    /**
     * @return string
     */
    public function type()
    {
        return 'charcoal/tinify/widget/compression-dashboard';
    }

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
        $this->setWidgetFactory($container['widget/factory']);
    }

    /**
     * @return array
     */
    public function tinifyData()
    {
        $data = [];

        $data['key']                    = $this->tinifyService()->key();
        $data['compressions_count']     = $this->tinifyService()->compressionCount();
        $data['max_compressions']       = $this->tinifyService()->tinifyConfig()->maxCompressions();
        $data['num_compressed_files']   = $this->tinifyService()->numCompressedFiles();
        $data['num_uncompressed_files'] = $this->tinifyService()->numUncompressedFiles();
        $data['total_size']             =
            number_format(
                ($this->tinifyService()->totalSize() / 1000000),
                '2'
            ).' MB';
        $data['total_memory_saved']             =
            number_format(
                ($this->tinifyService()->totalMemorySaved() / 1000000),
                '2'
            ).' MB';
        $data['compression_percentage'] = $this->tinifyService()->compressionPercentage();

        return $data;
    }

    /**
     * @return mixed
     */
    public function usageWidget()
    {
        return $this->widgetFactory()->create(UsageWidget::class);
    }

    /**
     * @throws Exception If the widget factory dependency was not previously set / injected.
     * @return FactoryInterface
     */
    protected function widgetFactory()
    {
        if ($this->widgetFactory === null) {
            throw new Exception(
                'Widget factory was not set.'
            );
        }

        return $this->widgetFactory;
    }

    /**
     * @param FactoryInterface $factory The widget factory, to create the dashboard and secondary menu widgets.
     * @return void
     */
    private function setWidgetFactory(FactoryInterface $factory)
    {
        $this->widgetFactory = $factory;
    }
}
