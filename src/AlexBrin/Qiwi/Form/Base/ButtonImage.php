<?php
/**
 * @author: i am scray (https://vk.com/i_am_scray)
 */

namespace AlexBrin\Qiwi\Form\Base;

class ButtonImage implements \JsonSerializable
{
    public const TYPE_PATH = 'path';
    public const TYPE_URL = 'url';

    private $type;
    private $data;

    public function __construct(string $data, string $type = self::TYPE_URL)
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function jsonSerialize(): array
    {
        return [
            'data' => $this->data,
            'type' => $this->type,
        ];
    }
}