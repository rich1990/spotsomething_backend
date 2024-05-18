<?php
namespace App\Tests\Entity;

use App\Entity\Flats;
use PHPUnit\Framework\TestCase;

/**
 * Class FlatsTest
 * @package App\Tests\Entity
 */
class FlatsTest extends TestCase
{
    /**
     * Test getter and setter methods of Flats entity
     */
    public function testGetterAndSetter(): void
    {
        // Create a new instance of the Flats entity
        $flats = new Flats();

        // Set data using setter methods
        $flats->setName('Test Name');
        $flats->setImg('test.jpg');
        $flats->setDescription('Test Description');
        $flats->setCity('Test City');

        // Assert data using getter methods
        $this->assertEquals('Test Name', $flats->getName());
        $this->assertEquals('test.jpg', $flats->getImg());
        $this->assertEquals('Test Description', $flats->getDescription());
        $this->assertEquals('Test City', $flats->getCity());
    }
}