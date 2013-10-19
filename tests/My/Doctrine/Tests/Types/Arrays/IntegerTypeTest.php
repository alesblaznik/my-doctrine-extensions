<?php

namespace My\Doctrine\Tests\Types\Arrays;

use Doctrine\DBAL\Types\Type;
use My\Doctrine\Tests\MyDoctrineTestCase;

class IntegerTypeTest extends MyDoctrineTestCase
{
    protected
        $_platform,
        $_type;

    protected function setUp()
    {
        $this->_platform = new \Doctrine\DBAL\Platforms\PostgreSqlPlatform();

        // Add new type and retrieve it
        $typeName = \My\Doctrine\Types\Arrays\IntegerType::TYPENAME;

        if (Type::hasType($typeName)) {
            Type::overrideType($typeName, 'My\Doctrine\Types\Arrays\IntegerType');
        } else {
            Type::addType($typeName, 'My\Doctrine\Types\Arrays\IntegerType');
        }

        $this->_type = Type::getType(\My\Doctrine\Types\Arrays\IntegerType::TYPENAME);
    }

    public function testInvalidPlatform()
    {
        $this->setExpectedException('My\Doctrine\Types\TypesException');
        $this->_type->getSQLDeclaration(array(), new \Doctrine\DBAL\Platforms\MySqlPlatform());
    }

    public function testSqlDeclaration()
    {
        $this->assertEquals('INT[]', $this->_type->getSQLDeclaration(array(), $this->_platform));
    }

    public function testNullToDatabaseConversion()
    {
        $this->assertEquals('NULL', $this->_type->convertToDatabaseValue(null, $this->_platform));
    }

    public function testSingleValueToDatabaseConversion()
    {
        $this->assertEquals(
            'ARRAY[123]',
            $this->_type->convertToDatabaseValue(123, $this->_platform)
        );
    }

    public function testSingleValueOfZeroToDatabaseConversion()
    {
        $this->assertEquals(
            'ARRAY[0]',
            $this->_type->convertToDatabaseValue(0, $this->_platform)
        );
    }

    public function testArrayOfValuesToDatabaseValue()
    {
        $this->assertEquals(
            'ARRAY[123,456,789]',
            $this->_type->convertToDatabaseValue(
                array(123, 456, 789),
                $this->_platform)
        );
    }

    public function testNullValueToPHPValue()
    {
        $this->assertEquals(
            null,
            $this->_type->convertToPHPValue(
                null,
                $this->_platform)
        );
    }

    public function testSingleArrayValueToPHPValue()
    {
        $this->assertEquals(
            array(0),
            $this->_type->convertToPHPValue(
                '{0}',
                $this->_platform
            )
        );
    }

    public function testArrayValueToPHPValue()
    {
        $this->assertEquals(
            array(123, 456, 789),
            $this->_type->convertToPHPValue(
                '{123, 456,789}',
                $this->_platform
            )
        );
    }
}
