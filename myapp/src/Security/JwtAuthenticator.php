<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Firebase\JWT\JWT;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $params;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(EntityManagerInterface $em, ContainerBagInterface $params, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->params = $params;
        $this->logger = $logger;
    }

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    public function supports(Request $request)
    {
        return $request->headers->has('Authorization');
    }

    public function getCredentials(Request $request)
    {
        return $request->headers->get('Authorization');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
       $this->logger->info(print_r($credentials));
        try {
            $credentials = str_replace('Bearer ', '', $credentials);
            $jwt = (array) JWT::decode(
                $credentials,
                $this->params->get('jwt_secret'),
                ['HS256']
            );
            return $this->em->getRepository(User::class)
                ->findOneBy([
                    'email' => $jwt['user'],
                ]);
        }catch (Exception $exception) {
            throw new AuthenticationException($exception->getMessage());
        }
    }

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => $exception->getMessage()
        ], Response::HTTP_UNAUTHORIZED);
    }

    /** @noinspection PhpUnnecessaryReturnInspection
     * @noinspection UselessReturnInspection
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return;
    }

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    public function supportsRememberMe()
    {
        return false;
    }
}