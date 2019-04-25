<?php

namespace Larangular\WebServiceManager\Traits;

trait ServiceUrl {

    public function getServiceUrl(string $provider, string $service) {
        $environment = $this->serviceUrl_getEnvironment($provider);
        $wsdlUrl = config($provider . '-services.services.' . $service . '.wsdl_url.' . $environment,
            config($provider . '-services.wsdl_url.' . $environment));
        return $wsdlUrl;
    }

    private function serviceUrl_getEnvironment(string $provider): string {
        $environment = config($provider . '-services.force_environment');
        if ($environment === false || is_null($environment)) {
            $environment = app()->isLocal()
                ? 'development'
                : 'production';
        }
        return $environment;
    }

}
