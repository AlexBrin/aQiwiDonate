<?php

namespace AlexBrin\Qiwi\Donate;

class Donate
{
    private $id;
    private $name;
    private $description;
    private $price;
    private $command;
    private $image;

    public function __construct(int $id, string $name, string $description, float $price, string $command, ?string $image)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->command = $command;
        $this->image = $image;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getCommand(): string {
        return $this->command;
    }

    public function getImage(): ?string {
        return $this->image;
    }

}