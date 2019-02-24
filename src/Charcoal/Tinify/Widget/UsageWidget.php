<?php

namespace Charcoal\Tinify\Widget;

use Charcoal\Admin\Widget\Graph\AbstractGraphWidget;
use Charcoal\Tinify\TinifyServiceTrait;
use Pimple\Container;

/**
 * Class UsageWidget
 */
class UsageWidget extends AbstractGraphWidget
{
    use TinifyServiceTrait;

    /**
     * @return string
     */
    public function type()
    {
        return 'charcoal/tinify/widget/usage';
    }

    /**
     * Set common dependencies used in all admin widgets.
     *
     * @param  Container $container DI Container.
     * @return void
     */
    protected function setDependencies(Container $container)
    {
        parent::setDependencies($container);

        $this->setTinifyService($container['tinify']);
    }

    /**
     * @return array Categories structure.
     */
    public function categories()
    {
        return [];
    }

    /**
     * @return array Series structure.
     */
    public function series()
    {
        return [
            [
                'name'   => 'Compression count',
                'type'   => 'gauge',
                'min' => 0,
                'max' =>  $this->tinifyService()->tinifyConfig()->maxCompressions(),
                'data'   => [
                    [
                        'name' => 'Current',
                        'value' => $this->tinifyService()->compressionCount()
                    ]
                ]
            ]
        ];
    }
}
