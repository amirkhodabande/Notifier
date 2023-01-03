<?php

namespace Amir\Notifier\Tests\Unit;

use Amir\Notifier\Messages\ValueObjects\MailMessage;
use Amir\Notifier\Tests\TestCase;
use Exception;
use Illuminate\Foundation\Testing\WithFaker;

class MailMessageTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function subject_max_length_validation()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Subject cant be greater than 250 characters.');

        new MailMessage($this->faker->sentence(50), $this->faker->sentence(3));
    }
}