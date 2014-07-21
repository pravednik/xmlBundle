<?php

/**
 * Xml Generator/Reader Bundle
 *
 * @author Desperado <desperado@minsk-info.ru>
 */

namespace Desperado\XmlBundle\Model;

use DOMDocument;
use DOMElement;
use Desperado\XmlBundle\Exceptions\InvalidArgumentException;

/**
 * Class of converting PHP array to XML
 *
 * Example:
 *
 *  $xmlGenerator->setRootName('response');
 *
 *  $data = array(
 *      'paysystems' => array(
 *          'paysystem' => array(
 *              array(
 *                  '@attrib' => array(
 *                      'id' => 1
 *                  ),
 *                  'title'       => 'WebMoney'
 *              ),
 *              array(
 *                  '@attrib' => array(
 *                      'id' => '2'
 *                  ),
 *                  'title'       => 'Qiwi Russian'
 *              ),
 *              array(
 *                  '@attrib' => array(
 *                      'id' => 3
 *                  ),
 *                  'title'       => 'Osmp-Russian',
 *                  'params'      => array(
 *                      'account' => array(
 *                          '@attrib' => array(
 *                              'exist' => 0
 *                          ),
 *                          '@value'      => 'WTF?!',
 *                      ),
 *                      'active'  => array(
 *                          '@value' => true
 *                      )
 *                  )
 *              )
 *          )
 *      )
 *  );
 *
 *  echo $xmlGenerator->generateFromArray($data);
 *
 * Result XML:
 *
 * <?xml version="1.0" encoding="UTF-8"?>
 *    <response>
 *      <paysystems>
 *        <paysystem id="1">
 *          <title>WebMoney</title>
 *        </paysystem>
 *        <paysystem id="2">
 *          <title>Qiwi Russian</title>
 *        </paysystem>
 *        <paysystem id="3">
 *          <title>Osmp-Russian</title>
 *          <params>
 *            <account exist="0">WTF?!</account>
 *            <active>true</active>
 *          </params>
 *        </paysystem>
 *      </paysystems>
 *    </response>
 *
 * @author Desperado <desperado@minsk-info.ru>
 */
