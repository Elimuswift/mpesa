<?php

use Elimuswift\Mpesa\Repositories\EndpointsRepository;

function mpesa_endpoint($endpoint)
{
    return EndpointsRepository::build($endpoint);
}
