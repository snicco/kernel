<?php

declare(strict_types=1);

namespace Snicco\Component\Kernel\Tests\Configuration;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Snicco\Component\Kernel\Configuration\ReadOnlyConfig;
use Snicco\Component\Kernel\Exception\MissingConfigKey;

/**
 * @internal
 */
final class ReadOnlyConfigTest extends TestCase
{
    /**
     * @test
     */
    public function test_get(): void
    {
        $config = ReadOnlyConfig::fromArray([
            'foo' => 'bar',
            'baz' => [
                'biz' => 'boo',
            ],
        ]);

        $this->assertSame('bar', $config->get('foo'));
        $this->assertSame('boo', $config->get('baz.biz'));
    }

    /**
     * @test
     */
    public function test_get_on_missing_key_throws_exception(): void
    {
        $config = ReadOnlyConfig::fromArray([
            'foo' => 'bar',
            'baz' => [
                'biz' => 'boo',
            ],
        ]);

        $this->assertSame('bar', $config->get('foo'));

        $this->expectException(MissingConfigKey::class);
        $this->expectExceptionMessage('The key [bar] does not exist in the configuration.');

        $config->get('bar');
    }

    /**
     * @test
     */
    public function test_get_string(): void
    {
        $config = ReadOnlyConfig::fromArray([
            'foo' => 'bar',
            'baz' => 1,
        ]);

        $this->assertSame('bar', $config->getString('foo'));

        $this->expectException(InvalidArgumentException::class);

        $config->getString('baz');
    }

    /**
     * @test
     */
    public function test_get_int(): void
    {
        $config = ReadOnlyConfig::fromArray([
            'foo' => 1,
            'baz' => 'biz',
        ]);

        $this->assertSame(1, $config->getInteger('foo'));

        $this->expectException(InvalidArgumentException::class);

        $config->getInteger('baz');
    }

    /**
     * @test
     */
    public function test_get_array(): void
    {
        $config = ReadOnlyConfig::fromArray([
            'foo' => ['bar'],
            'baz' => 'biz',
        ]);

        $this->assertSame(['bar'], $config->getArray('foo'));

        $this->expectException(InvalidArgumentException::class);

        $config->getArray('baz');
    }

    /**
     * @test
     */
    public function test_boolean(): void
    {
        $config = ReadOnlyConfig::fromArray([
            'foo' => [
                'bar' => true,
            ],
            'baz' => false,
            'biz' => 'boo',
        ]);

        $this->assertFalse($config->getBoolean('baz'));
        $this->assertTrue($config->getBoolean('foo.bar'));

        $this->expectException(InvalidArgumentException::class);

        $config->getBoolean('biz');
    }

    /**
     * @test
     */
    public function test_get_boolean_or_null(): void
    {
        $config = ReadOnlyConfig::fromArray([
            'foo' => true,
            'baz' => 1,
        ]);

        $this->assertTrue($config->getBooleanOrNull('foo'));

        try {
            $config->getBooleanOrNull('bogus', false);
            $this->fail('Test should have failed here');
        } catch (MissingConfigKey $e) {
            $this->assertStringContainsString('bogus', $e->getMessage());
        }

        try {
            $config->getBooleanOrNull('bogus');
            $this->fail('Test should have failed here');
        } catch (MissingConfigKey $e) {
            $this->assertStringContainsString('bogus', $e->getMessage());
        }

        try {
            $config->getBooleanOrNull('baz');
            $this->fail('Test should have failed here');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('baz', $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function test_get_string_or_null(): void
    {
        $config = ReadOnlyConfig::fromArray([
            'foo' => 'foo',
            'baz' => 1,
        ]);

        $this->assertSame('foo', $config->getStringOrNull('foo'));

        try {
            $config->getStringOrNull('bogus', 'foo');
            $this->fail('Test should have failed here');
        } catch (MissingConfigKey $e) {
            $this->assertStringContainsString('bogus', $e->getMessage());
        }

        try {
            $config->getStringOrNull('bogus');
            $this->fail('Test should have failed here');
        } catch (MissingConfigKey $e) {
            $this->assertStringContainsString('bogus', $e->getMessage());
        }

        try {
            $config->getStringOrNull('baz');
            $this->fail('Test should have failed here');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('baz', $e->getMessage());
        }
    }

    /**
     * @test
     */
    public function test_get_int_or_null(): void
    {
        $config = ReadOnlyConfig::fromArray([
            'foo' => 1,
            'baz' => 'BAZ',
        ]);

        $this->assertSame(1, $config->getIntegerOrNull('foo'));

        try {
            $config->getIntegerOrNull('bogus', 1);
            $this->fail('Test should have failed here');
        } catch (MissingConfigKey $e) {
            $this->assertStringContainsString('bogus', $e->getMessage());
        }

        try {
            $config->getIntegerOrNull('bogus');
            $this->fail('Test should have failed here');
        } catch (MissingConfigKey $e) {
            $this->assertStringContainsString('bogus', $e->getMessage());
        }

        try {
            $config->getIntegerOrNull('baz');
            $this->fail('Test should have failed here');
        } catch (InvalidArgumentException $e) {
            $this->assertStringContainsString('baz', $e->getMessage());
        }
    }
}
