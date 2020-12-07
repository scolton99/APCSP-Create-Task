<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/14/17
 * Time: 7:06 PM
 */

namespace tech\scolton\fitness\model;

define("TOP", dirname(__FILE__, 6) . "/");

require_once(TOP. "var.php");

class GoalSuperType {
	/** @var  int */
	private $id;

	/** @var  string */
	private $name;

	/** @var  string */
	private $desc;

	private function __construct(array $values) {
		$this->id = $values["id"];
		$this->name = $values["name"];
		$this->desc = $values["desc"];
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
	 * @return string
	 */
	public function getDesc(): string {
		return $this->desc;
	}

	public static function get(int $id): GoalSuperType {
		$all = self::getSuperTypes();

		foreach ($all as $supertype) {
			assert($supertype instanceof GoalSuperType);
			if ($supertype->getId() == $id)
				return $supertype;
		}

		throw new \Exception("No goal supertype of id $id found in the database.");
	}

	private static function getAllSuperTypes(): array {
		return getDB()->GetGoalSuperTypes();
	}

	public static function getSuperTypes(): array {
		$arr = self::getAllSuperTypes();
		$supertypes = [];

		foreach ($arr as $supertype) {
			$supertypes[] = new self($supertype);
		}

		return $supertypes;
	}
}