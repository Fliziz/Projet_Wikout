<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Entity\Contact;

final class ContactTest extends TestCase
{
    public function testEmailIsString(): void
    {
        $contact = new Contact;

        $email = $contact->setEmail('test@gmail.com');

        $this->assertStringContainsString('test@gmail.com', $contact->getEmail());

    }

}