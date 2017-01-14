<?php
/**
 * Created by PhpStorm.
 * User: scolton
 * Date: 2017-01-14
 * Time: 01:23
 */

namespace tech\scolton\fitness\notification;


abstract class ActionBase implements Action {
    /**
     * @var string
     */
    protected $target;

    /**
     * @var ActionType
     */
    protected $type;

    /**
     * @var Notification
     */
    protected $source;

    /**
     * @var array
     */
    protected $actionMeta;

    public function __construct(string $target, ActionType $type, Notification $source, array $actionMeta) {
        $this->target = $target;
        $this->type = $type;
        $this->source = $source;
        $this->actionMeta = $actionMeta;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function getType(): ActionType {
        return $this->type;
    }

    public function getSource(): Notification {
        return $this->source;
    }

    public function getActionMeta(): array {
        return $this->actionMeta;
    }
}