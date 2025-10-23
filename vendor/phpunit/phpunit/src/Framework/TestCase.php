<?php

namespace PHPUnit\Framework;

class AssertionFailedError extends \Exception
{
}

abstract class TestCase
{
    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {
    }

    public function runTest(string $method): void
    {
        $this->setUp();
        try {
            $this->{$method}();
        } finally {
            $this->tearDown();
        }
    }

    protected function assertTrue($condition, string $message = ''): void
    {
        if (!$condition) {
            throw new AssertionFailedError($message ?: 'Failed asserting that condition is true.');
        }
    }

    protected function assertEquals($expected, $actual, string $message = ''): void
    {
        if ($expected != $actual) {
            throw new AssertionFailedError($message ?: 'Failed asserting that values are equal.');
        }
    }

    protected function assertSame($expected, $actual, string $message = ''): void
    {
        if ($expected !== $actual) {
            throw new AssertionFailedError($message ?: 'Failed asserting that values are identical.');
        }
    }

    protected function assertCount(int $expectedCount, $haystack, string $message = ''): void
    {
        $count = is_countable($haystack) ? count($haystack) : 0;
        if ($count !== $expectedCount) {
            throw new AssertionFailedError($message ?: "Failed asserting count of {$expectedCount}, got {$count}.");
        }
    }

    protected function assertNotEmpty($value, string $message = ''): void
    {
        if (empty($value)) {
            throw new AssertionFailedError($message ?: 'Failed asserting value is not empty.');
        }
    }
}
