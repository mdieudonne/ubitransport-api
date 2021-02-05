<?php


namespace App\Tests\Api;

use App\Entity\Grading\Student;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StudentTest extends WebTestCase
{
  private static ?int $id = null;

  public function testAdd()
  {
    $client = self::createClient();

    $payload = [
      'lastname' => 'Fabien',
      'firstname' => 'Henri',
      'birthdate' => '2020-01-01',
    ];

    $client->request(
      'POST',
      'api/students',
      [],
      [],
      ['CONTENT_TYPE' => 'application/json'],
      json_encode($payload)
    );

    $response = $client->getResponse();

    $this->assertEquals(201, $response->getStatusCode());
    $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    $this->assertJson($response->getContent());

    $data = json_decode($response->getContent(), true);
    self::$id = $data['id'];

    return $data['id'];
  }

  public function testUpdate()
  {
    $client = self::createClient();

    $payload = [
      'lastname' => 'Joe',
      'firstname' => 'Kevin',
      'birthdate' => '2021-01-01',
    ];

    $client->request(
      'PUT',
      'api/students/' . self::$id,
      [],
      [],
      ['CONTENT_TYPE' => 'application/json'],
      json_encode($payload)
    );

    $response = $client->getResponse();

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    $this->assertJson($response->getContent());
  }

  public function testDelete()
  {
    $client = self::createClient();

    $client->request('DELETE', 'api/students/' . self::$id);

    $this->assertEquals(204, $client->getResponse()->getStatusCode());
  }

  public function testAverageByStudent()
  {
    $client = self::createClient();
    $container = $client->getContainer();
    /** @var EntityManager $em */
    $em = $container->get('doctrine.orm.entity_manager');

    /** @var Student $student */
    $student = $em->getRepository(Student::class)->findOneBy([
      'lastname' => 'Casey'
    ]);

    $client->request('GET', 'api/students/'. $student->getId() . '/getAverage');

    $response = $client->getResponse();

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    $this->assertJson($response->getContent());

    $studentAverage = json_decode($response->getContent(), true);
    $this->assertEquals(15, $studentAverage);
  }
}
