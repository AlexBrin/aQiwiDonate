<?php
/**
 * @author: i am scray (https://vk.com/i_am_scray)
 */

namespace AlexBrin\Qiwi\Form\Base;

use pocketmine\form\FormValidationException;
use pocketmine\Player;

class ModalForm extends Form
{
    protected $text;
    protected $yesButton;
    protected $noButton;
    protected $choice;

    public function __construct(string $title, string $yesButton = "ОК", string $noButton = "Отмена")
    {
        $this->yesButton = $yesButton;
        $this->noButton = $noButton;
        parent::__construct($title);
    }

    public function getType(): string
    {
        return self::TYPE_MODAL;
    }

    public function onSubmit(Player $player): void {}

    public function onClose(Player $player): void {}

    public function setText(string $text) {
        $this->text = $text;
    }

    public function setYesButton(string $text) {
        $this->yesButton = $text;
    }

    public function setNoButton(string $text) {
        $this->noButton = $text;
    }

    public function handleResponse(Player $player, $data): void
    {
        if($data === null) {
            $this->onClose($player);
            return;
        }

        if(is_bool($data))  {
            $this->choice = $data;
            $this->onSubmit($player);
            return;
        }

        throw new FormValidationException("Incorrect data: expected bool, got" . gettype($data));
    }

    public function serializeFormData(): array
    {
        return [
            'content' => $this->text,
            'button1' => $this->yesButton,
            'button2' => $this->noButton,
        ];
    }
}