<?php

/**
 * Xml Generator/Reader Bundle
 *
 * @author Desperado <desperado@minsk-info.ru>
 */

namespace Desperado\XmlBundle\Model;

/**
 * Base XML worker class
 *
 * @author Desperado <desperado@minsk-info.ru>
 */
abstract class XmlAbstract
{
    /**
     * Disables external entities loading
     *
     * @var boolean
     */
    protected $disableEntityLoader;

    /**
     * Disables libxml internal errors handling and allows user to fetch error information as needed
     *
     * @var boolean
     */
    protected $useInternalErrors;

    /**
     * Disable external entities loading
     *
     * @param boolean $disableEntityLoaderFlag
     *
     * @return $this
     */
    public function setDisableEntityLoader($disableEntityLoaderFlag)
    {
        $this->disableEntityLoader = (boolean) $disableEntityLoaderFlag;

        libxml_disable_entity_loader($this->disableEntityLoader);

        return $this;
    }

    /**
     * Disables libxml internal errors handling and allows user to fetch error information as needed
     *
     * @param boolean $useInternalErrorsFlag
     *
     * @return $this
     */
    public function setUseInternalErrors($useInternalErrorsFlag)
    {
        $this->useInternalErrors = (boolean) $useInternalErrorsFlag;

        libxml_use_internal_errors($this->useInternalErrors);

        return $this;
    }

    /**
     * Gets if external enties are being loaded
     *
     * @return boolean
     */
    public function getDisableEntityLoader()
    {
        return (boolean) $this->disableEntityLoader;
    }

    /**
     * Gets LibXML errors handling mode (internal or user)
     *
     * @return boolean
     */
    public function getUseInternalErrors()
    {
        return (boolean) $this->useInternalErrors;
    }

    /**
     * Checks XML string for validity
     *
     * @param string $xmlString
     *
     * @return boolean
     */
    public function isXml($xmlString)
    {
        $previousValue = libxml_use_internal_errors(true);

        $simpleXml = simplexml_load_string($xmlString);

        $this->setUseInternalErrors($previousValue);

        return false !== $simpleXml;
    }

    /**
     * Removes XML declaration
     *
     * @param string $xmlString
     *
     * @return string
     */
    public function removeAnnouncement($xmlString)
    {
        $matches = [];

        preg_match('/<?xml version="(.*)" encoding="(.*)"?>(.*)$/isuU', $xmlString, $matches);

        return isset($matches[3]) ? $matches[3] : $xmlString;
    }

    /**
     * Checks XML element for valid name
     *
     * @param string $elementName Имя элемента
     *
     * @return boolean
     */
    protected function isValidElementName($elementName)
    {
        $pattern = '/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i';

        return (boolean) preg_match($pattern, $elementName);
    }

} 
