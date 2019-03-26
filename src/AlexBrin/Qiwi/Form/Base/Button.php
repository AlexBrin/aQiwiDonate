<?php
/**
 * @author: i am scray (https://vk.com/i_am_scray)
 */

namespace AlexBrin\Qiwi\Form\Base;

class Button implements \JsonSerializable
{
    private $text;
    private $unique;
    private $image;

    public function __construct(string $text, string $unique, ?ButtonImage $image = null)
    {
        $this->text = $text;
        $this->unique = $unique;
        $this->image = $image;
    }

    public function getText(): string {
        return $this->text;
    }

    public function getUnique(): string {
        return $this->unique;
    }

    public function getImage(): ?ButtonImage {
        return $this->image;
    }

    public function setImage(ButtonImage $image) {
        $this->image = $image;
    }

    public function jsonSerialize(): array
    {
        $data = [
            'text' => $this->text,
        ];

        if($this->image)
            $data['image'] = $this->image;

        return $data;
    }

}