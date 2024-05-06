<?php

namespace Larangular\WebServiceManager\Traits;

trait ServiceUrl {

    /**
     * Get the service environment configuration based on provider and config path.
     *
     * @param string $provider   The name of the service provider.
     * @param string $configPath The path to the configuration.
     * @return mixed The service environment configuration.
     */
    public function getServiceEnvironmentConfig(string $provider, string $configPath) {
        // Retrieves the configuration based on the provider, config path, and current environment
        $environment = $this->serviceUrl_getEnvironment($provider);
        $configKey = $provider . '-services.' . $configPath . '.' . $environment;
        return config($configKey);
    }

    /**
     * Get the URL of a specific service.
     *
     * @param string $provider The name of the service provider.
     * @param string $service  The name of the service.
     * @param string $urlKey   The key for the service URL in the configuration (default: 'wsdl_url').
     * @return string|null The URL of the service, or null if not found.
     */
    public function getServiceUrl(string $provider, string $service, string $urlKey = 'wsdl_url') {
        // Checks if the service-specific wsdl_url exists and is a string
        $serviceUrlKey = $this->serviceUrl_getFormatConfigKey($provider, $urlKey, $service);
        $generalUrlKey = $this->serviceUrl_getFormatConfigKey($provider, $urlKey);

        $serviceUrl = config($serviceUrlKey, config($generalUrlKey));
        if (is_string($serviceUrl)) {
            return $serviceUrl; // Returns the service-specific URL immediately if it's a string
        }

        // If not found or not a string, fetch based on environment
        $environment = $this->serviceUrl_getEnvironment($provider);
        $serviceUrlKey = $this->serviceUrl_getFormatConfigKey($provider, $urlKey, $service, $environment);
        $generalUrlKey = $this->serviceUrl_getFormatConfigKey($provider, $urlKey, '', $environment);

        return config($serviceUrlKey, config($generalUrlKey));
    }

    /**
     * Format the configuration key for service URLs.
     *
     * @param string $provider The name of the service provider.
     * @param string $urlKey   The key for the service URL in the configuration.
     * @param string $service  The name of the service (optional).
     * @param string $env      The environment (optional).
     * @return string The formatted configuration key.
     */
    private function serviceUrl_getFormatConfigKey($provider, $urlKey = 'wsdl_url', $service = '', $env = '') {
        return vsprintf('%s-services%s.%s%s', [
            $provider,
            empty($service)
                ? ''
                : '.services.' . $service,
            $urlKey,
            empty($env)
                ? ''
                : '.' . $env,
        ]);
    }

    /**
     * Determine the environment for service configuration.
     *
     * @param string $provider The name of the service provider.
     * @return string The environment for the service.
     */
    private function serviceUrl_getEnvironment(string $provider): string {
        // Checks if a specific environment is forced in the configuration
        $environment = config($provider . '-services.force_environment');
        if ($environment === false || is_null($environment)) {
            // If no forced environment, use 'development' for local and testing environments, 'production' otherwise
            $environment = app()->environment([
                'local',
                'testing',
            ])
                ? 'development'
                : 'production';
        }
        return $environment;
    }

}
