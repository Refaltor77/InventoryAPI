<?php

namespace refaltor\inventoryapi;

use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use refaltor\inventoryapi\events\PacketListener;
use refaltor\inventoryapi\inventories\{BaseInventoryCustom, SimpleChestInventory, DoubleInventory};

class InventoryAPI extends PluginBase
{
    /*
     * Features: workbench inventory, hopper inventory
     */

    private static self $instance;

    public static function getInstance(): self {
        return self::$instance;
    }

    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents(new PacketListener(), $this);
    }

    public static function createSimpleChest(bool $isViewOnly = false): SimpleChestInventory {
        $inventory = new SimpleChestInventory();
        $inventory->setViewOnly($isViewOnly);
        return $inventory;
    }

    public static function createDoubleChest(bool $isViewOnly = false): SimpleChestInventory {
        $inventory = new DoubleInventory();
        $inventory->setViewOnly($isViewOnly);
        return $inventory;
    }

    public function getDelaySend(): int {
        return $this->getConfig()->get('delay-send-double-chest') ?? 10;
    }
}
