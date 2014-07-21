<?php

/**
 * Xml Generator/Reader Bundle
 *
 * @author Desperado <desperado@minsk-info.ru>
 */

namespace Desperado\XmlBundle\Tests\Model;

use Desperado\XmlBundle\Model\XmlReader;

/**
 * XmlReader test case
 *
 * @author Масюкевич Максим (Desperado)
 * @link   http://dengionline.com/
 */
class XmlReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @test
     * @group Desperado_XML
     */
    public function convertSimpleXmlInArray()
    {
        $xmlString = '<?xml version="1.0" encoding="UTF-8"?><root><paysystems>'
                     . '<paysystem id="1"><title>WebMoney</title></paysystem>'
                     . '<paysystem id="2"><title>Qiwi Russian</title></paysystem>'
                     . '<paysystem id="3"><title>Osmp-Russian</title><params>'
                     . '<account exist="0"><![CDATA[WTF?!]]></account><active>true'
                     . '</active></params></paysystem></paysystems></root>';

        $expectedArray = [
            'paysystems' => [
                ['paysystem' => ['title' => 'WebMoney']],
                ['paysystem' => ['title' => 'Qiwi Russian']],
                [
                    'paysystem' => [
                        'title'  => 'Osmp-Russian',
                        'params' => [
                            'account' => 'WTF?!',
                            'active'  => true,
                        ],
                    ],
                ],
            ],
        ];

        $object = new XmlReader;

        $resultArray = $object->processConvert($xmlString);

        $this->assertNotEmpty($resultArray);
        $this->assertTrue(is_array($resultArray));
        $this->assertEquals($resultArray, $expectedArray);
    }
}