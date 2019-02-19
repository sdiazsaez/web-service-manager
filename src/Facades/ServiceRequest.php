<?php

namespace Larangular\WebServiceManager\Facades;

use Illuminate\Support\Facades\Facade;

class ServiceRequest extends Facade {
    protected static function getFacadeAccessor() {
        return \Larangular\WebServiceManager\ServiceRequest::class;
    }
}
