<?php

declare(strict_types=1);

namespace UIAwesome\FormModel\Tests;

use UIAwesome\FormModel\FieldError;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class FieldErrorTest extends \PHPUnit\Framework\TestCase
{
    public function testAdd(): void
    {
        $fieldError = new FieldError();

        $fieldError->add('username', 'Invalid username.');

        $this->assertTrue($fieldError->has('username'));
        $this->assertSame('Invalid username.', $fieldError->getProperty('username', true));
    }

    public function testClear(): void
    {
        $fieldError = new FieldError();

        $fieldError->add('username', 'Username is required');
        $fieldError->add('email', 'Email is required');

        $this->assertSame(
            [
                'username' => ['Username is required'],
                'email' => ['Email is required'],
            ],
            $fieldError->get()
        );

        $fieldError->clear();

        $this->assertEmpty($fieldError->get());
    }

    public function testClearWithProperty(): void
    {
        $fieldError = new FieldError();

        $fieldError->add('username', 'Username is required');
        $fieldError->add('email', 'Email is required');

        $this->assertSame(
            [
                'username' => ['Username is required'],
                'email' => ['Email is required'],
            ],
            $fieldError->get()
        );

        $fieldError->clear('username');

        $this->assertSame([
            'email' => ['Email is required'],
        ], $fieldError->get());
    }

    public function testGet(): void
    {
        $fieldError = new FieldError();

        $this->assertSame([], $fieldError->get());

        $fieldError->set(
            [
                'username' => ['The field is required', 'Invalid username'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );

        $this->assertSame(
            [
                'username' => ['The field is required', 'Invalid username'],
                'email' => ['Invalid email', 'The field is required'],
            ],
            $fieldError->get()
        );

        $this->assertSame(
            [
                'username' => 'The field is required',
                'email' => 'Invalid email',
            ],
            $fieldError->get(true)
        );
    }

    public function testGetEmpty(): void
    {
        $fieldError = new FieldError();

        $this->assertEmpty($fieldError->get());
        $this->assertEmpty($fieldError->get(true));
    }

    public function testGetProperty(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['Invalid username', 'The field is required'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );

        $this->assertSame(['Invalid username', 'The field is required'], $fieldError->getProperty('username'));
    }

    public function testGetPropertyWithEmpty(): void
    {
        $fieldError = new FieldError();

        $this->assertEmpty($fieldError->getProperty('username'));
        $this->assertEmpty($fieldError->getProperty('username', true));
    }

    public function testGetPropertyWithFirstTrue(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['Invalid username', 'The field is required'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );


        $this->assertNotSame('The field is required', $fieldError->getProperty('username', true));
        $this->assertSame('Invalid username', $fieldError->getProperty('username', true));
    }

    public function testGetSummary(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['Invalid username'],
                'email' => ['Invalid email'],
            ],
        );

        $this->assertSame(['Invalid username', 'Invalid email'], $fieldError->getSummary());
    }

    public function testGetSummaryWithEmpty(): void
    {
        $fieldError = new FieldError();

        $this->assertSame([], $fieldError->getSummary());
    }

    public function testGetSummaryWithOnlyProperty(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['Invalid username'],
                'email' => ['Invalid email'],
            ],
        );

        $this->assertSame(['Invalid username'], $fieldError->getSummary(['username']));
    }

    public function testGetSummaryWithFirstTrue(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['The field is required', 'Invalid username'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );

        $this->assertSame(
            [
                'username' => 'The field is required',
                'email' => 'Invalid email',
            ],
            $fieldError->getSummary(first: true)
        );
    }

    public function testGetWithFirstTrue(): void
    {
        $fieldError = new FieldError();

        $fieldError->set(
            [
                'username' => ['Invalid username', 'The field is required'],
                'email' => ['Invalid email', 'The field is required'],
            ],
        );

        $this->assertSame(
            [
                'username' => 'Invalid username',
                'email' => 'Invalid email',
            ],
            $fieldError->get(true)
        );
    }

    public function testHas(): void
    {
        $fieldError = new FieldError();

        $this->assertFalse($fieldError->has());

        $fieldError->add('username', 'Invalid username');

        $this->assertTrue($fieldError->has());
    }

    public function testHasWithProperty(): void
    {
        $fieldError = new FieldError();

        $this->assertFalse($fieldError->has('username'));

        $fieldError->add('username', 'Invalid username');

        $this->assertTrue($fieldError->has('username'));
    }

    public function testHasValidate(): void
    {
        $fieldError = new FieldError();

        $this->assertFalse($fieldError->hasValidate('username'));

        $fieldError->clear('username');

        $this->assertTrue($fieldError->hasValidate('username'));
    }

    public function testSet(): void
    {
        $fieldError = new FieldError();

        $errorContent = [
            'username' => ['Invalid username'],
        ];

        $fieldError->clear();

        $this->assertEmpty($fieldError->getProperty('username'));

        $fieldError->set($errorContent);

        $this->assertTrue($fieldError->has('username'));
        $this->assertSame('Invalid username', $fieldError->getProperty('username', true));
    }
}
