<?php

namespace App\Entity\Grading;

use App\Repository\Grading\StudentRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 * @ORM\Table(name="student",schema="grading")
 */
class Student
{
  /**
   * @Groups({"student"})
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   * @ORM\Column(type="integer")
   */
  private int $id;

  /**
   * @Groups({"student"})
   * @ORM\Column(type="string", length=50)
   * @Assert\NotBlank
   */
  private string $lastname;

  /**
   * @Groups({"student"})
   * @ORM\Column(type="string", length=50)
   * @Assert\NotBlank
   */
  private string $firstname;

  /**
   * @Groups({"student"})
   * @ORM\Column(type="date")
   */
  private DateTime $birthdate;

  /**
   * @Groups({"student_score"})
   * @ORM\OneToMany(targetEntity=Score::class, mappedBy="student", orphanRemoval=true)
   */
  private $scores;

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

  public function getBirthdate(): ?DateTime
  {
    return $this->birthdate;
  }

  public function setBirthdate(DateTime $birthdate): self
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
