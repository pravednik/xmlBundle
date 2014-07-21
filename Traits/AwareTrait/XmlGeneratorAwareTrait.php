<?php

/**
 * Xml Generator/Reader Bundle
 *
 * @author Desperado <desperado@minsk-info.ru>
 */

namespace Desperado\XmlBundle\Traits\AwareTrait;

use Desperado\XmlBundle\Model\XmlGenerator;

/**
 * XmlGenerator Aware Trait
 *
 * @author Desperado <desperado@minsk-info.ru>
 */
trait XmlGeneratorAwareTrait
{
    /** @var XmlGenerator */
    protected $xmlGenerator;

    /**
     * Set XmlGenerator object
     *
     * @param XmlGenerator $xmlGenerator
     *
     * @return $this
     */
    public function setXmlGenerator(XmlGenerator $xmlGenerator)
    {
        $this->xmlGenerator = $xmlGenerator;

        return $this;
    }

    /**
     * Gets XmlGenerator object
     *
     * @return XmlGenerator
     */
    public function getXmlGenerator()
    {
        return $this->xmlGenerator;
    }
} 