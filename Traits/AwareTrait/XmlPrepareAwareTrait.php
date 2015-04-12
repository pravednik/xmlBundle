<?php

/**
 * Xml Generator/Reader Bundle
 *
 * @author Desperado <desperado@minsk-info.ru>
 */

namespace Desperado\XmlBundle\Traits\AwareTrait;

use Desperado\XmlBundle\Model\XmlPrepare;

/**
 * XmlPrepare Aware Trait
 *
 * @author Desperado <desperado@minsk-info.ru>
 */
trait XmlPrepareAwareTrait
{
    /** @var XmlPrepare */
    protected $xmlPrepare;

    /**
     * Set XmlPrepare object
     *
     * @param XmlPrepare $xmlPrepare
     *
     * @return $this
     */
    public function setXmlPrepare(XmlPrepare $xmlPrepare)
    {
        $this->xmlPrepare = $xmlPrepare;

        return $this;
    }

    /**
     * Gets XmlPrepare object
     *
     * @return XmlPrepare
     */
    public function getXmlPrepare()
    {
        return $this->xmlPrepare;
    }
}
