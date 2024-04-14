<?php declare(strict_types=1);

namespace Yormy\AssertLaravel\Traits;

use Mexion\TestappCore\Domain\Tribe\Notifications\InviteMember\InviteMemberNotification;

trait AssertNotificationContainsData
{
//    private function assertNotificationContainsData($user, $project)
//    {
//        Notification::assertSentTo($user, InviteMemberNotification::class, function ($notification, $channels) use ($project) {
//            $asMail = in_array('mail', $channels);
//            return true;
//            //$mailData = $notification->toMail($user);
//
//            if ($asMail && $notification->getData()->custom['project_name'] === $project->name) {
//                return true;
//            }
//
//            return false;
//        });
//    }
}
