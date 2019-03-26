<?php

namespace AlexBrin\Qiwi\Purchase;

use AlexBrin\Qiwi\Donate\Donate;
use AlexBrin\Qiwi\Main;

class Purchase implements \JsonSerializable
{
    public const STATUS_WAIT = 'wait';
    public const STATUS_DONE = 'done';
    public const STATUS_FRAUD = 'fraud';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $player;

    /**
     * @var int
     */
    private $donateId;

    /**
     * @var int
     */
    private $timestamp;

    /**
     * @var string
     */
    private $status;

    /**
     * @var Donate
     */
    private $donate = null;

    /**
     * Purchase constructor.
     * @param int $id
     * @param string $player
     * @param int $donateId
     * @param int $timestamp
     * @param string $status
     */
    public function __construct(int $id, string $player, int $donateId, int $timestamp, string $status)
    {
        $this->id = $id;
        $this->player = $player;
        $this->donateId = $donateId;
        $this->timestamp = $timestamp;
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPlayer(): string
    {
        return $this->player;
    }

    /**
     * @return int
     */
    public function getDonateId(): int
    {
        return $this->donateId;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status) {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int {
        return $this->timestamp;
    }

    /**
     *
     */
    public function updateTimestamp() {
        $this->timestamp = time();
    }

    /**
     * Завершить платеж и обновить его время
     */
    public function complete()
    {
        $this->status = Purchase::STATUS_DONE;
        $this->timestamp = time();
    }

    /**
     * @return Donate|null
     */
    public function getDonate(): ?Donate
    {
        return $this->donate
            ?: $this->donate = Main::getInstance()->getDonateManager()->findById($this->getDonateId());
    }

    public function save() {
        Main::getInstance()->getPurchaseManager()->update($this);
    }

    public function toArray(): array {
        return $this->jsonSerialize();
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'player' => $this->player,
            'donateId' => $this->donateId,
            'timestamp' => $this->timestamp,
            'status' => $this->status,
        ];
    }

}