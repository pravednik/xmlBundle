<?php

/**
 * Xml Generator/Reader Bundle
 *
 * @author Desperado <desperado@minsk-info.ru>
 */

namespace Desperado\XmlBundle\Model;

use SimpleXmlIterator;

/**
 * Class for converting XML into a PHP array
 * Convert only "simple" XML (without attributes, namespaces, etc.)
 *
 * @author Масюкевич Максим (Desperado)
 * @link   http://dengionline.com/
 */
class XmlReader
    extends XmlAbstract
{

    /**
     * Convert XML to PHP array
     *
     * @param string $xmlString
     *
     * @return array
     */
    public function processConvert($xmlString)
    {
        $result = [];
        $entity_load = libxml_disable_entity_loader(true);
        $errors = libxml_use_internal_errors(true);

        if(true === $this->isXml($xmlString))
        {
            $iterator = new SimpleXmlIterator($xmlString);

            $result = @$this->getArrayFromXml($iterator);
            libxml_disable_entity_loader($entity_load);
            libxml_use_internal_errors($errors);
        }
        return $result;
    }

    /**
     * Convert XML to PHP array
     *
     * @param SimpleXmlIterator $iterator
     *
     * @return array
     */
    protected function getArrayFromXml(SimpleXmlIterator $iterator)
    {
        $result = [];

        for($iterator->rewind(); $iterator->valid(); $iterator->next())
        {
            if($iterator->hasChildren())
            {
                foreach($iterator->getChildren() as $key => $value)
                {
                    if(1 <= $value->count())
                    {
                        $result[$iterator->key()][][$key] = $this->getArrayFromXml($value);
                    }
                    else
                    {
                        if(true === $value->hasChildren())
                        {
                            $result[$iterator->key()][$key] = $this->getArrayFromXml($value);
                        }
                        else
                        {
                            $result[$iterator->key()] = $this->getArrayFromXml($iterator->current());
                        }
                    }
                }
            }
            else
            {
                $result[$iterator->key()] = $this->convertToStringOrBoolean($iterator->current());
            }
        }

        return $result;
    }

    /**
     * Cast to string or to a boolean value if the string contains a true or false
     *
     * @param mixed $string
     *
     * @return boolean|string
     */
    protected function convertToStringOrBoolean($string)
    {
        $result = (string) $string;
        $lowerString = strtolower($result);

        if('true' === $lowerString)
        {
            $result = true;
        }
        elseif('false' === $lowerString)
        {
            $result = false;
        }

        return $result;
    }
}
