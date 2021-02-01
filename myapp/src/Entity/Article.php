<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"article:list_articles"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=false)
     * @Groups({"article:get_article", "article:list_articles"})
     * @Assert\NotBlank(message = "The name cannot be empty")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"article:get_article", "article:list_articles"})
     * @Assert\Length(
     *      min = 150,
     *      max = 10000,
     *      minMessage = "Description must be at least {{ limit }} characters long",
     *      maxMessage = "Description cannot be longer than {{ limit }} characters"
     * )
     */
    private $description;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

}
