<?php

/**
 * Xml Generator/Reader Bundle
 *
 * @author Desperado <desperado@minsk-info.ru>
 */

namespace Desperado\XmlBundle\Traits\AwareTrait;

use Desperado\XmlBundle\Model\XmlEditor;

/**
 * XmlEditor Aware Trait
 *
 * @author Desperado <desperado@minsk-info.ru>
 */
trait XmlEditorAwareTrait
{
    /** @var XmlEditor */
    protected $xmlEditor;

    /**
     * Set XmlEditor object
     *
     * @param XmlEditor $xmlEditor
     *
     * @return $this
     */
    public function setXmlEditor(XmlEditor $xmlEditor)
    {
        $this->xmlEditor = $xmlEditor;

        return $this;
    }

    /**
     * Gets XmlEditor object
     *
     * @return XmlEditor
     */
    public function getXmlEditor()
    {
        return $this->xmlEditor;
    }
}
