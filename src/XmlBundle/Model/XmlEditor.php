<?php

/**
 * Xml Generator/Reader Bundle
 *
 * @author Desperado <desperado@minsk-info.ru>
 */

namespace Desperado\XmlBundle\Model;

use DOMDocument;
use Desperado\XmlBundle\Exceptions\InvalidArgumentException;

/**
 * Edit XML string
 *
 * @author Масюкевич Максим (Desperado)
 * @link   http://dengionline.com/
 */
class XmlEditor
    extends XmlAbstract
{

    /**
     * Removing all spaces and line breaks
     *
     * @param string $xmlString XML
     *
     * @return string
     */
    public function trimXmlString($xmlString)
    {
        $xmlString = preg_replace('/[\r\n]+(?![^(]*\))/', '', $xmlString);
        $xmlString = preg_replace('/[\s]{2,}/', ' ', $xmlString);
        $xmlString = str_replace(["\r\n", "\n"], '', $xmlString);

        return trim($xmlString);
    }

    /**
     * Pretty print for XML
     *
     * @param string $xmlString XML
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function formatXmlString($xmlString)
    {
        if(true === $this->isXml($xmlString))
        {
            $dom = new DOMDocument;
            $dom->preserveWhiteSpace = false;
            $dom->loadXML($xmlString);
            $dom->formatOutput = true;

            return $dom->saveXml();
        }
        else
        {
            throw new InvalidArgumentException('Incorrect XML string');
        }
    }

}