class XmlGenerator
    extends XmlAbstract
{

    /**
     * XML version
     *
     * @var string
     */
    protected $version = '1.0';

    /**
     * XML charset
     *
     * @var string
     */
    protected $encoding = 'UTF-8';

    /**
     * Name of the root element
     *
     * @var string
     */
    protected $rootName = 'root';

    /**
     * XML formatting needed
     *
     * @var boolean
     */
    protected $formatOutputFlag = true;

    /** @var null|DomDocument */
    protected $domDocument;

    /**
     * Set XML charset
     *
     * @param string $encoding
     *
     * @return $this
     */
    public function setEncoding($encoding)
    {
        $this->encoding = strtoupper($encoding);

        return $this;
    }

    /**
     * Set XML version
     *
     * @param string $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Set name of the root element
     *
     * @param null|string $rootName Имя родительского элемента
     *
     * @return $this
     */
    public function setRootName($rootName = null)
    {
        $this->rootName = $rootName;

        return $this;
    }

    /**
     * Gets XML version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Gets XML charset
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Gets name of the root element
     *
     * @return string
     */
    public function getRootNameElement()
    {
        return $this->rootName;
    }

    /**
     * Sets if XML formatting needed
     *
     * @param boolean $flag
     *
     * @return $this
     */
    public function setFormatOutputFlag($flag)
    {
        $this->formatOutputFlag = (boolean) $flag;

        return $this;
    }

    /**
     * Process convert array to XML
     *
     * @param array $data
     *
     * @return string
     */
    public function generateFromArray(array $data)
    {
        $xml = $this->processGenerate($this->rootName, $data);
        $this->getDomDocument()->appendChild($xml);
        $xml = $this->getDomDocument()->saveXML();

        $this->flush();

        return $xml;
    }

    /**
     * Recursive creation of XML
     *
     * @param string       $element The child's name
     * @param string|array $data    Element value
     *
     * @return DOMElement
     *
     * @throws InvalidArgumentException
     */
    protected function processGenerate($element, $data)
    {
        $this->prepareElementName($element);

        /** setRootName(null) support */
        if(empty($element))
        {
            $element = array_keys($data)[0];

            return $this->processGenerate($element, $data[$element]);
        }

        /** @var DOMElement $node */
        $node = $this->getDomDocument()->createElement($element);

        if(is_array($data))
        {
            $this->applyAttributes($data, $node);

            if(isset($data['@value']))
            {
                $this->applyValue($data, $node);

                return $node;
            }
            else if(isset($data['@cdata']))
            {
                $this->applyCData($data, $node);

                return $node;
            }
            elseif(isset($data['@ns']))
            {
                $this->applyNamespace($data, $node);
            }
        }

        if(is_array($data))
        {
            foreach($data as $key => $value)
            {
                $this->prepareElementName($key);

                if('' === $key || false === $this->isValidElementName($key))
                {
                    continue;
                }

                if(is_array($value) && is_numeric(key($value)))
                {
                    foreach($value as $k => $v)
                    {
                        $node->appendChild($this->processGenerate($key, $v));
                    }
                }
                else
                {
                    $node->appendChild($this->processGenerate($key, $value));
                }

                unset($data[$key]);
            }
        }

        if(!is_array($data))
        {
            $data = $this->getBooleanAsString($data);

            $node->appendChild(
                $this->getDomDocument()->createTextNode($data)
            );
        }

        return $node;
    }

    /**
     * Adding Namespaces
     *
     * @param array      $data
     * @param DOMElement $node
     *
     * @return void
     */
    protected function applyNamespace(&$data, &$node)
    {
        foreach($data['@ns'] as $namespace => $nsDetails)
        {
            $nsUrl = !empty($nsDetails['uri']) ? $nsDetails['uri'] : '';
            $nsQualifiedName = !empty($nsDetails['prefix']) ? $nsDetails['prefix'] : '';
            $nsContent = !empty($nsDetails['content']) ? $nsDetails['content'] : '';
            $prefixName = !empty($nsQualifiedName) ? $nsQualifiedName . ':' . $namespace : $namespace;

            $node->setAttributeNS($nsUrl, $prefixName, $nsContent);
        }

        unset($data['@ns']);
    }

    /**
     * Adding cdata
     *
     * @param array      $data
     * @param DOMElement $node
     *
     * @return void
     */
    protected function applyCData(&$data, &$node)
    {
        $data['@cdata'] = $this->getBooleanAsString($data['@cdata']);

        $node->appendChild(
            $this->getDomDocument()->createCDATASection($data['@cdata'])
        );

        unset($data['@cdata']);
    }

    /**
     * Adding element value
     *
     * @param array      $data
     * @param DOMElement $node
     *
     * @return void
     */
    protected function applyValue(&$data, &$node)
    {
        $data['@value'] = $this->getBooleanAsString($data['@value']);

        if(is_array($data['@value']))
        {
            $this->processGenerate(key($data['@value']), $data['@value']);
        }
        else
        {
            $node->appendChild($this->getDomDocument()->createTextNode($data['@value']));
        }

        unset($data['@value']);
    }

    /**
     * Adding attributes
     *
     * @param array      $data
     * @param DOMElement $node
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    protected function applyAttributes(&$data, &$node)
    {
        if(isset($data['@attrib']))
        {
            foreach($data['@attrib'] as $key => $value)
            {
                if(true === $this->isValidElementName($key))
                {
                    $value = $this->getBooleanAsString($value);

                    $node->setAttribute($key, $value);
                }
                else
                {
                    throw new InvalidArgumentException(
                        sprintf('Illegal character in attribute (%s) name', $key)
                    );
                }
            }

            unset($data['@attrib']);
        }
    }

    /**
     * Converting boolean values to the string
     *
     * @param mixed $elementValue
     *
     * @return string
     */
    protected function getBooleanAsString($elementValue)
    {
        if(is_bool($elementValue))
        {
            $elementValue = (true === $elementValue) ? 'true' : 'false';
        }

        return $elementValue;
    }

    /**
     * Gets DomDocument object
     *
     * @return DOMDocument
     */
    protected function getDomDocument()
    {
        if(null === $this->domDocument)
        {
            $this->domDocument = new DOMDocument($this->getVersion(), $this->getEncoding());
            $this->domDocument->formatOutput = $this->formatOutputFlag;
        }

        return $this->domDocument;
    }

    /**
     * Remove DomDocument object
     *
     * @return $this
     */
    protected function flush()
    {
        $this->domDocument = null;

        return $this;
    }

    /**
     * Support for multiple elements with the same name
     *
     * @param string $elementName
     *
     * @return void
     */
    protected function prepareElementName(&$elementName)
    {
        $tempElementName = explode('_', $elementName);
        $elementName = isset($tempElementName[0]) && is_numeric($tempElementName[0])
            ? $tempElementName[1]
            : $elementName;
    }
}