<?php

/**
 * Xml Generator/Reader Bundle
 *
 * @author Desperado <desperado@minsk-info.ru>
 */

namespace Desperado\XmlBundle\Tests\Model;

use Desperado\XmlBundle\Model\XmlGenerator;

/**
 * XmlGenerator test case
 *
 * @author Масюкевич Максим (Desperado)
 * @link   http://dengionline.com/
 */
class XmlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @test
     * @group Desperado_XML
     */
    public function successRemoveAnnouncement()
    {
        $xml = '<?xml version="1.1" encoding="UTF-8"?><response><status>error</status><code>3</code><descr>details'
               . '</descr><response>';

        $expectedXml = '<response><status>error</status><code>3</code><descr>details</descr><response>';


        $object = new XmlGenerator;
        $xml = $object->removeAnnouncement($xml);

        $this->assertNotEmpty($xml);
        $this->assertEquals($expectedXml, $xml);
    }

    /**
     *
     * @test
     * @group Desperado_XML
     */
    public function wrongRemoveAnnouncement()
    {
        $expectedXml = '<response><status>error</status><code>3</code><descr>details</descr><response>';

        $object = new XmlGenerator;
        $xml = $object->removeAnnouncement($expectedXml);

        $this->assertNotEmpty($xml);
        $this->assertEquals($expectedXml, $xml);
    }

    /**
     *
     * @test
     * @group Desperado_XML
     */
    public function testSuccessCreateXmlFromArray()
    {
        $array = [
            'paysystems' => [
                'paysystem' => [
                    [
                        '@attrib' => ['id' => 1],
                        'title'   => 'WebMoney'
                    ],
                    [
                        '@attrib' => ['id' => '2'],
                        'title'   => 'Qiwi Russian'
                    ],
                    [
                        '@attrib' => ['id' => 3],
                        'title'   => 'Osmp-Russian',
                        'params'  => [
                            'account' => [
                                '@attrib' => ['exist' => 0],
                                '@cdata'  => 'WTF?!',
                            ],
                            'active'  => ['@value' => true]
                        ]
                    ]
                ]
            ]
        ];

        $object = new XmlGenerator;
        $xml = $object->generateFromArray($array);

        $this->assertNotEmpty($xml);
        $this->assertTrue($object->isXml($xml));
    }

    /**
     *
     * @test
     * @group Desperado_XML
     */
    public function testCreateXmlWithWrongNodeName()
    {
        $array = [
            'paysystems' => [
                'paysystem' => [
                    [
                        '@attrib' => ['id' => 1],
                        '#%$5'    => 'WebMoney'
                    ],
                    [
                        '@attrib' => ['id' => '2'],
                        'title'   => 'Qiwi Russian'
                    ],
                    [
                        '@attrib' => ['id' => 3],
                        'title'   => 'Osmp-Russian',
                        'params'  => [
                            'account' => [
                                '@attrib' => ['exist' => 0],
                                '@value'  => 'WTF?!',
                            ],
                            'active'  => ['@value' => true]
                        ]
                    ]
                ]
            ]
        ];

        $object = new XmlGenerator;
        $object->generateFromArray($array);
    }

    /**
     * Тестирование создания XML с некорректным названием атрибута
     *
     * @expectedException \Desperado\XmlBundle\Exceptions\InvalidArgumentException
     *
     * @access public
     * @return void
     */
    public function testCreateXmlWithWrongAttribName()
    {
        $array = [
            'paysystems' => [
                'paysystem' => [
                    [
                        '@attrib' => ['#%$5' => 1],
                        'title'   => 'WebMoney'
                    ],
                    [
                        '@attrib' => ['id' => '2'],
                        'title'   => 'Qiwi Russian'
                    ],
                    [
                        '@attrib' => [
                            'id' => 3
                        ],
                        'title'   => 'Osmp-Russian',
                        'params'  => [
                            'account' => [
                                '@attrib' => ['exist' => 0],
                                '@value'  => 'WTF?!',
                            ],
                            'active'  => ['@value' => []]
                        ]
                    ]
                ]
            ]
        ];

        $object = new XmlGenerator;
        $object->generateFromArray($array);
    }
} 