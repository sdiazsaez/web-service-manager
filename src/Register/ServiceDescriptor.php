<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2019-02-11
 */

namespace Larangular\WebServiceManager\Register;

use Larangular\Support\Instance;
use Larangular\WebServiceManager\Adapters\RequestForm;

abstract class ServiceDescriptor {

    abstract public function provider(): string;

    abstract public function serviceName(): string;

    abstract public function serviceCallClassName(): string;

    abstract public function requestClassName(): string;

    abstract public function transformableClassName(): string;

    public function getForm(): array {
        if (!Instance::hasInterface($this, HasRequestForm::class)) {
            return [];
        }

        $className = $this->requestFormClassName();
        return $this->getFormWithValues(new $className);
    }

    private function getFormWithValues(RequestForm $form): array {
        $fields = $form->form();
        $defaultValues = $this->getDefaultValues();
        if (!empty($defaultValues)) {
            foreach ($fields as $key => $value) {
                $dotKey = str_replace(['[', ']'], ['.', ''], $key);
                if (!array_has($defaultValues, $dotKey)) {
                    continue;
                }

                $fields[$key]['value'] = array_get($defaultValues, $dotKey);
            }
        }

        return $fields;
    }

    public function getDefaultValues(): array {
        if (!Instance::hasInterface($this, HasDefaultValues::class)) {
            return [];
        }

        $className = $this->defaultValuesClassName();
        return (new $className)->values();
    }

}
