<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 19.03.2019
 * Time: 01:11
 */

namespace AlexBrin\Qiwi\Donate;


use pocketmine\utils\Config;

class DonateManager
{
    /**
     * @var Donate[]
     */
    private $donate = [];

    private static $instance;

    public function __construct(string $path)
    {
        $donate = new Config($path . 'donate.yml', Config::YAML);
        foreach ($donate->getAll() as $id => $d)
            $this->donate[] = new Donate($id + 1, $d['name'],
                $d['description'], $d['price'], $d['command'],
                $d['image'] ?? null);

        self::$instance =& $this;
    }

    /**
     * Поиск доната по ID
     * @param int $id
     * @return Donate|null
     */
    public function findById(int $id): ?Donate
    {
        return $this->donate[$id - 1] ?? null;
    }

    /**
     * Список всех донатов
     * @return Donate[]
     */
    public function getAll(): array
    {
        return $this->donate;
    }

    public static function getInstance(): DonateManager
    {
        return self::$instance;
    }

}