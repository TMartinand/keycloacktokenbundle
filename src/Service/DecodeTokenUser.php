<?php 

declare(strict_types=1);

namespace Amiltone\KeycloackTokenBundle\Service;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Amiltone\KeycloackTokenBundle\Model\UserKeycloack;

class DecodeTokenUser
{
    /**
     * @param Request $request
     * @throws UnauthorizedHttpException
     * @return array<string>
     */
    public function parseToken(Request $request): array
    {
        $token = $request->headers->get("Authorization");

        if (empty($token)) {
            throw new UnauthorizedHttpException("Token not found");
        }

        if (!preg_match('/Bearer\s(\S+)/', $token)) {
            throw new UnauthorizedHttpException("Invalid token");
        }

        try {
            return json_decode(base64_decode(explode('.', $token)[1]), true);
        } catch(Exception $e){
            throw new UnauthorizedHttpException("Invalid token");
        }
    }

    /**
     * @param array<string> $tokenData
     * @throws BadRequestHttpException
     */
    public function parseUserInfo(array $tokenData): UserKeycloack
    {
        if($this->checkTokenUserInfo($tokenData)) {
            return new UserKeycloack($tokenData["sub"], $tokenData["preferred_username"], $tokenData["email"], $tokenData["given_name"], $tokenData["family_name"]);
        }

        throw new BadRequestHttpException("Token data invalid");
    }

    /**
     * @param array<string> $tokenData
     */
    public function checkTokenUserInfo(array $tokenData): bool
    {
        //Obligatory field
        return (
            !empty($tokenData) &&
            isset($tokenData["sub"]) &&
            isset($tokenData["preferred_username"]) &&
            isset($tokenData["email"]) &&
            isset($tokenData["given_name"]) &&
            isset($tokenData["family_name"])
        );
    }

    public function getUser(Request $request): UserKeycloack
    {
        return $this->parseUserInfo($this->parseToken($request));
    }
}
