<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 2:19 PM
 */

namespace tech\scolton\fitness\notification;

define("TOP", dirname(__FILE__, 6) . "/");

require_once(TOP. "var.php");

class NotificationType
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $desc;

    /**
     * @var boolean
     */
    private $actionable;

    private function __construct($values) {
        $this->id = $values["id"];
        $this->name = $values["name"];
        $this->desc = $values["desc"];
        $this->actionable = $values["actionable"] == 1 ? true : false;
    }

    public static function getType(int $id): NotificationType {
        $types = self::getAllTypes();

        foreach ($types as $type) {
            assert($type instanceof NotificationType);
            if ($type->getId() == $id)
                return $type;
        }

        throw new \Exception("No notification type with id $id found.  Foreign key error?");
    }

    private static function getTypes(): array {
        $db = getDB();

        return $db->GetNotificationTypes();
    }

    public static function getAllTypes(): array {
        $types = [];

        foreach (self::getTypes() as $type) {
            $types[] = new self($type);
        }

        return $types;
    }

    public static function getTypeByName(string $name) {
        $types = self::getAllTypes();

        foreach ($types as $type) {
            assert($type instanceof NotificationType);
            if ($type->getName() == $name)
                return $type;
        }

        throw new \Exception("No notification type with name $name found.");
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
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
    public function getDesc(): string {
        return $this->desc;
    }

    /**
     * @return bool
     */
    public function isActionable(): bool {
        return $this->actionable;
    }

    // TODO: Add periodic goal reminders
}