<?php

namespace Larangular\WebServiceManager\Request;

use \WsdlToPhp\PackageBase\AbstractSoapClientBase;

trait MakeServiceResponse {

    public function makeServiceResponse_GetResponse(AbstractSoapClientBase $service, \Closure $hasError): ServiceResponse {
        $serviceResponse = new ServiceResponse($service);
        $serviceResponse->hasError = $hasError($serviceResponse->body);
        return $serviceResponse;
    }

}
