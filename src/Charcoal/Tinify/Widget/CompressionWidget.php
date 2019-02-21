<?php

namespace Charcoal\Tinify\Widget;

use Charcoal\Admin\AdminWidget;

/**
 * Class CompressionWidget
 */
class CompressionWidget extends AdminWidget
{
    /**
     * @return string
     */
    public function type()
    {
        return 'charcoal/tinify/widget/compression';
    }
}
