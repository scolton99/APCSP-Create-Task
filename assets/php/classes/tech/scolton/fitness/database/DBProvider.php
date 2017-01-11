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

interface DBProvider
{
    function WriteUserData(User $user);
    function WriteTeamData(Team $team);
    function NewUser(User $user);
    function NewTeam(Team $team);
    function GetTeam(int $id): array;
    function GetUser(int $id): array;
    function GetAllUsersOnTeam(int $id): array;
}