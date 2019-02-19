<?php

namespace Larangular\WebServiceManager\Facades;

use Illuminate\Support\Facades\Facade;

class ServiceRecords extends Facade {
    protected static function getFacadeAccessor() {
        return \Larangular\WebServiceManager\Register\ServiceRecords::class;
    }
}
