xml_wrapper_bundle
==================

XML Builder/Reader Bundle for Symfony2

## Installation

Using composer

Add `desperado/xml-bundle` to your composer.json file.

```js
     "require": {
	     "desperado/xml-bundle": "v0.1.*"
     },
     "repositories": [
         {
             "type": "git",
             "url": "https://github.com/pravednik/xml_wrapper_bundle.git",
             "trunk-path": "src"
         }
     ]
```

## Usage


### Create xml from array

```php
<?php

$data = [
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
                        '@value'  => 'WTF?!',
                    ],
                    'active'  => ['@value' => true]
                ]
            ]
        ]
    ]
];

$xmlGenerator = new Desperado\XmlBundle\Model\XmlGenerator;

$xmlGenerator
    ->setRootName('response')
    ->setDisableEntityLoader(true)
    ->setUseInternalErrors(true)
    ->setEncoding('UTF-8')
    ->setFormatOutputFlag(true);


echo $xmlGenerator->generateFromArray($data);
```
prints:

```xml

 <?xml version="1.0" encoding="UTF-8"?>
    <response>
      <paysystems>
        <paysystem id="1">
          <title>WebMoney</title>
        </paysystem>
        <paysystem id="2">
          <title>Qiwi Russian</title>
        </paysystem>
        <paysystem id="3">
          <title>Osmp-Russian</title>
          <params>
            <account exist="0">WTF?!</account>
            <active>true</active>
          </params>
        </paysystem>
      </paysystems>
    </response>

```

### Create XML without attributes, namespaces, etc.

```php
<?php

$data = ['foo' => 'bar'];

$xmlPrepare = new \Desperado\XmlBundle\Model\XmlPrepare;
$xmlGenerator = new Desperado\XmlBundle\Model\XmlGenerator;

echo $xmlGenerator->generateFromArray($xmlPrepare->prepareArrayBeforeToXmlConvert($data));
```

### DIC

XmlEditor: desperado_xml.model.xml_editor
XmlGenerator: desperado_xml.model.xml_generator
XmlReader: desperado_xml.model.xml_reader
XmlPrepare: desperado_xml.model.xml_prepare

### Config options

desperado_xml:
    disable_entity_loader: true (default: true)
    use_internal_errors: true (default: true)
