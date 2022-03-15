<?php

namespace refaltor\inventoryapi;

use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use refaltor\inventoryapi\events\PacketListener;
use refaltor\inventoryapi\inventories\{BaseInventoryCustom, SimpleChestInventory, DoubleInventory};

class InventoryAPI extends PluginBase
{
    /*
     * Features: workbench inventory, hopper inventory
     */
    
    use SingletonTrait;

    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents(new PacketListener(), $this);
    }

    public function create(string $type = "simple", bool $isViewOnly = false): BaseInventoryCustom {
        return match($type) {
            "double" => (new DoubleInventory()),
            default => (new SimpleChestInventory())
        }->setViewOnly($isViewOnly);
    }
    
    public function getDelaySend(): int {
        return $this->getConfig()->get('delay-send-double-chest') ?? 10;
    }
}
