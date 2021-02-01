<?php


use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;

class StudentService
{
  private EntityManagerInterface $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function add(array $data): Student
  {
    $student = new Student();
    $student->setFirstname($data['firstname']);
    $student->setLastname($data['lastname']);
    $student->setBirthdate(new DateTime($data['birthdate']));

    $this->em->persist($student);
    $this->em->flush();

    return $student;
  }

  public function update(array $data, int $id): Student
  {
    /** @var Student $student */
    $student = $this->em->getRepository(Student::class)->find($id);
    $student->setFirstname($data['firstname']);
    $student->setLastname($data['lastname']);
    $student->setBirthdate(new DateTime($data['birthdate']));

    $this->em->flush();

    return $student;
  }

  public function delete(int $id): void
  {
    $student = $this->em->getRepository(Student::class)->find($id);
    $this->em->remove($student);
    $this->em->flush();
  }

}
