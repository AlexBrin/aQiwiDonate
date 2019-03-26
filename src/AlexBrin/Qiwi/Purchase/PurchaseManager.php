<?php

namespace AlexBrin\Qiwi\Purchase;

use pocketmine\utils\Config;

class PurchaseManager
{
    /**
     * @var Purchase[]
     */
    private $purchase = [];

    /**
     * @var Config
     */
    private $history;

    private static $instance;

    public function __construct(string $path)
    {
        $this->history = new Config($path . 'history.json');
        foreach ($this->history->getAll() as $id => $h)
            $this->purchase[] = new Purchase($id, $h['player'], $h['donateId'], $h['timestamp'], $h['status']);

        self::$instance =& $this;
    }

    /**
     * Найти платеж по ID
     * @param int $id
     * @return Purchase|null
     */
    public function findById(int $id): ?Purchase
    {
        return $this->purchase[$id] ?? null;
    }

    /**
     * Обновить платеж
     * @param Purchase $purchase
     */
    public function update(Purchase $purchase) {
        $this->history->set($purchase->getId(), $purchase->toArray());
        $this->history->save();
    }

    /**
     * Создать новый платеж
     * @param string $player
     * @param int $donateId
     * @return Purchase
     */
    public function create(string $player, int $donateId): Purchase
    {
        $purchaseId = count($this->purchase);
        $this->purchase[] = new Purchase($purchaseId, $player, $donateId, time(), Purchase::STATUS_WAIT);

        $this->history->set($purchaseId, $this->purchase[$purchaseId]->jsonSerialize());
        $this->history->save();

        return $this->purchase[$purchaseId];
    }

    public static function getInstance(): PurchaseManager
    {
        return self::$instance;
    }

}