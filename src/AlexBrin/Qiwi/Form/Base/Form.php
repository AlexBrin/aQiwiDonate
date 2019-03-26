<?php
/**
 * @author: i am scray (https://vk.com/i_am_scray)
 */

namespace AlexBrin\Qiwi\Form\Base;

use pocketmine\form\Form as IForm;
use pocketmine\Player;

abstract class Form implements IForm
{
    public const TYPE_CUSTOM = "custom_form";
    public const TYPE_MENU = "form";
    public const TYPE_MODAL = "modal";

    protected $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    abstract public function getType(): string;

    abstract public function onSubmit(Player $player): void;

    abstract public function onClose(Player $player): void;

    abstract public function serializeFormData(): array;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function jsonSerialize(): array
    {
        $data = [
            "type" => $this->getType(),
            "title" => $this->getTitle()
        ];
        return array_merge($data, $this->serializeFormData());
    }

    public function handleResponse(Player $player, $data): void {}

}