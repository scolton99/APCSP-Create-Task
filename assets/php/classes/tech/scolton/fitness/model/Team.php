<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 10:52 AM
 */

namespace tech\scolton\fitness\model;

require_once("../../../../../var.php");

use tech\scolton\fitness\exception\RequiredValueMissingException;

class Team {
	public static $required_values = ["name"];

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	private function __construct(array $values) {
		$name = $values["name"];

		$this->name = $name;
	}

	/**
	 * @param $values
	 * @return Team
	 * @throws RequiredValueMissingException
	 */
	public static function g_new(array $values): Team {
		foreach (Team::$required_values as $val)
			if (!array_key_exists($val, $values))
				throw new RequiredValueMissingException("Missing required value `$val` while trying to instantiate Team.");

		$team = new self($values);

		$db = getDB();

		$id = $db->NewTeam($team);

		$team->setId($id);

		return $team;
	}

	public static function get(int $id): Team {
		$db = getDB();
		$data = $db->GetTeam($id);
		return Team::g_new($data);
	}

	private function _update() {
		$db = getDB();
		$db->WriteTeamData($this);
	}

	public function getUsers(): array {
		return getDB()->GetAllUsersOnTeam($this->getId());
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param int $id
	 */
	private function setId(int $id) {
		$this->id = $id;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name) {
		$this->name = $name;
		$this->_update();
	}
}