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

class Params
{
    /**
     * @var array List of valid script parameters.
     */
    private static $valid_script_params = [
        's',
        'a',
        'l',
    ];

    /**
     * @var array List of vital parameters that must be passed.
     */
    private static $valid_vital_bot_params = [
        'api_key',
        'botname',
        'secret',
    ];

    /**
     * @var array List of valid extra parameters that can be passed.
     */
    private static $valid_extra_bot_params = [
        'webhook',
        'selfcrt',
        'logging',
        'admins',
        'mysql',
        'download_path',
        'upload_path',
        'commands_paths',
        'command_configs',
        'botan_token',
        'custom_input',
    ];

    /**
     * @var array List of all params passed to the script.
     */
    private $script_params = [];

    /**
     * @var array List of all params passed at construction.
     */
    private $bot_params = [];

    /**
     * Params constructor.
     *
     * api_key (string) Telegram Bot API key
     * botname (string) Telegram Bot name
     * secret (string) Secret string to validate calls
     * webhook (string) URI of the webhook
     * selfcrt (string) Path to the self-signed certificate
     * logging (array) Array of logger files to set.
     * admins (array) List of admins to enable.
     * mysql (array) MySQL credentials to use.
     * download_path (string) Custom download path to set.
     * upload_path (string) Custom upload path to set.
     * commands_paths (array) Custom commands paths to set.
     * command_configs (array) List of custom command configs.
     * botan_token (string) Botan token to enable botan.io support.
     * custom_input (string) Custom raw JSON string to use as input.
     *
     * @param array $params All params to set the bot up with.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $params)
    {
        $this->validateAndSetBotParams($params);
        $this->validateAndSetScriptParams();
    }

    /**
     * Validate and set up the vital and extra params.
     *
     * @param $params
     *
     * @throws \InvalidArgumentException
     */
    private function validateAndSetBotParams($params)
    {
        // Set all vital params.
        foreach (self::$valid_vital_bot_params as $vital_key) {
            if (empty($params[$vital_key])) {
                throw new \InvalidArgumentException('Some vital info is missing: ' . $vital_key);
            }

            $this->bot_params[$vital_key] = $params[$vital_key];
        }

        // Set all extra params.
        foreach (self::$valid_extra_bot_params as $extra_key) {
            if (empty($params[$extra_key])) {
                continue;
            }

            $this->bot_params[$extra_key] = $params[$extra_key];
        }
    }

    /**
     * Handle all script params, via web server handler or CLI.
     *
     * https://url/entry.php?s=<secret>&a=<action>&l=<loop>
     * $ php entry.php s=<secret> a=<action> l=<loop>
     */
    private function validateAndSetScriptParams()
    {
        $this->script_params = $_GET;

        // If we're running from CLI, properly set script parameters.
        if ('cli' === PHP_SAPI) {
            // We don't need the first arg (the file name).
            $args = array_slice($_SERVER['argv'], 1);

            foreach ($args as $arg) {
                @list($key, $val) = explode('=', $arg);
                isset($key, $val) && $this->script_params[$key] = $val;
            }
        }

        // Keep only valid ones.
        $this->script_params = array_intersect_key($this->script_params,
            array_fill_keys(self::$valid_script_params, null));

        return $this;
    }

    /**
     * Get a specific bot param.
     *
     * @param string $param
     *
     * @return mixed
     */
    public function getBotParam($param)
    {
        return isset($this->bot_params[$param]) ? $this->bot_params[$param] : null;
    }

    public function getBotParams()
    {
        return $this->bot_params;
    }

    /**
     * Get a specific script param.
     *
     * @param string $param
     *
     * @return mixed
     */
    public function getScriptParam($param)
    {
        return isset($this->script_params[$param]) ? $this->script_params[$param] : null;
    }

    public function getScriptParams()
    {
        return $this->script_params;
    }
}
