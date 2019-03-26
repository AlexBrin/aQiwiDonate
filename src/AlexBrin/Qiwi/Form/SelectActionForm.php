<?php

namespace AlexBrin\Qiwi\Form;

use AlexBrin\Qiwi\Form\Base\Button;
use AlexBrin\Qiwi\Form\Base\SimpleForm;
use AlexBrin\Qiwi\MessageManager;
use pocketmine\Player;

class SelectActionForm extends SimpleForm
{

    public function __construct()
    {
        $buttons = [
            new Button(MessageManager::getMessage('forms.selectAction.select'), 'select'),
            new Button(MessageManager::getMessage('forms.selectAction.check'), 'check'),
        ];

        parent::__construct(
            MessageManager::getMessage('forms.selectAction.title'), '', $buttons);
    }

    public function onSubmit(Player $player): void
    {
        switch ($this->selectedButton->getUnique()) {

            case 'check':
                $player->sendForm(new CheckForm);
                break;

            case 'select':
                $player->sendForm(new DonateListForm);
                break;

        }
    }

}