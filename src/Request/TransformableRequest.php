<?php
/**
 * [required]
 * Point where the service is able to format the provided data
 * to comply with service request structure
 */

//move to Adapters and update providers
namespace Larangular\WebServiceManager\Request;

interface TransformableRequest {
    public function transform(array $data): array;
}
