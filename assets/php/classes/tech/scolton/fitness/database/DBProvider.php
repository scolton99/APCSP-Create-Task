<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 12:45 PM
 */

namespace tech\scolton\fitness\database;


use tech\scolton\fitness\model\Goal;
use tech\scolton\fitness\model\Message;
use tech\scolton\fitness\model\Team;
use tech\scolton\fitness\model\User;
use tech\scolton\fitness\notification\Notification;

interface DBProvider
{
    function WriteUserData(User $user);
    function NewUser(User $user);
    function GetUser(int $id): array;

    function UserHasGoals(User $user): bool;

    function WriteTeamData(Team $team);
    function NewTeam(Team $team);
    function GetTeam(int $id): array;

    function GetAllUsersOnTeam(int $id): array;

    function SendNotification(Notification $notification): int;
    function UpdateNotification(Notification $notification);
    function GetNotification(int $id): Notification;
    function SetNotificationsRead(User $user);

    function GetActionTypes(): array;
    function GetNotificationTypes(): array;

    function GetMessage(int $id): Message;
    function GetMessages(int $team, int $offset = 0, int $limit = 50): array;
    function SendMessage(Message $message): int;

    function GetGoalTypes(): array;
    function GetGoalSuperTypes(): array;

    function GetGoal(int $id): Goal;
    function NewGoal(int $id, User $user, float $amount): int;
    function UpdateGoal(Goal $goal);
    function GetGoals(int $user): array;
    function GetGoalStatusWeek(\DateTime $start, int $id): float;
    function GetGoalStatusDay(\DateTime $day, int $id): float;
    function GetGoalProgressDay(\DateTime $day, int $id): float;
    function GetGoalProgressWeek(\DateTime $start, int $id): float;
    function SetGoalProgress(Goal $goal, float $progress);
    function GetGoalAmountForDate(Goal $goal, \DateTime $start): float;
    function GetDefaultGoalAmount(Goal $goal): float;

    function Login(string $username, string $password): int;

    function FindTeam(): int;
}