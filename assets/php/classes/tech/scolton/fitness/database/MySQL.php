<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 12:48 PM
 */

namespace tech\scolton\fitness\database;

define("MYSQLDIR", dirname(__FILE__ , 8) . "/");

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
        require_once(MYSQLDIR . "assets/php/var.php");
        $cfg = Config::getConfigSection("db");

        $username = $cfg["username"];
        $password = $cfg["password"];
        $database = $cfg["database"];
        $host = $cfg["host"];
        $port = $cfg["port"];

        $this->mysqli = new \mysqli($host, $username, $password, $database, $port);
    }

    public function WriteTeamData(Team $team) {
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
        $birthday = $user->getBirthday()->format("Y-m-d");
        $units = $user->getUnits();
        $name = $user->getName();

        if (!$this->mysqli->query("UPDATE `fitness__users` SET `username`='$username', `password`='$password', `weight`=$weight, `height`=$height, `team`=$team, `birthday`='$birthday', `units`='$units',`name`='$name' WHERE `id`=$id")) {
            throw new MySQLException("Failed to write new user data. ".$this->mysqli->error);
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

    public function SetNotificationsRead(User $user) {
		$id = $user->getId();
		$this->mysqli->query("UPDATE `fitness__notifications` SET `read`=1 WHERE `target`=$id");
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
		$res = $this->mysqli->query("SELECT * FROM `fitness__goals` WHERE `id`=$id");

		if ($row = $res->fetch_assoc()) {
			return new Goal($row);
		} else {
			throw new MySQLException("No goal found with id $id.");
		}
	}

	public function GetGoals(int $user): array {
		$res = $this->mysqli->query("SELECT * FROM `fitness__goals` WHERE `user`=$user");
		$goals = [];

		if ($row = $res->fetch_assoc()) {
			do {
				$goals[] = $row;
			} while ($row = $res->fetch_assoc());
		} else {
			throw new MySQLException("No goals found in database for user with id $user");
		}

		return $goals;
	}

	public function GetGoalTypes(): array {
		$res = $this->mysqli->query("SELECT * FROM `fitness__goal_types`");

		if ($row = $res->fetch_assoc()) {
			$arr = [];

			do {
				$arr[] = $row;
			} while ($row = $res->fetch_assoc());

			return $arr;
		} else {
			if ($this->mysqli->errno) {
				throw new MySQLException($this->mysqli->error);
			} else {
				throw new MySQLException("No goal types were defined in the database.");
			}
		}
	}

	public function GetGoalSuperTypes(): array {
		$res = $this->mysqli->query("SELECT * FROM `fitness__goal_supertypes`");

		if ($row = $res->fetch_assoc()) {
			$arr = [];

			do {
				$arr[] = $row;
			} while ($row = $res->fetch_assoc());

			return $arr;
		} else {
			if ($this->mysqli->errno) {
				throw new MySQLException($this->mysqli->error);
			} else {
				throw new MySQLException("No goal supertypes were defined in the database.");
			}
		}
	}

	public function NewGoal(int $id, User $user, float $amount): int {
        $uid = $user->getId();

		$this->mysqli->query("INSERT INTO `fitness__goals` (`type`, `goal_amount`, `user`) VALUES ($id, $amount, $uid)");

		return $this->mysqli->insert_id;
	}

	public function UpdateGoal(Goal $goal) {
		// TODO: Implement UpdateGoal() method.
	}

	public function GetGoalStatusWeek(\DateTime $start, int $id): float {
		$date = $this->GetFirstDayOfWeek($start)->format("Y-m-d");
		$res = $this->mysqli->query("SELECT `goal_progress`,`goal_amount` FROM `fitness__goal_weeks` WHERE `start_date`='$date' AND `goal`=$id");

		if ($row = $res->fetch_assoc()) {
			$progress = $row["goal_progress"];
			$amount = $row["goal_amount"];

			return $progress / $amount;
		} else {
            $amt = $this->GetDefaultGoalAmount(Goal::get($id));

            $this->mysqli->query("INSERT INTO `fitness__goal_weeks` (`goal_progress`, `goal_amount`, `start_date`, `goal`) VALUES (0.0, $amt, '$date', $id)");
		    return 0.0;
		}
	}

	public function GetGoalProgressWeek(\DateTime $start, int $id): float {
        $date = $this->GetFirstDayOfWeek($start)->format("Y-m-d");
        $res = $this->mysqli->query("SELECT `goal_progress` FROM `fitness__goal_weeks` WHERE `start_date`='$date' AND `goal`=$id");

        if ($row = $res->fetch_assoc()) {
            $progress = $row["goal_progress"];

            return is_null($progress) ? 0 : $progress;
        } else {
            $amt = $this->GetDefaultGoalAmount(Goal::get($id));

            $this->mysqli->query("INSERT INTO `fitness__goal_weeks` (`goal_progress`, `goal_amount`, `start_date`, `goal`) VALUES (0.0, $amt, '$date', $id)");
            return 0.0;
        }
    }

    public function GetGoalProgressDay(\DateTime $day, int $id): float {
        $date = $day->format("Y-m-d");

        $res = $this->mysqli->query("SELECT `goal_progress` FROM `fitness__goal_days` WHERE `date`='$date' AND `id`=$id");

        if ($row = $res->fetch_assoc()) {
            $progress = $row["goal_progress"];

            return is_null($progress) ? 0 : $progress;
        } else {
            $amt = $this->GetDefaultGoalAmount(Goal::get($id));
            $this->mysqli->query("INSERT INTO `fitness__goal_days` (`goal_progress`, `goal`, `date`, `goal_amount`) VALUES (0.0, $id, '$date', $amt)");
            return 0.0;
        }
    }

	public function GetGoalStatusDay(\DateTime $day, int $id): float {
		$date = $day->format("Y-m-d");

		$res = $this->mysqli->query("SELECT `goal_progress`,`goal_amount` FROM `fitness__goal_days` WHERE `date`='$date' AND `goal`=$id");

		if ($row = $res->fetch_assoc()) {
			$progress = $row["goal_progress"];
			$amount = $row["goal_amount"];

			return $progress / $amount;
		} else {
		    $amt = $this->GetDefaultGoalAmount(Goal::get($id));
		    $this->mysqli->query("INSERT INTO `fitness__goal_days` (`goal_progress`, `goal`, `date`, `goal_amount`) VALUES (0.0, $id, '$date', $amt)");
		    return 0.0;
		}
	}

	public function SetGoalProgress(Goal $goal, float $progress) {
		$date = date("Y-m-d");
		$id = $goal->getId();
		$def = $this->GetDefaultGoalAmount($goal);

		if ($goal->getType()->getPer() == "DAY") {
			if ($this->GoalProgressExists($goal, new \DateTime()))
				$this->mysqli->query("UPDATE `fitness__goal_days` SET `goal_progress`=$progress WHERE `date`='$date' AND `goal`=$id");
			else
				$this->mysqli->query("INSERT INTO `fitness__goal_days` (`goal`,`date`,`goal_amount`,`goal_progress`) VALUES ($id,'$date',$def,$progress)");
		} else if ($goal->getType()->getPer() == "WEEK") {
		    $date = $this->GetFirstDayOfWeek(new \DateTime())->format("Y-m-d");

			if ($this->GoalProgressExists($goal, new \DateTime()))
				$this->mysqli->query("UPDATE `fitness__goal_weeks` SET `goal_progress`=$progress WHERE `start_date`='$date' AND `goal`=$id");
			else
				$this->mysqli->query("INSERT INTO `fitness__goal_weeks` (`goal`,`start_date`,`goal_amount`,`goal_progress`) VALUES ($id,'$date',$def,$progress)");
		} else {
		    throw new \Exception();
        }

	}

	private function GoalProgressExists(Goal $goal, \DateTime $date = null): bool {
		if (is_null($date))
			$date = new \DateTime();

		$id = $goal->getId();
		$day = $date->format("Y-m-d");
		$per = $goal->getType()->getPer();

		if ($per == "WEEK") {
			$check = $this->GetFirstDayOfWeek($date)->format("Y-m-d");

			$res = $this->mysqli->query("SELECT * FROM `fitness__goal_weeks` WHERE `start_date`='$check' AND `goal`=$id");

			if ($row = $res->fetch_assoc()) {
				return true;
			}

			return false;
		} else if ($per == "DAY") {
			$res = $this->mysqli->query("SELECT * FROM `fitness__goal_days` WHERE `date`='$day' AND `goal`=$id");

			if ($row = $res->fetch_assoc()) {
				return true;
			}

			return false;
		} else {
			// This case should never be executed; it's here to appease the IDE
			return false;
		}
	}

	public function GetGoalAmountForDate(Goal $goal, \DateTime $start): float {
		$id = $goal->getId();
		$per = $goal->getType()->getPer();
		$date = $start->format("Y-m-d");

		if ($per == "WEEK") {
			$check = $this->GetFirstDayOfWeek($start)->format("Y-m-d");

			$res = $this->mysqli->query("SELECT `goal_amount` FROM `fitness__goal_weeks` WHERE `start_date`='$check' AND `goal`=$id");


			if ($row = $res->fetch_assoc()) {
				return $row["goal_amount"];
			} else {
			    $amt = $this->GetDefaultGoalAmount($goal);
			    $this->mysqli->query("INSERT INTO `fitness__goal_weeks` (`goal`, `start_date`, `goal_progress`, `goal_amount`) VALUES ($id, '$check', 0.0, $amt)");
			    return 0.0;
			}
		} else if ($per == "DAY") {
			$res = $this->mysqli->query("SELECT `goal_amount` FROM `fitness__goal_days` WHERE `goal`=$id AND `date`='$date'");

			if ($row = $res->fetch_assoc()) {
				return $row["goal_amount"];
			} else {
			    $amt = $this->GetDefaultGoalAmount($goal);
			    $this->mysqli->query("INSERT INTO `fitness__goal_days` (`goal`, `date`, `goal_amount`, `goal_progress`) VALUES ($id, '$date', $amt, 0.0))");
			    return 0.0;
			}
		} else {
			// This case should never be executed because "per" is an ENUM
			throw new \Exception("Internal error.");
		}
	}

	private function GetFirstDayOfWeek(\DateTime $date = null): \DateTime {
    	if (is_null($date))
    		$date = new \DateTime();
		$day = $date->format("w");
		return new \DateTime(date('Y-m-d', strtotime('-'.$day.' days')));
	}

	public function GetDefaultGoalAmount(Goal $goal): float {
		$id = $goal->getId();

		$res = $this->mysqli->query("SELECT `goal_amount` FROM `fitness__goals` WHERE `id`=$id");
		if ($row = $res->fetch_assoc()) {
			return $row["goal_amount"];
		} else {
			throw new MySQLException("No goal found with id $id in the database.");
		}
	}

	public function UserHasGoals(User $user): bool {
    	$id = $user->getId();
    	$query = "SELECT * FROM `fitness__goals` WHERE (`user`=$id AND `date_deactivated` IS NULL)";
    	$res = $this->mysqli->query($query);

    	if ($this->mysqli->errno)
    		throw new MySQLException($this->mysqli->error . " Query: ".$query);

    	if ($row = $res->fetch_assoc())
    		return true;
    	else
    		return false;
	}

	public function FindTeam(): int {
        $res = $this->mysqli->query("SELECT * FROM `fitness__teams` WHERE `id`!=0");

        if ($row = $res->fetch_assoc()) {
            do {
                $id = $row["id"];

                $c = $this->mysqli->query("SELECT COUNT(*) AS 'TOTAL' FROM `fitness__users` WHERE `team`=$id")->fetch_assoc()["TOTAL"];
                if (intval($c) < 5)
                    return $id;
            } while ($row = $res->fetch_assoc());
        }

        return 0;
    }
}