<?php

namespace AlexBrin\Qiwi\Task;

use AlexBrin\Qiwi\Form\PurchaseStatusForm;
use AlexBrin\Qiwi\Main;
use AlexBrin\Qiwi\Exception\BadQiwiResponseException;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class PurchaseUpdateAsyncTask extends AsyncTask
{
    private $comment;
    private $phone;
    private $token;

    private $success = false;
    private $profit = 0.0;

    public function __construct(string $phone, string $token, string $comment, bool $form = false)
    {
        $this->comment = $comment;
        $this->phone = $phone;
        $this->token = $token;

        $this->storeLocal($form);
    }

    /**
     * @throws BadQiwiResponseException
     */
    public function onRun()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://edge.qiwi.com/payment-history/v2/persons/"
            . $this->phone . "/payments?" . http_build_query([
                'rows' => 15,
                'operation' => 'IN'
            ]));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token,
            'Host: edge.qiwi.com',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if(!$result)
            throw new BadQiwiResponseException("Bad Qiwi response: " . curl_error($ch)
                . "(" . curl_errno($ch) . ")");

        if($httpCode !== 200)
            throw new BadQiwiResponseException("Bad Qiwi response code: " . $httpCode
                . ". Response body: " . $result);

        $result = json_decode($result);
        foreach($result->data as $purchase) {
            if(!$purchase->comment)
                continue;

            $comment = explode('#', $purchase->comment);
            if(count($comment) != 2)
                continue;

            if($comment[1] == $this->comment) {
                $this->success = true;
                $this->profit = $purchase->sum->amount;
                return;
            }
        }
    }

    public function onCompletion(Server $server)
    {
        $purchaseManager = Main::getInstance()->getPurchaseManager();
        $purchase = $purchaseManager->findById((int) $this->comment);
        if(!$purchase)
            return;

        if($purchase->getStatus() == $purchase::STATUS_DONE)
            return;

        $donate = $purchase->getDonate();
        if(!$donate)
            return;

        if($this->profit < $donate->getPrice()) {
            $purchase->setStatus($purchase::STATUS_FRAUD);
        } else {

            $purchase->setStatus($purchase::STATUS_DONE);
            $purchase->updateTimestamp();
            $server->dispatchCommand(new ConsoleCommandSender(),
                str_replace('{username}', $purchase->getPlayer(), $donate->getCommand()), true);
        }

        $purchase->save();

        if($this->fetchLocal()) {
            $player = $server->getPlayer($purchase->getPlayer());
            if(!$player)
                return;

            $player->sendForm(new PurchaseStatusForm($purchase));
        }
    }
}