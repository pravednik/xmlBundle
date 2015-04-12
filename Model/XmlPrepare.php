<?php

/**
 * Xml Generator/Reader Bundle
 *
 * @author Desperado <desperado@minsk-info.ru>
 */

namespace Desperado\XmlBundle\Model;

use RecursiveArrayIterator;

/**
 * Class for preconditioning the array to convert to XML
 * Used exclusively for the preparation of simple arrays (without attributes, namespaces, etc.)
 *
 * @author Desperado <desperado@minsk-info.ru>
 */
class XmlPrepare
    extends XmlAbstract
{

    /**
     * Preparing the array to convert to XML
     *
     * @param array  $array      Source array
     * @param string $primaryKey Parent element name
     *
     * @return array
     */
    public function format($array, $primaryKey = '')
    {
        return $this->prepareArrayBeforeToXmlConvert($array, $primaryKey);
    }

    /**
     * Preparing the array to convert to XML
     *
     * @param array  $data       Source array
     * @param string $primaryKey Parent element name
     *
     * @return array
     */
    public function prepareArrayBeforeToXmlConvert(array $data, $primaryKey = '')
    {
        $result = [];
        $iterator = new RecursiveArrayIterator($data);
        $this->arrayWalk($iterator, $result, '');

        return '' !== (string) $primaryKey ? [$primaryKey => $result] : $result;
    }

    /**
     * Recursive traversal of array
     *
     * @param RecursiveArrayIterator $iterator
     * @param array                  $result
     * @param string                 $primaryKey
     */
    protected function arrayWalk(RecursiveArrayIterator $iterator, &$result, $primaryKey)
    {
        while($iterator->valid())
        {
            if($iterator->hasChildren())
            {
                if($iterator->getChildren()->hasChildren())
                {
                    $this->arrayWalk($iterator->getChildren(), $result[$iterator->key()], null);
                }
                else
                {
                    if('' !== $primaryKey && false === is_numeric($iterator->key()) && is_string($iterator->key()))
                    {
                        $this->arrayWalk($iterator->getChildren(), $result, $iterator->key());
                    }
                    else
                    {
                        $this->arrayWalk($iterator->getChildren(), $result[$iterator->key()], $primaryKey);
                    }
                }
            }
            else
            {
                if('' !== (string) $primaryKey)
                {
                    $result[$iterator->key()] = ['@value' => $iterator->current()];
                }
                else
                {
                    $result[$primaryKey][$iterator->key()] = ['@value' => $iterator->current()];
                }
            }

            $iterator->next();
        }
    }
}
