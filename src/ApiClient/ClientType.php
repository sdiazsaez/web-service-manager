<?php

namespace Larangular\WebServiceManager\ApiClient;

/**
 * Class ClientType
 *
 * This class defines different types of API clients that can be used within the web service manager.
 * Each client type represents a specific format or protocol for communication with API endpoints.
 *
 * Usage Example:
 * $clientType = ClientType::APIJSON;
 *
 * @package Larangular\WebServiceManager\ApiClient
 */
abstract class ClientType {
    // Define constants for different types of API clients

    /**
     * Represents an API client that communicates using JSON format.
     */
    public const APIJSON = 'APIJSON';

    /**
     * Represents an API client that communicates using SOAP protocol.
     */
    public const APISOAP = 'APISOAP';
}
