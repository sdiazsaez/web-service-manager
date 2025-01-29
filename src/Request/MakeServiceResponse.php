<?php

namespace Larangular\WebServiceManager\Request;

trait MakeServiceResponse {

    public function makeServiceResponse_GetResponse($service, \Closure $hasError): ServiceResponse {
        $serviceResponse = new ServiceResponse($service);
        $serviceResponse->hasError = $hasError($serviceResponse);
        return $serviceResponse;
    }

}
