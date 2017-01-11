<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 12:48 PM
 */

namespace tech\scolton\fitness\database;


use tech\scolton\fitness\exception\MySQLException;
use tech\scolton\fitness\model\Team;
use tech\scolton\fitness\model\User;
use tech\scolton\fitness\util\Config;

class MySQL implements DBProvider
{
    private $mysqli;

    public function __construct() {
        require_once("../../../../../var.php");
        $cfg = Config::getConfigSection("DB");

        $username = $cfg["Username"];
        $password = $cfg["Password"];
        $database = $cfg["Database"];
        $host = $cfg["Host"];
        $port = $cfg["Port"];

        $this->mysqli = new \mysqli($host, $username, $password, $database, $port);
    }

    public function WriteTeamData(Team $team)
    {
        $id = $team->getId();
        $name = $team->getName();

        if (!$this->mysqli->query("UPDATE `fitness__teams` SET (`name`='$name') WHERE `id`=$id")) {
            throw new MySQLException("Failed to write new team data.");
        }
    }

    public function WriteUserData(User $user)
    {
        $id = $user->getId();
        $username = $user->getUsername();
        $password = $user->getPassword();
        $height = $user->getHeight();
        $weight = $user->getWeight();
        $team = $user->getTeamId();

        if (!$this->mysqli->query("UPDATE `fitness__users` SET (`username`='$username', `password`='$password', `weight`=$weight, `height`=$height, `team`=$team) WHERE `id`=$id")) {
            throw new MySQLException("Failed to write new user data.");
        }
    }

    public function NewUser(User $user): int {
        $username = $user->getUsername();
        $password = $user->getPassword();
        $weight = $user->getWeight();
        $height = $user->getHeight();
        $team = $user->getTeamId();

        if ($this->mysqli->query("INSERT INTO fitness__users (`username`,`password`,`weight`,`height`,`team`) VALUES ('$username', '$password', $weight, $height, $team)")) {
            $id = $this->mysqli->insert_id;

            return $id;
        } else {
            return -1;
        }
    }

    public function NewTeam(Team $team): int
    {
        $name = $team->getName();

        if ($this->mysqli->query("INSERT INTO fitness__teams (`name`) VALUES ('$name')")) {
            $id = $this->mysqli->insert_id;

            return $id;
        } else {
            return -1;
        }
    }

    public function GetTeam(int $id): array
    {
        $res = $this->mysqli->query("SELECT * FROM `fitness__teams` WHERE `id`=$id");
        if ($row = $res->fetch_assoc()) {
            return $row;
        } else {
            throw new MySQLException("No team found with id $id.");
        }
    }

    public function GetUser(int $id): array
    {
        $res = $this->mysqli->query("SELECT * FROM `fitness__users` WHERE `id`=$id");
        if ($row = $res->fetch_assoc()) {
            return $row;
        } else {
            throw new MySQLException("No user found with id $id.");
        }
    }

    public function GetAllUsersOnTeam(int $id): array {
        $res = $this->mysqli->query("SELECT `id` FROM `fitness__users` WHERE `team`=$id");
        if ($row = $res->fetch_assoc()) {
            $users = [];
            do {
                $id = $row["id"];
                $users[] = User::get($id);
            } while ($row = $res->fetch_assoc());
            return $users;
        } else {
            throw new MySQLException("No team existed with id $id or team has no members.");
        }
    }
}