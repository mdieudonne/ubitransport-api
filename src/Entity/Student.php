<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstname;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTime $birthdate;

    /**
     * @ORM\OneToMany(targetEntity=Score::class, mappedBy="student", orphanRemoval=true)
     */
    private ArrayCollection $scores;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthdate(): ?\DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTime $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * @return Collection|Score[]
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): self
    {
        if (!$this->scores->contains($score)) {
            $this->scores[] = $score;
            $score->setStudent($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->removeElement($score)) {
            // set the owning side to null (unless already changed)
            if ($score->getStudent() === $this) {
                $score->setStudent(null);
            }
        }

        return $this;
    }
}
