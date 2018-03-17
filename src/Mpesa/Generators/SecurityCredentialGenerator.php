<?php

namespace Elimuswift\Mpesa\Generators;

use Elimuswift\Mpesa\Engine\Core;
use Elimuswift\Mpesa\Contracts\GeneratorInterface;

class SecurityCredentialGenerator implements GeneratorInterface
{
    /**
     * Core instance.
     *
     * @var Core
     **/
    protected $mpesa;

    /**
     * Instantiate generator class with the mpesa Core class.
     *
     * @param Core $config
     **/
    public function __construct(Core $mpesa)
    {
        $this->mpesa = $mpesa;
    }

    /**
     *  Generate a base64 encoded initiator password
     *  with M-Pesa’s public key, a X509 certificate.
     *
     * @author Leitato Albert <wizqydy@gmail.com>
     *
     * @return string
     **/
    public function generate()
    {
        // $publicKey = \file_get_contents($this->mpesa->config->get('mpesa.public_key'));
        // $password = $this->mpesa->config->get('mpesa.initiator_password');
        // \openssl_public_encrypt($password, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING);
        // $securityCredential = \base64_encode($encrypted);

        return $this->mpesa->config->get('mpesa.securityCredential');
    }
}
