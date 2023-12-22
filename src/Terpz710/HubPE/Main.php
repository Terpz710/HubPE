<?php

declare(strict_types=1);

namespace Terpz710\HubPE;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\world\WorldManager;

use Terpz710\HubPE\command\HubCommand;
use Terpz710\HubPE\command\SetHubCommand;
use Terpz710\HubPE\command\DeleteHubCommand;

class Main extends PluginBase {

    public function onEnable(): void {
        if (!is_dir($this->getDataFolder() . "Hub")) {
            @mkdir($this->getDataFolder() . "Hub");
        }

        $config = new Config($this->getDataFolder() . "Hub" . DIRECTORY_SEPARATOR . "hub-data.json", Config::JSON);
        $worldManager = $this->getWorldManager();

        $this->getServer()->getCommandMap()->register("hub", new HubCommand($config, $this, $worldManager));
        $this->getServer()->getCommandMap()->register("sethub", new SetHubCommand($config, $this, $worldManager));
        $this->getServer()->getCommandMap()->register("deletehub", new DeleteHubCommand($config, $this));
    }
}
