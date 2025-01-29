<?php

namespace Larangular\WebServiceManager\Interfaces;

use Larangular\WebServiceManager\Request\ServiceResponse;

interface CanValidateWSResponse {

    public function hasValidResponse(ServiceResponse $serviceResponse);

}
