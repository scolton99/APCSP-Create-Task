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
use tech\scolton\fitness\notification\Action;
use tech\scolton\fitness\notification\ActionableNotification;
use tech\scolton\fitness\notification\ActionTypes;
use tech\scolton\fitness\notification\Notification;
use tech\scolton\fitness\notification\NotificationTypes;
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
        $birthday = $user->getBirthday();
        $units = $user->getUnits();
        $name = $user->getName();

        if (!$this->mysqli->query("UPDATE `fitness__users` SET (`username`='$username', `password`='$password', `weight`=$weight, `height`=$height, `team`=$team, `birthday`='$birthday', `units`='$units',`name`='$name') WHERE `id`=$id")) {
            throw new MySQLException("Failed to write new user data.");
        }
    }

    public function NewUser(User $user): int {
        // TODO: make this function throw a MySQLException instead of returning -1

        $username = $user->getUsername();
        $password = $user->getPassword();
        $weight = $user->getWeight();
        $height = $user->getHeight();
        $team = $user->getTeamId();
        $birthday = $user->getBirthday();
        $units = $user->getUnits();
        $name = $user->getName();

        if ($this->mysqli->query("INSERT INTO fitness__users (`username`,`password`,`weight`,`height`,`team`,`birthday`,`units`,`name`) VALUES ('$username', '$password', $weight, $height, $team, '$birthday','$units','$name')")) {
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

    /**
     * @param Notification $notification
     * @throws MySQLException
     */
    public function SendNotification(Notification $notification)
    {
        $content = $this->mysqli->escape_string($notification->getContent());
        $target = $notification->getTarget();
        $type = $this->mysqli->escape_string($notification->getType());

        if ($notification instanceof ActionableNotification) {
            $action = $notification->getAction()->getType();
            $aTarget = $notification->getAction()->getTarget();
            $runType = $notification->getRunType();
            if (!$this->mysqli->query("INSERT INTO `fitness__notifications` (target, type, content, `action`, `action_target`, action_type) VALUES ($target, '$type', '$content', '$action', '$aTarget', '$runType')"))
                throw new MySQLException("Failed to send new actionable notification. Error: ".$this->mysqli->error);
        } else {
            if (!$this->mysqli->query("INSERT INTO `fitness__notifications` (target, type, content) VALUES ($target, '$type', '$content')"))
                throw new MySQLException("Failed to send new notification. Error: ".$this->mysqli->error);
        }
    }

    /**
     * @param Notification $notification
     * @throws MySQLException
     */
    public function UpdateNotification(Notification $notification) {
        $id = $notification->getId();
        $read = $notification->isRead() ? 1 : 0;

        if ($notification instanceof ActionableNotification) {
            $executed = $notification->isExecuted() ? 1 : 0;

            if (!$this->mysqli->query("UPDATE `fitness__notifications` SET (`read`=$read, `action_executed`=$executed) WHERE `id`=$id"))
                throw new MySQLException("Failed to update actionable notification with id $id. Error: ".$this->mysqli->error);
        } else {
            if (!$this->mysqli->query("UPDATE `fitness__notifications` SET (`read`=$read) WHERE `id`=$id"))
                throw new MySQLException("Failed to update notification with id $id. Error: ".$this->mysqli->error);
        }
    }

    public function GetNotification(int $id): Notification {
        $res = $this->mysqli->query("SELECT * FROM `fitness__notifications` WHERE `id`=$id");

        if ($row = $res->fetch_assoc()) {
            $read = $row["read"] == 1 ? true : false;
            $target = $row["target"];
            $type = $row["type"];
            $content = $row["content"];
            $actionStr = $row["action"];
            $actionTarget = $row["action_target"];
            $actionType = $row["action_type"];
            $actionExecuted = $row["action_executed"] == 1 ? true : false;

            if ($actionStr == "NONE") {
                $class = NotificationTypes::MAP[$type];
                $notification = new $class();
                assert($notification instanceof Notification);
                $notification->setup($id, $content, User::get($target), $read);

                return $notification;
            } else {
                $class = NotificationTypes::MAP[$type];
                $notification = new $class();
                assert($notification instanceof Notification);
                assert($notification instanceof ActionableNotification);

                $aClass = ActionTypes::MAP[$actionStr];
                $action = new $aClass();
                assert($action instanceof Action);

                $action->setup($actionTarget, $actionStr);
                $notification->setup($id, $content, User::get($target), $read);
                $notification->setupAction($action, $actionType, $actionExecuted);

                return $notification;
            }
        } else {
            throw new MySQLException("No team exists with id $id or an error was encountered while processing. (".$this->mysqli->error.")");
        }
    }

    public function Login(string $username, string $password): int {
        $res = $this->mysqli->query("SELECT * FROM `fitness__users` WHERE `username`='$username' AND `password`='$password'");
        if ($row = $res->fetch_assoc()) {
            return $row["id"];
        } else {
            return -1;
        }
    }
}