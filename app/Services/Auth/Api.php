<?php

namespace App\Services\Auth;

use App\Models\User as UserModel;
use App\Services\Auth as AuthService;
use Lcobucci\JWT\Builder as JwtBuilder;
use Lcobucci\JWT\Parser as JwtParser;
use Lcobucci\JWT\Signer\Hmac\Sha256 as JwtSingerSha256;
use Lcobucci\JWT\Signer\Key as JwtSingerKey;
use Lcobucci\JWT\ValidationData as JwtValidationData;

class Api extends AuthService
{

    public function saveAuthInfo(UserModel $user)
    {
        $builder = new JwtBuilder();

        $config = $this->getConfig();

        $expireTime = time() + $config->jwt->lifetime;

        $builder->expiresAt($expireTime);
        $builder->withClaim('user_id', $user->id);
        $builder->withClaim('user_name', $user->name);

        $singer = new JwtSingerSha256();

        $key = new JwtSingerKey($config->jwt->key);

        $token = $builder->getToken($singer, $key);

        return $token->__toString();
    }

    public function clearAuthInfo()
    {

    }

    public function getAuthInfo()
    {
        $authToken = $this->getAuthToken();

        if (!$authToken) return null;

        $config = $this->getConfig();

        $parser = new JWTParser();

        $token = $parser->parse($authToken);

        $data = new JWTValidationData(time(), $config->jwt->leeway);

        if (!$token->validate($data)) {
            return null;
        }

        $singer = new JwtSingerSha256();

        if (!$token->verify($singer, $config->jwt->key)) {
            return null;
        }

        return [
            'id' => $token->getClaim('user_id'),
            'name' => $token->getClaim('user_name'),
        ];
    }

    protected function getAuthToken()
    {
        $authorization = $this->request->getHeader('Authorization');

        return trim(str_ireplace('Bearer', '', $authorization));
    }

    protected function getConfig()
    {
        return $this->getDI()->get('config');
    }

}
