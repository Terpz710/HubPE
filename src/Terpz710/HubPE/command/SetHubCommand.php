<?php

declare(strict_types=1);

namespace Terpz710\HubPE\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;

use Terpz710\HubPE\Main;

class SetHubCommand extends Command implements PluginOwned {

    /** @var Config */
    private $config;

    /** @var Plugin */
    private $plugin;

    public function __construct(Config $config, Plugin $plugin) {
        parent::__construct("sethub", "Set the hub location", null, ["setlobby", "setspawn"]);
        $this->config = $config;
        $this->plugin = $plugin;
        $this->setPermission("hubpe.sethub");
    }

    public function getOwningPlugin(): Plugin {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if ($sender instanceof Player) {
            if ($sender->hasPermission("hubpe.sethub")) {
                $position = $sender->getPosition();
                $world = $position->getWorld()->getFolderName();

                $hubData = [
                    "x" => $position->getX(),
                    "y" => $position->getY(),
                    "z" => $position->getZ(),
                    "world" => $world,
                ];

                $this->config->set("hub", $hubData);
                $this->config->save();
                
                $sender->sendMessage("§l§aHub location set");
            } else {
                $sender->sendMessage("§l§cYou don't have permission to use this command");
            }
        } else {
            $sender->sendMessage("This command can only be used by players.");
        }
        return true;
    }
}
