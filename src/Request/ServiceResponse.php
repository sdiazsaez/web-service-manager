<?php

namespace Larangular\WebServiceManager\Request;

use WsdlToPhp\PackageBase\AbstractSoapClientBase;

class ServiceResponse {

    public $header;
    public $body;
    public $error;
    public $hasError;
    private $serviceClient;

    public function __construct(AbstractSoapClientBase $service) {
        $this->serviceClient = $service;
        $this->header = $service->getSoapClient()->getLastSoapOutputHeaders();
        $this->body = $service->getResult();
        $this->error = $service->getLastError();
    }

    public function getServiceClient(): AbstractSoapClientBase {
        return $this->serviceClient;
    }

}
