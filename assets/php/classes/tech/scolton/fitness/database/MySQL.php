<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 12:48 PM
 */

namespace tech\scolton\fitness\database;

define("DIRNAME", dirname(__FILE__ , 8) . "/");

use tech\scolton\fitness\exception\MySQLException;
use tech\scolton\fitness\exception\UserLoginException;
use tech\scolton\fitness\model\Goal;
use tech\scolton\fitness\model\Message;
use tech\scolton\fitness\model\Team;
use tech\scolton\fitness\model\User;
use tech\scolton\fitness\notification\Action;
use tech\scolton\fitness\notification\NotificationActionable;
use tech\scolton\fitness\notification\ActionType;
use tech\scolton\fitness\notification\Notification;
use tech\scolton\fitness\notification\NotificationType;
use tech\scolton\fitness\util\Config;

class MySQL implements DBProvider
{
    private $mysqli;

    public function __construct() {
        require_once(DIRNAME . "assets/php/var.php");
        $cfg = Config::getConfigSection("db");

        $username = $cfg["username"];
        $password = $cfg["password"];
        $database = $cfg["database"];
        $host = $cfg["host"];
        $port = $cfg["port"];

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
        $username = $user->getUsername();
        $password = $user->getPassword();
        $weight = $user->getWeight();
        $height = $user->getHeight();
        $team = $user->getTeamId();
        $birthday = $user->getBirthday()->format("Y-m-d");
        $units = $user->getUnits();
        $name = $user->getName();

        $query = "INSERT INTO fitness__users (`username`,`password`,`weight`,`height`,`team`,`birthday`,`units`,`name`) VALUES ('$username', '$password', $weight, $height, $team, '$birthday','$units','$name')";

        if ($this->mysqli->query($query)) {
            $id = $this->mysqli->insert_id;

            return $id;
        } else {
            throw new MySQLException("Couldn't insert user into database. Error: " . $this->mysqli->error . " in query " . $query);
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
     * @return int
     * @throws MySQLException
     */
    public function SendNotification(Notification $notification): int
    {
        $content = $this->mysqli->escape_string($notification->getContent());
        $target = $notification->getTarget();
        $type = $notification->getType()->getId();

        if ($notification instanceof NotificationActionable) {
            $action = $notification->getAction()->getType()->getId();
            $aTarget = $notification->getAction()->getTarget();
            $runType = $notification->getRunType();
            if (!$this->mysqli->query("INSERT INTO `fitness__notifications` (`target`, `type`, `content`, `action_type`, `action_target`, `action_run_type`) VALUES ($target, $type, '$content', $action, '$aTarget', '$runType')"))
                throw new MySQLException("Failed to send new actionable notification. Error: ".$this->mysqli->error);
            else
                return $this->mysqli->insert_id;
        } else {
            if (!$this->mysqli->query("INSERT INTO `fitness__notifications` (target, type, content) VALUES ($target, $type, '$content')"))
                throw new MySQLException("Failed to send new notification. Error: ".$this->mysqli->error);
            else
                return $this->mysqli->insert_id;
        }
    }

    /**
     * @param Notification $notification
     * @throws MySQLException
     */
    public function UpdateNotification(Notification $notification) {
        $id = $notification->getId();
        $read = $notification->isRead() ? 1 : 0;

        if ($notification instanceof NotificationActionable) {
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
            $target = User::get($row["target"]);
            $type = NotificationType::getType($row["type"]);
            $read = $row["read"] == 1 ? true : false;
            $content = $row["content"];
            $actionType = ActionType::getType($row["action_type"]);
            $actionTarget = $row["action_target"];
            $actionRunType = $row["action_run_type"];
            $actionExecuted = $row["action_executed"] == 1 ? true : false;

            $notification = new Notification($id, $content, $target, $read, $type);

            if ($type->isActionable()) {
                assert($notification instanceof NotificationActionable);

                $aClass = $actionType->getClassmap();
                $action = new $aClass($actionTarget, $actionType);
                assert($action instanceof Action);

                $notification->setupAction($action, $actionRunType, $actionExecuted);
            }

            return $notification;
        } else {
            throw new MySQLException("No team exists with id $id or an error was encountered while processing. (".$this->mysqli->error.")");
        }
    }

    public function SetNotificationsRead(int $userId) {
        // TODO: Implement the SetNotificationsRead function
    }

    public function Login(string $username, string $password): int {
        $res = $this->mysqli->query("SELECT * FROM `fitness__users` WHERE `username`='$username'");
        if ($row = $res->fetch_assoc()) {
            if ($row["password"] == $password) {
                return $row["id"];
            } else {
                throw new UserLoginException("Failed to log in user.", 0, null, UserLoginException::INCORRECT_PASSWORD);
            }
        } else {
            throw new UserLoginException("Failed to log in user.", 0, null, UserLoginException::USER_NOT_FOUND);
        }
    }

    public function GetActionTypes(): array {
        $res = $this->mysqli->query("SELECT * FROM `fitness__action_types`");

        $returnArr = [];
        if ($row = $res->fetch_assoc()) {
            do {
                $returnArr[] = $row;
            } while ($row = $res->fetch_assoc());

            return $returnArr;
        } else {
            if ($this->mysqli->errno) {
                throw new MySQLException($this->mysqli->error);
            } else {
                throw new MySQLException("No action types have been defined in the database.");
            }
        }
    }

    public function GetNotificationTypes(): array {
        $res = $this->mysqli->query("SELECT * FROM `fitness__notification_types`");

        $returnArr = [];
        if ($row = $res->fetch_assoc()) {
            do {
                $returnArr[] = $row;
            } while ($row = $res->fetch_assoc());

            return $returnArr;
        } else {
            if ($this->mysqli->errno) {
                throw new MySQLException($this->mysqli->error);
            } else {
                throw new MySQLException("No notification types have been defined in the database.");
            }
        }
    }

    public function SendMessage(Message $message): int {
        // TODO: Implement the SendMessage function
        return null;
    }

    public function GetMessages(int $team, int $offset = 0, int $limit = 50): array {
        // TODO: Implement the GetMessagesFunction
        return null;
    }

    public function GetMessage(int $id): Message {
        // TODO: Implement the GetMessagesFunction
        return null;
    }

    public function GetGoal(int $id): Goal {
		// TODO: Implement GetGoal() method.
		return null;
	}

	public function GetGoals(int $user): array {
		// TODO: Implement GetGoals() method.
		return null;
	}

	public function GetGoalTypes(): array {
		// TODO: Implement GetGoalTypes() method.
		return null;
	}

	public function NewGoal() {
		// TODO: Implement NewGoal() method.
	}

	public function UpdateGoal(int $id) {
		// TODO: Implement UpdateGoal() method.
	}
}