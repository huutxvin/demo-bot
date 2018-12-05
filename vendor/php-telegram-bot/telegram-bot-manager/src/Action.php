<?php
/**
 * This file is part of the TelegramBotManager package.
 *
 * (c) Armando Lüscher <armando@noplanman.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NPM\TelegramBotManager;

class Action
{
    /**
     * @var array List of valid actions that can be called.
     */
    private static $valid_actions = [
        'set',
        'unset',
        'reset',
        'handle',
    ];

    /**
     * @var string Action to be executed.
     */
    private $action;

    /**
     * Action constructor.
     *
     * @param string $action
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($action = 'handle')
    {
        $this->action = $action ?: 'handle';

        if (!$this->isAction(self::$valid_actions)) {
            throw new \InvalidArgumentException('Invalid action');
        }
    }

    /**
     * Check if the current action is one of the passed ones.
     *
     * @param string|array $actions
     *
     * @return bool
     */
    public function isAction($actions)
    {
        return in_array($this->action, (array)$actions, true);
    }

    /**
     * Return the current action.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
