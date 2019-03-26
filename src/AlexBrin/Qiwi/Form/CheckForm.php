<?php

namespace AlexBrin\Qiwi\Form;

use AlexBrin\Qiwi\Form\Base\CustomForm;
use AlexBrin\Qiwi\MessageManager;
use AlexBrin\Qiwi\Purchase\PurchaseManager;
use pocketmine\Player;

class CheckForm extends CustomForm
{

    public function __construct()
    {
        parent::__construct(
            MessageManager::getMessage('forms.check.title', [], true)
        );

        $this->addInput(MessageManager::getMessage('forms.check.id'),
            '', null, 'purchaseId');
    }

    public function onSubmit(Player $player): void
    {
        $purchase = PurchaseManager::getInstance()->findById(intval($this->result['purchaseId']));
        if(!$purchase || $purchase->getPlayer() != $player->getName()) {
            $player->sendMessage(MessageManager::getMessage('purchaseNotFound', [], true));
            return;
        }

        $player->sendForm(new PurchaseStatusForm($purchase));
    }

}