<?php


namespace App\Tests\Api;

use App\Entity\Grading\Student;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScoreTest extends WebTestCase
{
  private static ?int $idStudent = null;
  private static ?int $idScore = null;

  public function testAdd()
  {
    $client = self::createClient();
    $container = $client->getContainer();
    /** @var EntityManager $em */
    $em = $container->get('doctrine.orm.entity_manager');

    /** @var Student $student */
    $student = $em->getRepository(Student::class)->findOneBy(
      [
        'lastname' => 'Casey',
      ]
    );

    self::$idStudent = $student->getId();

    $payload = [
      'idStudent' => self::$idStudent,
      'subject' => 'Math',
      'value' => 18,
    ];

    $client->request(
      'POST',
      'api/scores',
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
    self::$idScore = $data['id'];
  }

  public function testUpdate()
  {
    $client = self::createClient();

    $payload = [
      'subject' => 'Math',
      'value' => '20',
    ];

    $client->request(
      'PUT',
      'api/scores/'.self::$idScore,
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

    $client->request('DELETE', 'api/scores/'.self::$idScore);

    $this->assertEquals(204, $client->getResponse()->getStatusCode());
  }

  public function testAverage()
  {
    $client = self::createClient();

    $client->request('GET', 'api/scores/getAverage');

    $response = $client->getResponse();

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    $this->assertJson($response->getContent());

    $average = json_decode($response->getContent(), true);
    $this->assertEquals(14.5, $average);
  }
}
