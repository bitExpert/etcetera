<?php
declare(strict_types = 1);

/*
 * This file is part of the Etcetera package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bitExpert\Etcetera\Source\Descriptor;

use bitExpert\Etcetera\Extractor\Source\Descriptor\PropertyDescriptor;

class PropertyDescriptorUnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function returnsNameSetViaConstructor()
    {
        $descriptor = new PropertyDescriptor(0, 'property');
        $this->assertEquals('property', $descriptor->getName());
    }

    /**
     * @test
     */
    public function returnsOccuranceSetInConstructor()
    {
        $descriptor = new PropertyDescriptor(0, 'property', 5);
        $this->assertEquals(5, $descriptor->getOccurance());
    }

    /**
     * @test
     */
    public function returnsIndexSetInConstructor()
    {
        $descriptor = new PropertyDescriptor(2, 'property', 5);
        $this->assertEquals(2, $descriptor->getIndex());
    }

    /**
     * @test
     */
    public function matchesIfGivenParamsMatch()
    {
        $descriptor = new PropertyDescriptor(0, 'property', 3);
        $this->assertTrue($descriptor->matches('property', 3));
    }

    /**
     * @test
     */
    public function doesNotMatchIfNameDoesNotMatch()
    {
        $descriptor = new PropertyDescriptor(0, 'property', 3);
        $this->assertFalse($descriptor->matches('anotherproperty', 3));
    }

    /**
     * @test
     */
    public function doesNotMatchIfOccuranceDoesNotMatch()
    {
        $descriptor = new PropertyDescriptor(0, 'property', 3);
        $this->assertFalse($descriptor->matches('property', 2));
    }

    /**
     * @test
     */
    public function doesNotMatchIfNameAndOccuranceDoNotMatch()
    {
        $descriptor = new PropertyDescriptor(0, 'property', 3);
        $this->assertFalse($descriptor->matches('anotherproperty', 2));
    }
}
