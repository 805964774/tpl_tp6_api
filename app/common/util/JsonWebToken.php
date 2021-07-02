<?php


namespace app\common\util;


use app\common\constant\ErrorNums;
use app\common\exception\AppException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use UnexpectedValueException;

class JsonWebToken
{
    /**
     * set 数据
     * @param $data
     * @return string
     */
    public function setData($data): string {
        $jwtConf = config('jwt');
        $currentTimeStamp = time();
        $payload = [
            "iat" => $currentTimeStamp,
            "exp" => $currentTimeStamp + $jwtConf['exp'],
            "nbf" => $currentTimeStamp,
            'data' => $data,
        ];
        $payload = array_merge($jwtConf, $payload);
        return JWT::encode($payload, $jwtConf['iss']);
    }

    /**
     * 获取之前set的数据
     * @param $token
     * @param $name
     * @return string
     */
    /**
     * @param $token
     * @param $name
     * @return string|null
     * @throws UnexpectedValueException     Provided JWT was invalid
     * @throws SignatureInvalidException    Provided JWT was invalid because the signature verification failed
     * @throws BeforeValidException         Provided JWT is trying to be used before it's eligible as defined by 'nbf'
     * @throws BeforeValidException         Provided JWT is trying to be used before it's been created as defined by 'iat'
     * @throws ExpiredException|AppException             Provided JWT has since expired, as defined by the 'exp' claim
     */
    public function getData($token, $name = ''): ?string {
        try {
            $jwtConf = config('jwt');
            JWT::$leeway = 60; // $leeway in seconds
            $decoded = JWT::decode($token, $jwtConf['iss'], ['HS256']);
            if (empty($name)) {
                return json_decode(json_encode($decoded->data), true);
            }
            return $decoded->data->$name ?? null;
        } catch (UnexpectedValueException
        | SignatureInvalidException
        | BeforeValidException
        | ExpiredException $e) {
            throw new AppException(ErrorNums::TOKEN_INVALID);
        }
    }
}
