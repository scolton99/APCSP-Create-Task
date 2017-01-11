<?php
/**
 * Created by PhpStorm.
 * User: scolton17
 * Date: 1/11/17
 * Time: 2:23 PM
 */

namespace tech\scolton\fitness\notification;


class CongratulateAction implements Action
{
    /**
     * @var string
     */
    private $type = ActionTypes::CONGRATULATE;

    /**
     * @var string
     */
    private $target;

    public function execute()
    {
        // TODO: Implement execute() method.
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function setup(string $target, string $type)
    {
        $this->target = $target;
        $this->type = $type;
    }
}