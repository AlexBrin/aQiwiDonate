<?php

namespace AlexBrin\Qiwi\Command;

use AlexBrin\Qiwi\Form\SelectActionForm;
use AlexBrin\Qiwi\MessageManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class DonateCommand extends Command
{

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender instanceof Player) {
            $sender->sendMessage("§cOnly for players");
            return false;
        }

        if(count($args) == 1 && $args[0] == 'help') {
            $sender->sendMessage(MessageManager::getInstance()->help());
            return false;
        }

        $sender->sendForm(new SelectActionForm());
        return true;
    }

}