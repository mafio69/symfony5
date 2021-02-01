<?php

namespace App\Entity;

use App\Repository\InspectionRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;


/**
 * @ORM\Entity(repositoryClass=InspectionRepository::class)
 */
class Inspection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @var UuidInterface
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="assignee_uuid", referencedColumnName="uuid")
     */
    private $assigneeUuid;

    /**
     * @var UuidInterface
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="author_uuid", referencedColumnName="uuid")
     */
    private $authorUuid;

    /**
     * @ORM\Column(type="date")
     */
    private $inspectionDate;

    public function getId(): ?int
    {
        return $this->id;
    }
}
