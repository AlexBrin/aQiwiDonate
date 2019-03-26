<?php

namespace AlexBrin\Qiwi;

use pocketmine\utils\Config;

class MessageManager
{
    private $messages;

    private static $instance;

    public function __construct(string $path)
    {
        $this->messages = new Config($path . 'message.yml', Config::YAML);
        self::$instance =& $this;
    }

    public function get(string $node, array $vars = [], bool $prefix = false): string {
        $message = $this->messages->getNested($node, '');

        $i = 0;
        foreach($vars as $var) {
            $message = str_replace("{var$i}", $var, $message);
            $i++;
        }

        if($prefix)
            $message = $this->getPrefix() . $message;

        return $message;
    }

    public static function getMessage(string $node, array $vars = [], bool $prefix = false): string {
        return self::getInstance()->get($node, $vars, $prefix);
    }

    public function help() {
        return
            $this->getPrefix() .
            implode("\n", $this->messages->get('help'));
    }

    public function getPrefix(): string {
        return $this->messages->get('prefix') . ' ';
    }

    public static function getInstance(): MessageManager {
        return self::$instance;
    }

}