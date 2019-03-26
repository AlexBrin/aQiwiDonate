<?php

namespace AlexBrin\Qiwi\Form;

use AlexBrin\Qiwi\Donate\Donate;
use AlexBrin\Qiwi\Donate\DonateManager;
use AlexBrin\Qiwi\Form\Base\ModalForm;
use AlexBrin\Qiwi\MessageManager;
use AlexBrin\Qiwi\Purchase\PurchaseManager;
use pocketmine\Player;

class DonateInfoForm extends ModalForm
{
    private $donateId;

    public function __construct(Donate $donate)
    {
        parent::__construct(
            MessageManager::getMessage('forms.confirm.title', [], true),
            MessageManager::getMessage('forms.confirm.yes'),
            MessageManager::getMessage('forms.confirm.no'));

        $this->text = MessageManager::getMessage('forms.confirm.description', [
            $donate->getName(), $donate->getPrice(), $donate->getDescription()
        ]);
        $this->donateId = $donate->getId();
    }

    public function onSubmit(Player $player): void
    {
        if (!$this->choice) {
            $player->sendForm(new DonateListForm);
            return;
        }

        $donate = DonateManager::getInstance()->findById($this->donateId);
        $purchase = PurchaseManager::getInstance()->create($player->getName(), $this->donateId);
        $comment = 'Оплата счета #' . $purchase->getId();
        $player->sendForm(new PurchaseCreatedForm($comment, $donate->getPrice()));
    }

}