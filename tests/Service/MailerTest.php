<?php declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\Mailer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerTest extends TestCase
{
    public function testSendWelcomeMessage(): void
    {
        $fromToAdmin = 'admin@serie.fr';

        $symfonyMailer = $this->createMock(MailerInterface::class);
        $symfonyMailer->expects($this->once())
            ->method('send');
        $params = $this->createMock(ParameterBagInterface::class);
        $params->expects($this->once())
            ->method('get')
            ->willReturn($fromToAdmin);
        $user = new User();
        $user->setEmail('user@email.fr');
        $mailer = new Mailer($symfonyMailer, $params);

        $email = $mailer->sendWelcomeMessage($user);
        $this->assertSame('Welcome to Serie!', $email->getSubject());
        $this->assertCount(1, $email->getTo());
        /** @var Address $address */
        $address = $email->getTo()[0];
        $this->assertSame($user->getEmail(), $address->getAddress());
    }
}
