<?php

namespace Larangular\WebServiceManager\Request;

use Larangular\WebServiceManager\Register\ServiceDescriptor;

class Requestable {

    public  $descriptor;
    private $transformable;
    private $requestData;
    private $requestComposer;
    private $serviceCaller;

    public function __construct(ServiceDescriptor $descriptor, array $data, $transformable = true) {
        $this->descriptor = $descriptor;
        $this->transformable = $transformable;
        $this->requestData = $data;
        if ($this->transformable) {
            $this->requestData = $this->setTransformedData($data);
        }
    }

    public function getResponse(): ServiceResponse {
        return $this->getServiceCaller()->getResponse();
    }

    public function getServiceCaller(): ServiceCaller {
        if(is_null($this->serviceCaller)) {
            $class = $this->descriptor->serviceCallClassName();
            $this->serviceCaller = new $class($this->getRequestComposer());
        }
        return $this->serviceCaller;
    }

    public function getTransformedData(): array {
        return $this->requestData;
    }

    private function getRequestComposer(): RequestComposer {
        if(!is_null($this->requestComposer)) {
            $class = $this->descriptor->requestClassName();
            $this->requestComposer = new $class($this->requestData);
        }

        return $this->requestComposer;
    }

    private function setTransformedData($data): array {
        $transformClass = $this->descriptor->transformableClassName();
        return (new $transformClass())->transform($data);
    }

}
