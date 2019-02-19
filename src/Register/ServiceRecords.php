<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2019-02-11
 */

namespace Larangular\WebServiceManager\Register;

class ServiceRecords {

    private $services       = [];
    private $registeredKeys = [];

    public function __construct() {
    }

    public function register(ServiceDescriptor $serviceDescriptor): void {
        $this->registerKeys($serviceDescriptor->provider(), $serviceDescriptor->serviceName());
        $this->services[$this->makeKeyService($serviceDescriptor->provider(),
            $serviceDescriptor->serviceName())] = $serviceDescriptor;
    }

    public function getService(string $provider, string $service): ServiceDescriptor {
        return $this->services[$this->makeKeyService($provider, $service)];
    }

    public function getProviders(): array {
        return array_keys($this->registeredKeys);
    }

    public function getServiceNames(string $provider): array {
        if (!array_key_exists($provider, $this->registeredKeys)) {
            return [];
        }

        return $this->registeredKeys[$provider];
    }

    private function registerKeys(string $provider, string $service): void {
        if (!array_key_exists($provider, $this->registeredKeys)) {
            $this->registeredKeys[$provider] = [];
        }

        if (array_search($service, $this->registeredKeys[$provider]) === false) {
            array_push($this->registeredKeys[$provider], $service);
        }
    }

    private function makeKeyService(string $provider, string $service): string {
        return $provider . '.' . $service;
    }
}
