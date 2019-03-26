<?php

namespace AlexBrin\Qiwi\Form;

use AlexBrin\Qiwi\Form\Base\ModalForm;
use AlexBrin\Qiwi\Main;
use AlexBrin\Qiwi\MessageManager;
use AlexBrin\Qiwi\Purchase\Purchase;
use AlexBrin\Qiwi\Task\PurchaseUpdateAsyncTask;
use pocketmine\Player;
use pocketmine\Server;

class PurchaseStatusForm extends ModalForm
{
    private $purchaseId;
    private $status;

    public function __construct(Purchase $purchase)
    {
        parent::__construct(MessageManager::getMessage('forms.status.title', [], true));

        $this->text = MessageManager::getMessage('forms.status.text', [
            $purchase->getId(),
            $purchase->getDonate()->getName(),
            $purchase->getDonate()->getDescription(),
            MessageManager::getMessage('status.' . $purchase->getStatus()),
        ]);
        $this->purchaseId = $purchase->getId();
        $this->status = $purchase->getStatus();
    }

    public function onSubmit(Player $player): void
    {
        if(!$this->choice)
            return;

        if($this->status == Purchase::STATUS_DONE)
            return;

        $config = Main::getInstance()->getConfig();
        Server::getInstance()->getAsyncPool()->submitTask(
            new PurchaseUpdateAsyncTask(
                $config->get('phone'),
                $config->get('token'),
                (string) $this->purchaseId,
                true
            )
        );
    }

}