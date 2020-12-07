<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 4:27 PM
 */

namespace tech\scolton\fitness\model;

define("TOP", dirname(__FILE__, 6) . "/");

require_once(TOP. "var.php");

class Goal
{
	/** @var int */
	private $id;

	/** @var User */
	private $user;

	/** @var GoalType */
	private $type;

	/** @var float */
	private $goalAmount;

	/** @var \DateTime  */
	private $dateActivated;

	/** @var \DateTime  */
	private $dateDeactivated;

	public function __construct(array $values) {
		$this->id = $values["id"];
		$this->user = User::get($values["user"]);
		$this->type = GoalType::getType($values["type"]);
		$this->goalAmount = $values["goal_amount"];
		$this->dateActivated = new \DateTime($values["date_activated"]);
		$this->dateDeactivated = new \DateTime($values["date_deactivated"]);
	}

	public static function g_new(int $type, User $user, float $amount): Goal {
	    $db = getDB();
	    $id = $db->NewGoal($type, $user, $amount);

        return self::get($id);
    }

	public function setDateDeactivated(\DateTime $date) {
		$this->dateActivated = $date;
		$this->_update();
	}

	private function _update() {
		getDB()->UpdateGoal($this);
	}

	public static function get(int $id) {
		return getDB()->GetGoal($id);
	}

	public static function getAll(User $user) {
		$goals = self::getAllGoals($user);
		$fin = [];

		foreach ($goals as $goal) {
			$fin[] = new self($goal);
		}

		return $fin;
	}

	private static function getAllGoals(User $user) {
		return getDB()->GetGoals($user->getId());
	}

	public function getStatus(\DateTime $date): float {
		if (!isset($date))
			$date = new \DateTime();

		if ($this->getType()->getPer() == "DAY") {
			return getDB()->GetGoalStatusDay($date, $this->getId());
		} else if ($this->getType()->getPer() == "WEEK") {
			return getDB()->GetGoalStatusWeek($date, $this->getId());
		} else {
			throw new \Exception("An internal error occurred with this goal.");
		}
	}

	public function addProgress(float $progress) {
		// TODO: make this work
		$this->setProgress($this->getStatus(new \DateTime()) + $progress);

	}

	public function getProgress(\DateTime $date) {
	    if (!isset($date))
	        $date = new \DateTime();

	    if ($this->getType()->getPer() == "WEEK") {
	        return getDB()->GetGoalProgressWeek($date, $this->getId());
        } else {
	        return getDB()->GetGoalProgressDay($date, $this->getId());
        }
    }

	public function setProgress(float $progress) {
		getDB()->SetGoalProgress($this, $progress);
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return User
	 */
	public function getUser(): User {
		return $this->user;
	}

	/**
	 * @return GoalType
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return float
	 */
	public function getGoalAmount() {
		return $this->goalAmount;
	}

	/**
	 * @return \DateTime
	 */
	public function getDateActivated(): \DateTime {
		return $this->dateActivated;
	}

	/**
	 * @return \DateTime
	 */
	public function getDateDeactivated(): \DateTime {
		return $this->dateDeactivated;
	}

	public function getGoalAmountForDate(\DateTime $day): float {
		return getDB()->GetGoalAmountForDate($this, $day);
	}

	public function getDefaultGoalAmount(): float {
		return getDB()->GetDefaultGoalAmount($this);
	}

	public function renderSentence(): string {
		/** @var GoalType $type */
		$type = $this->getType();

		$verb = $type->getVerb();
		$amt = $this->getGoalAmountForDate(new \DateTime());
		$unit = $type->getUnits();
		if ($amt == 1)
			$unit = substr($amt, 0, strlen($amt) - 1);

		$amt .= " ".$unit;

		if ($this->getType()->getItem() != null)
			$amt .= $amt . " of " . $this->getType()->getItem();

		$cur = $verb . " " . $amt . " per ";

		switch ($this->getType()->getPer()) {
			case "WEEK": {
				$cur .= "week";
				break;
			}
			case "DAY": {
				$cur .= "day";
				break;
			}
		}

		return $cur;
	}

	public function renderHTML(): string {
		$goalAmt = $this->getGoalAmountForDate(new \DateTime());
		$unit = $this->getType()->getUnits();
		$name = $this->getType()->getParticiple();
		$progress = $this->getStatus(new \DateTime()) * $goalAmt;
		$id = $this->getId();

		$html = "
			<h3 class=\"goal-progress\">$name</h3>
			<div class=\"progress\">
				<div class=\"progress-bar\" style=\"width: calc(100% * ($progress / $goalAmt));\">
					$progress \\ $goalAmt $unit
				</div>
			</div>
			<form class='goal-form' data-goal-id='$id'>
                <div class=\"input-group\">
                    <input type=\"text\" data-goal-id=\"$id\" class=\"form-control goal-input\" />
                    <span class=\"input-group-addon\">$unit</span>
                    <span class=\"input-group-btn\">
                        <button class=\"btn btn-secondary\">+</button>
				    </span>
			    </div>
            </form>
		";

		return $html;
	}
}