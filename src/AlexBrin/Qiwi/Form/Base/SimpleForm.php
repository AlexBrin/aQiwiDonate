<?php
/**
 * @author: i am scray (https://vk.com/i_am_scray)
 */

namespace AlexBrin\Qiwi\Form\Base;

use pocketmine\form\FormValidationException;
use pocketmine\Player;

class SimpleForm extends Form
{
    protected $text = '';

    /**
     * @var Button[]
     */
    protected $buttons;

    /**
     * @var Button
     */
    protected $selectedButton;

    public function __construct(string $title, string $text, array $buttons = [])
    {
        parent::__construct($title);
        $this->text = $text;
        foreach($buttons as $button) {
            if(!$button instanceof Button)
                throw new FormValidationException("Incorrect object: expected MenuButton, got " . get_class($button));

            $this->buttons[] = $button;
        }
    }

    public function getType(): string
    {
        return self::TYPE_MENU;
    }

    public function onSubmit(Player $player): void {}

    public function onClose(Player $player): void {}

    public function handleResponse(Player $player, $data): void
    {
        if($data === null) {
            $this->onClose($player);
            return;
        }

        if(is_numeric($data)) {
            $this->selectedButton = $this->buttons[$data];
            $this->onSubmit($player);
            return;
        }

        throw new FormValidationException("Incorrect data: expected INT, got " . gettype($data));
    }

    public function serializeFormData(): array
    {
        return [
            'content' => $this->text,
            'buttons' => $this->buttons,
        ];
    }
}