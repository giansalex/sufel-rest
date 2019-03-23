<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 22/03/2019
 * Time: 21:54
 */

namespace Sufel\App\Services;

use Firebase\JWT\JWT;
use Sufel\App\Service\TokenServiceInterface;

class TokenProvider implements TokenServiceInterface
{

    /**
     * Generate token.
     *
     * @param array $data
     * @param string $secret
     *
     * @return string
     */
    public function create($data, $secret)
    {
        return JWT::encode($data, $secret);
    }
}