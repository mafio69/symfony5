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

    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        $data = [
            'message' => 'Authentication Required'
        ];
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization');
    }

    public function getCredentials(Request $request): ?string
    {
        return $request->headers->get('Authorization');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
       $this->logger->info($credentials);
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

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        return new JsonResponse([
            'message' => $exception->getMessage()
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return Response|null
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}