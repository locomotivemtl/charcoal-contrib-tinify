<?php

namespace Charcoal\Tinify\Widget;

use Charcoal\Admin\Widget\TableWidget;

/**
 * Registries Table Widget
 */
class RegistriesTableWidget extends TableWidget
{
    /**
     * @return \Charcoal\Translator\Translation|null|string
     */
    public function label()
    {
        return $this->translator()->translate('Compression Registries');
    }

    /**
     * @return boolean
     */
    public function showLabel()
    {
        return true;
    }

    /**
     * @return boolean
     */
    public function showTableHeader()
    {
        return false;
    }

    /**
     * @return boolean
     */
    public function showObjectActions()
    {
        return false;
    }
}
