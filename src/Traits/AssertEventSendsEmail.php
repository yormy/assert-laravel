<?php declare(strict_types=1);

namespace Yormy\AssertLaravel\Traits;

use Mexion\TestappCore\Domain\Tribe\Notifications\InviteMember\InviteMemberNotification;

trait AssertEventSendsEmail
{
    protected function assertEventWillSendEmail(string $toEmail, array $withContent = [])
    {
        $mailer = $this->app->make('mailer');

        /**
         * @var \Symfony\Component\Mailer\Transport\TransportInterface $symfonyTransport
         */
        $symfonyTransport = \Mockery::mock($mailer->getSymfonyTransport());
        $mailer->setSymfonyTransport($symfonyTransport);

        $symfonyTransport->shouldReceive('send')->once()->withArgs(function ( $mail) use ( $toEmail, $withContent) {
            /**
             * @var array<mixed> $toAddresses
             */
            $toAddresses = $mail->getTo();

            foreach ($toAddresses as $address) {
                if ($address->getAddress() === $toEmail) {
                    $correctTo = true;
                    break;
                }
            }

            if (!$correctTo) {
                $this->assertTrue(false, "Email was not sent to: $toEmail");
            }

            $html = $mail->getHtmlBody();
            foreach ($withContent as $key => $content) {
                if (stripos($html, $content) === false) {
                    $this->assertTrue(false, "Email missing content: $content ($key)");
                }
            }

            return true;
        });
    }
}
