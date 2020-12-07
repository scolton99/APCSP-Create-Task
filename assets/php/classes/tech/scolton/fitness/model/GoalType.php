<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/14/17
 * Time: 6:42 PM
 */

namespace tech\scolton\fitness\model;

define("TOP", dirname(__FILE__, 6) . "/");

require_once(TOP. "var.php");

class GoalType {
	/** @var int */
	private $id;

	/** @var string */
	private $verb;

	/** @var string */
	private $name;

	/** @var GoalSuperType */
	private $supertype;

	/** @var string */
	private $units;

	/** @var string */
	private $item;

	/** @var string */
	private $participle;

	/** @var string  */
	private $per;

	/** @var string */
	private $comparator;

	private function __construct(array $values) {
		$this->id = $values["id"];
		$this->verb = $values["verb"];
		$this->name = $values["name"];
		$this->supertype = GoalSuperType::get($values["supertype"]);
		$this->units = $values["units"];
		$this->item = $values["item"];
		$this->participle = $values["participle"];
		$this->per = $values["per"];
		$this->comparator = $values["comparator"];

		if (is_null($this->item)) {
			$this->item = "";
		}
	}

	public static function getType(int $id): GoalType {
		$arr = self::getTypes();

		foreach ($arr as $type) {
			assert($type instanceof GoalType);
			if ($type->getId() == $id)
				return $type;
		}

		throw new \Exception("No goal type with id $id found in the database.");
	}

	private static function getAllTypes(): array {
		return getDB()->GetGoalTypes();
	}

	public static function getTypes(): array {
		$arr = self::getAllTypes();
		$types = [];

		foreach ($arr as $type) {
			$types[] = new self($type);
		}

		return $types;
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
	public function getVerb(): string {
		return $this->verb;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return GoalSuperType
	 */
	public function getSupertype(): GoalSuperType {
		return $this->supertype;
	}

	/**
	 * @return string
	 */
	public function getUnits(): string {
		return $this->units;
	}

	/**
	 * @return string
	 */
	public function getItem(): string {
		return $this->item;
	}

	/**
	 * @return string
	 */
	public function getParticiple(): string {
		return $this->participle;
	}

	/**
	 * @return string
	 */
	public function getPer(): string {
		return $this->per;
	}

	/**
	 * @return string
	 */
	public function getComparator(): string {
		return $this->comparator;
	}
}