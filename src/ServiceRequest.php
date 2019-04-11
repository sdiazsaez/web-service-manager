<?php

namespace Larangular\WebServiceManager;

use Larangular\WebServiceManager\Register\{ServiceRecords, ServiceDescriptor};
use Larangular\WebServiceManager\Request\Requestable;

class ServiceRequest {

    private $serviceRecord;

    public function __construct(ServiceRecords $serviceRecord) {
        $this->serviceRecord = $serviceRecord;
    }

    public function getRequestableWithDescriptor(ServiceDescriptor $descriptor, array $data, $transform = true): Requestable {
        return new Requestable($descriptor, $data, $transform);
    }

    public function getRequestableWithDescriptorName(string $descriptorName, array $data, $transform = true): Requestable {
        return $this->getRequestableWithDescriptor($this->getServiceDescriptor($descriptorName), $data, $transform);
    }

    public function getRequestableWithServiceNames(string $provider, string $service, array $data, bool $transform = true): Requestable {
        $descriptor = $this->serviceRecord->getService($provider, $service);
        return $this->getRequestableWithDescriptor($descriptor, $data, $transform);

    }

    private function getServiceDescriptor(string $descriptorName): ServiceDescriptor {
        return new $descriptorName;
    }
}
