<?php

$data = ['foo' => 'bar'];

$xmlPrepare = new \Desperado\XmlBundle\Model\XmlPrepare;
$xmlGenerator = new Desperado\XmlBundle\Model\XmlGenerator;

echo $xmlGenerator->generateFromArray($xmlPrepare->prepareArrayBeforeToXmlConvert($data));