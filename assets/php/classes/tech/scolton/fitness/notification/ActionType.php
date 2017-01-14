<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 2:22 PM
 */

namespace tech\scolton\fitness\notification;

define("TOP", dirname(__FILE__, 6) . "/");

require_once(TOP. "var.php");

class ActionType
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $desc;

    /**
     * @var string;
     */
    private $classmap;

    /**
     * @var int
     */
    private $id;

    private function __construct($values) {
        $this->name = $values["name"];
        $this->desc = $values["desc"];
        $this->classmap = $values["classmap"];
        $this->id = $values["id"];
    }

    public static function getType(int $id): ActionType {
        $types = self::getAllTypes();

        foreach ($types as $type) {
            assert($type instanceof ActionType);
            if ($type->getId() == $id)
                return $type;
        }

        throw new \Exception("No type found with id $id. Foreign key error?");
    }

    private static function getTypes(): array {
        $db = getDB();

        return $db->GetActionTypes();
    }

    public static function getAllTypes(): array {
        $types = [];

        foreach (self::getTypes() as $type) {
            $types[] = new self($type);
        }

        return $types;
    }

    /**
     * @return string
     */
    public function getClassmap(): string {
        return $this->classmap;
    }

    /**
     * @return mixed
     */
    public function getDesc() {
        return $this->desc;
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

}