<?php

namespace Larangular\WebServiceManager\Request;

class ServiceResponse {

    public $header;
    public $body;
    public $error;
    public $hasError;
    private $serviceClient;

    public function __construct($service) {
        $this->serviceClient = $service;
        $this->header = $this->getOutputHeaders($service);
        $this->body = $service->getResult();
        $this->error = $service->getLastError();
    }

    private function getOutputHeaders($service) {
        return (method_exists($service, 'getSoapClient'))
            ? $service->getSoapClient()->getLastSoapOutputHeaders()
            : $service->getLastOutputHeaders();
    }

    public function getServiceClient() {
        return $this->serviceClient; //AbstractSoapClientBase
    }

    public function toArray(): array {
        return @json_decode(json_encode($this), true);
    }

}
