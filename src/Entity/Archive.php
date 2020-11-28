<?php

namespace App\Entity;

use App\Repository\ArchiveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArchiveRepository::class)
 */
class Archive
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="object")
     */
    private $project_archive;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="archives")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectArchive()
    {
        return $this->project_archive;
    }

    public function setProjectArchive($project_archive): self
    {
        $this->project_archive = $project_archive;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
