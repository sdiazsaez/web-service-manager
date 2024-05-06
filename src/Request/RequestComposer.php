<?php

namespace Larangular\WebServiceManager\Request;

use WsdlToPhp\PackageBase\AbstractStructBase;

abstract class RequestComposer {

    protected $data, $structs;

    public function __construct($data = []) {
        $this->data = $data;
    }

    abstract public function getRequest(); //: AbstractStructBase;

    protected function getStruct(string $name, string $prefixKey = '') {
        $struct = new $name();
        $params = $this->getStructVariables($name);
        $this->setStructVariables($struct, $params, $prefixKey);
        return $struct;
    }

    private function getStructVariables($name): array {
        return get_class_vars($name);
    }

    private function setStructVariables($struct, array $structParams, string $prefixKey = ''): void {
        $collection = (empty($prefixKey)
            ? $this->data
            : array_get($this->data, $prefixKey));// $this->data[$prefixKey]);

        foreach ($structParams as $key => $value) {
            if (!array_has($collection, $key)) {
                continue;
            }

            $resultValue = $collection[$key];
            if (is_array($resultValue)) {
                $parameterType = $this->getStructMethodParameterType($struct, $key);
                $keyPath = empty($prefixKey)
                    ? $key
                    : $prefixKey . '.' . $key;
                $resultValue = $this->getStruct($parameterType, $keyPath);
            }

            $struct->_set($key, $resultValue);
        }
    }

    private function getStructMethodParameterType(AbstractStructBase $struct, string $method): string {
        $method = 'set' . ucfirst($method);
        $structMethod = new \ReflectionMethod($struct, $method);
        $params = $structMethod->getParameters();
        return $params[0]->getType();
    }


}
