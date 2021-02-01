<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

class InspectionServices
{
    /**
     * @var UserRepository
     */
    public $userRepository;

    /**
     * @var LoggerInterface
     */
    public $logger;

    public function __construct(UserRepository  $userRepository,
                                LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    /**
     * @param Reader $reader
     * @return bool
     */
    public function createUser(Reader $reader): bool
    {
        foreach ($reader->getRecords() as $key => $value) {
            $column[$key] = $value;
            $user = new User();
            $user->setEmail($value['email']);
            $user->setFirstName($value['forename']);
            $user->setLastName($value['surname']);
            $user->setPassword(password_hash('1234', PASSWORD_BCRYPT));
            $user->setRoles(explode(',', $value['roles']));
            $user->setUuid(Uuid::fromString($value['uuid']));

            try {
                $this->userRepository->saveUser($user) ;
            } catch (Exception $e) {
                $this->logger->error('Save user fail ' . $e->getMessage());
                return false;
            }
        }

        return true;
    }
}