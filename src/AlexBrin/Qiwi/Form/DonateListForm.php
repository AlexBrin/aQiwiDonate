<?php

namespace AlexBrin\Qiwi\Form;

use AlexBrin\Qiwi\Donate\DonateManager;
use AlexBrin\Qiwi\Form\Base\Button;
use AlexBrin\Qiwi\Form\Base\ButtonImage;
use AlexBrin\Qiwi\Form\Base\SimpleForm;
use AlexBrin\Qiwi\MessageManager;
use pocketmine\Player;

class DonateListForm extends SimpleForm
{

    public function __construct()
    {
        $buttons = [];
        foreach(DonateManager::getInstance()->getAll() as $donate) {
            $button = new Button(
                MessageManager::getMessage('forms.list.element', [
                    $donate->getName(), $donate->getPrice()
                ]), (string) $donate->getId()
            );

            if($donate->getImage())
                $button->setImage(new ButtonImage(ButtonImage::TYPE_URL, $donate->getImage()));

            $buttons[] = $button;
        }

        parent::__construct(
            MessageManager::getMessage('form.list.title', [], true),'', $buttons);
    }

    public function onSubmit(Player $player): void
    {
        $donate = DonateManager::getInstance()->findById((int) $this->selectedButton->getUnique());
        if(!$donate) {
            $player->sendMessage(MessageManager::getMessage('donateNotFound', [], true));
            return;
        }

        $player->sendForm(new DonateInfoForm($donate));
    }

}