<?php

namespace App\Entity\Grading;

use App\Repository\Grading\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ScoreRepository::class)
 * @ORM\Table(name="score",schema="grading")
 */
class Score
{
  const MIN_VALUE = 0;
  const MAX_VALUE = 20;

  /**
   * @Groups({"score"})
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   * @ORM\Column(type="integer")
   */
  private int $id;

  /**
   * @Groups({"score"})
   * @ORM\Column(type="float")
   * @Assert\Range(
   *   min = self::MIN_VALUE,
   *   max = self::MAX_VALUE,
   * )
   */
  private float $value;

  /**
   * @Groups({"score"})
   * @ORM\Column(type="string", length=50)
   * @Assert\NotBlank
   */
  private string $subject;

  /**
   * @Groups({"score_student"})
   * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="scores")
   * @ORM\JoinColumn(nullable=false)
   */
  private Student $student;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getValue(): ?float
  {
    return $this->value;
  }

  public function setValue(float $value): self
  {
    $this->value = $value;

    return $this;
  }

  public function getSubject(): ?string
  {
    return $this->subject;
  }

  public function setSubject(string $subject): self
  {
    $this->subject = $subject;

    return $this;
  }

  public function getStudent(): ?Student
  {
    return $this->student;
  }

  public function setStudent(Student $student): self
  {
    $this->student = $student;

    return $this;
  }
}
