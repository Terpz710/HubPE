<?php

declare(strict_types=1);

namespace Terpz710\HubPE\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\Config;
use pocketmine\world\Position;
use Terpz710\HubPE\Main;
use pocketmine\world\WorldManager;

class HubCommand extends Command implements PluginOwned {

    /** @var Config */
    private $config;

    /** @var Plugin */
    private $plugin;

    /** @var WorldManager */
    private $worldManager;

    public function __construct(Config $config, Main $plugin, WorldManager $worldManager) {
        parent::__construct(
            "hub",
            "Teleport to hub",
            "/hub",
            ["lobby", "spawn"]
        );
        $this->setPermission("hubpe.hub");
        $this->config = $config;
        $this->plugin = $plugin;
        $this->worldManager = $worldManager;
    }

    public function getOwningPlugin(): Plugin {
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if ($sender instanceof Player) {
            if ($sender->hasPermission("hubpe.hub")) {
                $hubData = $this->config->get("hub");

                if (is_array($hubData) && isset($hubData["world"], $hubData["x"], $hubData["y"], $hubData["z"])) {
                    $worldName = $hubData["world"];
                    $x = $hubData["x"];
                    $y = $hubData["y"];
                    $z = $hubData["z"];

                    $world = $this->worldManager->getWorldByName($worldName);
                    if ($world === null) {
                        $this->worldManager->loadWorld($worldName);
                        $world = $this->worldManager->getWorldByName($worldName);

                        if ($world === null) {
                            $sender->sendMessage("§l§cFailed to load the hub world. Check if the world folder is in the right directory");
                            return false;
                        }
                    }

                    $position = new Position($x, $y, $z, $world);
                    $sender->teleport($position);
                    $sender->sendMessage("§l§aTeleported to the hub");
                } else {
                    $sender->sendMessage("§c§lHub location data is invalid or not set. Use /sethub to set the hub or make sure it's set up correctly");
                }
            } else {
                $sender->sendMessage("§c§lYou don't have permission to use this command");
            }
        } else {
            $sender->sendMessage("This command can only be used by players.");
        }
        return true;
    }
}
