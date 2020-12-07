<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/10/17
 * Time: 10:52 AM
 */

namespace tech\scolton\fitness\model;


use tech\scolton\fitness\exception\RequiredValueMissingException;

define("TOP", dirname(__FILE__, 6) . "/");

require_once(TOP. "var.php");

class User
{
    public static $required_values = ["username", "password"];

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var int
     */
    private $weight;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $team_id;

    /**
     * @var \DateTime
     */
    private $birthday;

    /**
     * @var string
     */
    private $units;

    /**
     * @var string
     */
    private $name;

    private function __construct(array $values) {
        $this->username = $values["username"];
        $this->password = $values["password"];

        if (array_key_exists("weight", $values))
            $this->weight = $values["weight"];

        if (array_key_exists("height", $values))
            $this->height = $values["height"];

        if (array_key_exists("team", $values))
            $this->team_id = $values["team"];

        if (array_key_exists("birthday", $values))
            $this->birthday = new \DateTime($values["birthday"]);

        if (array_key_exists("units", $values))
            $this->units = $values["units"];

        if (array_key_exists("name", $values))
            $this->name = $values["name"];

        if (array_key_exists("id", $values))
        	$this->id = $values["id"];
    }

    /**
     * @param array $values
     * @return User
     * @throws RequiredValueMissingException
     */
    public static function g_new(array $values): User {
        foreach (User::$required_values as $val)
            if (!array_key_exists($val, $values))
                throw new RequiredValueMissingException();

        $user = new User($values);

        $db = getDB();

        $id = $db->NewUser($user);

        $user->setId($id);

        return $user;
    }

    /**
     * @return void
     */
    private function _update() {
        $db = getDB();
        $db->WriteUserData($this);
    }

    /**
     * @return mixed|string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @return mixed|string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return int|mixed
     */
    public function getWeight() {
        return $this->weight;
    }

    /**
     * @return int|mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return int|mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
        $this->_update();
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = hash("SHA256", $password);
        $this->_update();
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height)
    {
        $this->height = $height;
        $this->_update();
    }

    /**
     * @param int $weight
     */
    public function setWeight(int $weight)
    {
        $this->weight = $weight;
        $this->_update();
    }

    /**
     * @param int $team_id
     */
    public function setTeam(int $team_id) {
        $this->team_id = $team_id;
        $this->_update();
    }

    /**
     * @return int
     */
    public function getTeamId(): int
    {
        return $this->team_id;
    }

    public function getTeam() {
        return Team::get($this->team_id);
    }

    public static function get($id): User {
        $db = getDB();
        $data = $db->GetUser($id);
        return new self($data);
    }

    /**
     * @param $id int
     */
    private function setId($id) {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday(): \DateTime
    {
        return $this->birthday;
    }

    /**
     * @return string
     */
    public function getUnits(): string
    {
        return $this->units;
    }

    /**
     * @param \DateTime $birthday
     */
    public function setBirthday(\DateTime $birthday)
    {
        $this->birthday = $birthday;
        $this->_update();
    }

    /**
     * @param string $units
     */
    public function setUnits(string $units)
    {
        $this->units = $units;
        $this->_update();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
        $this->_update();
    }

    public function hasGoals(): bool {
		return getDB()->UserHasGoals($this);
	}
}