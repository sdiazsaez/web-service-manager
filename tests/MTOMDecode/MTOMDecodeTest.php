<?php

namespace Larangular\WebServiceManager\Tests\MTOMDecode;

use Larangular\WebServiceManager\SoapClient\MTOMDecode;
use Larangular\WebServiceManager\Tests\TestCase;

class MTOMDecodeTest extends TestCase {

    public function testDecode() {
        $sampleResponse = file_get_contents(__DIR__ . '/mtom-sample-response');
        $expectedResponse = file_get_contents(__DIR__ . '/mtom-expected-response');

        $mtom = new MTOMDecode();
        $decoded = $mtom->decode($sampleResponse);
        $this->assertTrue($decoded == $expectedResponse);
    }
}
