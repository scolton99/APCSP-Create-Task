<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 12:45 PM
 */

namespace tech\scolton\fitness\database;


use tech\scolton\fitness\model\Team;
use tech\scolton\fitness\model\User;
use tech\scolton\fitness\notification\Notification;

interface DBProvider
{
    function WriteUserData(User $user);
    function WriteTeamData(Team $team);
    function NewUser(User $user);
    function NewTeam(Team $team);
    function GetTeam(int $id): array;
    function GetUser(int $id): array;
    function GetAllUsersOnTeam(int $id): array;
    function SendNotification(Notification $notification);
    function UpdateNotification(Notification $notification);
    function GetNotification(int $id): Notification;

    function Login(string $username, string $password): int;
}