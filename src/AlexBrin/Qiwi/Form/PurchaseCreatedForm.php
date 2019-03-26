<?php

namespace AlexBrin\Qiwi\Form;

use AlexBrin\Qiwi\Form\Base\CustomForm;
use AlexBrin\Qiwi\Main;
use AlexBrin\Qiwi\MessageManager;

class PurchaseCreatedForm extends CustomForm
{

    public function __construct(string $comment, int $price)
    {
        parent::__construct(MessageManager::getMessage('forms.created.title', [], true));

        $this->addLabel(MessageManager::getMessage('forms.created.text', [
            $price,
            Main::getInstance()->getConfig()->get('phone'),
            $comment,
        ]));
        $this->addInput('', '', $comment);
    }

}