xml_wrapper_bundle
==================

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ac3fa776-bc19-4fd7-99f0-a0466c1b0bd3/big.png)](https://insight.sensiolabs.com/projects/ac3fa776-bc19-4fd7-99f0-a0466c1b0bd3)

XML Builder/Reader Bundle for Symfony2

## Installation

Using composer

Add `desperado/xml-bundle` to your composer.json file.

```js
     "require": {
	     "desperado/xml-bundle": "dev-master"
     }
```

## Usage

### DIC

* XmlEditor: desperado_xml.model.xml_editor
* XmlGenerator: desperado_xml.model.xml_generator
* XmlReader: desperado_xml.model.xml_reader
* XmlPrepare: desperado_xml.model.xml_prepare

### Config options

```yml
desperado_xml:
    disable_entity_loader: true (default: true)
    use_internal_errors: true (default: true)
```

### Create xml from array

```php
<?php
$params = [
            'Request' => [
                '@ns'               => [
                    '0_xmlns' => ['prefix' => 'xsi', 'uri' => 'http//www.w3.org/2001/XMLSchema-instance'],
                    '1_xmlns' => ['prefix' => 'xsd', 'uri' => 'http//www.w3.org/2001/XMLSchema'],
                ],
                '@attrib'           => [
                    'Id'      => 100,
                    'Service' => 200,
                    'xmlns'   => 'http://ekassir.com/ekassir/PaySystem/Server/eKassirV3Protocol'
                ],
                'PaymentParameters' => [
                    '@attrib'   => ['xmlns' => ''],
                    'Parameter' => [
                        '@attrib' => ['Name' => 'account'],
                        '@value'  => 'emptyAccount'
                    ]

                ]
            ]
        ];


        $xmlGenerator = new XmlGenerator;

        echo $xmlGenerator->generateFromArray($params);
```
prints:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<root xmlns:xsi="http//www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http//www.w3.org/2001/XMLSchema">
  <Request xmlns="http://ekassir.com/ekassir/PaySystem/Server/eKassirV3Protocol" xmlns:xsi="http//www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http//www.w3.org/2001/XMLSchema" Id="100" Service="200" xsi:xmlns="" xsd:xmlns="">
    <PaymentParameters xmlns="">
      <Parameter Name="account">emptyAccount</Parameter>
    </PaymentParameters>
  </Request>
</root>
```

### Create XML without attributes, namespaces, etc.

```php
<?php
        $params = [
            'Details' => [
                'PaymentParameters' => [
                    'first_node'  => 'first_node_value',
                    'second_node' => 'second_node_value'
                ]
            ]
        ];


        $xmlPrepare = new XmlPrepare;
        $xmlGenerator = new XmlGenerator;

        echo $xmlGenerator->setRootName('request')->generateFromArray($xmlPrepare->prepareArrayBeforeToXmlConvert($params));
```
prints:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<request>
  <Details>
    <PaymentParameters>
      <first_node>first_node_value</first_node>
      <second_node>second_node_value</second_node>
    </PaymentParameters>
  </Details>
</request>
```

### Parse XML without attributes, namespaces, etc.

```php
<?php
        $xmlString = '<?xml version="1.0" encoding="UTF-8"?>
                      request>
                          <Details>
                              <PaymentParameters>
                                  <first_node>first_node_value</first_node>
                                  <second_node>second_node_value</second_node>
                              </PaymentParameters>
                          </Details>
                      </request>';

        $xmlReader = new XmlReader;

        print_r($xmlReader->processConvert($xmlString));
```
prints:

```php
Array
(
    [Details] => Array
        (
            [PaymentParameters] => Array
                (
                    [first_node] => first_node_value
                    [second_node] => second_node_value
                )

        )

)
```