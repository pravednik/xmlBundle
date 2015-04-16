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

        libxml_use_internal_errors($previousValue);

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
