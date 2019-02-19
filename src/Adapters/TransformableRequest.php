<?php

namespace Larangular\WebServiceManager\Request;

interface TransformableRequest {
    public function transform(array $data): array;
}
