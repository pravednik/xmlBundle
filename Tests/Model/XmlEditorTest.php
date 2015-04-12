<?php

/**
 * Xml Generator/Reader Bundle
 *
 * @author Desperado <desperado@minsk-info.ru>
 */

namespace Desperado\XmlBundle\Tests\Model;

use Desperado\XmlBundle\Model\XmlEditor;

/**
 * XmlEditor test case
 *
 * @author Масюкевич Максим (Desperado)
 * @link   http://dengionline.com/
 */
class XmlEditorTest extends \PHPUnit_Framework_TestCase
{
    /** @var XmlEditor */
    protected $object;

    /** {@inheritDoc} */
    public function setUp()
    {
        parent::setUp();

        $this->object = new XmlEditor;
    }

    /**
     *
     * @test
     * @group Desperado_XML
     */
    public function isXmlTrue()
    {
        $xml = '<response><status>error</status><code>3</code><descr>details</descr></response>';

        $this->assertTrue($this->object->isXml($xml));
    }

    /**
     *
     * @test
     * @group Desperado_XML
     */
    public function isXmlFalse()
    {
        $xml = '<response><status>error</status><code>3</code><descr>details</descr><response>';

        $this->assertFalse($this->object->isXml($xml));
    }

    /**
     *
     * @test
     * @group Desperado_XML
     */
    public function trimXml()
    {
        $xml = '  <response><status>error</status><code>3</code><descr>details</descr></response>   ' . PHP_EOL;
        $expected = '<response><status>error</status><code>3</code><descr>details</descr></response>';

        $trimmedXml = $this->object->trimXmlString($xml);

        $this->assertNotEmpty($trimmedXml);
        $this->assertEquals($expected, $trimmedXml);
    }

    /**
     *
     * @test
     * @group Desperado_XML
     */
    public function successFormatXml()
    {
        $xml = '<response><status>error</status><code>3</code><descr>details</descr></response>';

        $formattedXml = $this->object->formatXmlString($xml);

        $this->assertNotEmpty($formattedXml);
        $this->assertTrue($this->object->isXml($formattedXml));
    }

    /**
     *
     * @test
     * @group Desperado_XML
     * @expectedException \Desperado\XmlBundle\Exceptions\InvalidArgumentException
     */
    public function errorFormatXml()
    {
        $this->object->formatXmlString('wrong string');
    }
}
