<?php

declare(strict_types=1);

namespace Terpz710\HubPE\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Terpz710\HubPE\Main as Plugin;

class DeleteHubCommand extends Command implements PluginOwned {

    /** @var Config */
    private $config;

    /** @var Plugin */
    private $plugin;

    public function __construct(Config $config, Plugin $plugin) {
        parent::__construct("deletehub", "Delete the hub", null, ["deletelobby", "deletespawn", "delhub", "delspawn", "dellobby"]);
        $this->config = $config;
        $this->plugin = $plugin;
        $this->setPermission("hubpe.deletehub");
    }

    public function getOwningPlugin(): Plugin {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if ($sender instanceof Player) {
            if ($sender->hasPermission("hubpe.deletehub")) {
                $this->config->remove("hub");
                $this->config->save();
                $sender->sendMessage("§l§eHub location deleted");
            } else {
                $sender->sendMessage("§l§cYou don't have permission to use this command");
            }
        } else {
            $sender->sendMessage("This command can only be used by players.");
        }
        return true;
    }
}
