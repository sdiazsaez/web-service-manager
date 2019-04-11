<?php

namespace Larangular\WebServiceManager\Request;

use Larangular\WebServiceManager\Register\ServiceDescriptor;

class Requestable {

    public  $descriptor;
    private $transformable;
    private $requestData;

    public function __construct(ServiceDescriptor $descriptor, array $data, $transformable = true) {
        $this->descriptor = $descriptor;
        $this->transformable = $transformable;
        $this->requestData = $data;
        if ($this->transformable) {
            $this->requestData = $this->setTransformedData($data);
        }
    }

    public function getResponse(): ServiceResponse {
        $request = $this->getServiceRequest();
        $service = $this->getServiceInstance($this->descriptor, $request);
        return $service->getResponse();
    }

    public function getTransformedData(): array {
        return $this->requestData;
    }

    private function getServiceInstance(ServiceDescriptor $service, RequestComposer $request): ServiceCaller {
        $class = $service->serviceCallClassName();
        return new $class($request);
    }

    private function getServiceRequest(): RequestComposer {
        $class = $this->descriptor->requestClassName();
        return new $class($this->requestData);
    }

    private function setTransformedData($data): array {
        $transformClass = $this->descriptor->transformableClassName();
        return (new $transformClass())->transform($data);
    }

}
