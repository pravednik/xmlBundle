<?php

/**
 * Xml Generator/Reader Bundle
 *
 * @author Desperado <desperado@minsk-info.ru>
 */

namespace Desperado\XmlBundle\Traits\AwareTrait;

use Desperado\XmlBundle\Model\XmlReader;

/**
 * XmlReader Aware Trait
 *
 * @author Desperado <desperado@minsk-info.ru>
 */
trait XmlReaderAwareTrait
{
    /** @var XmlReader */
    protected $xmlReader;

    /**
     * Set XmlReader object
     *
     * @param XmlReader $xmlReader
     *
     * @return $this
     */
    public function setXmlReader(XmlReader $xmlReader)
    {
        $this->xmlReader = $xmlReader;

        return $this;
    }

    /**
     * Gets XmlReader object
     *
     * @return XmlReader
     */
    public function getXmlReader()
    {
        return $this->xmlReader;
    }
}
