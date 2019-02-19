<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 1/26/18
 * Time: 19:34
 */

namespace Larangular\WebServiceManager\Request;

use WsdlToPhp\PackageBase\AbstractSoapClientBase;
use WsdlToPhp\PackageBase\AbstractStructBase;

abstract class ServiceCaller {

    use MakeServiceResponse;

    public $service;
    public $request;

    abstract public function serviceClass(): string;
    abstract public function makeRequest($service, AbstractStructBase $request): void;
    abstract public function isValidResponse($response): bool;

    public function __construct(RequestComposer $request) {
        $class = $this->serviceClass();
        $this->service = new $class();
        $this->request = $request;
    }

    public function getResponse(): ServiceResponse {
        $this->makeRequest($this->service, $this->request->getRequest());
        return $this->makeServiceResponse_GetResponse($this->service, function($body) {
            return !$this->isValidResponse($body);
        });
    }

}
