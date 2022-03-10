<?php

namespace refaltor\inventoryapi;

use pocketmine\plugin\PluginBase;
use refaltor\inventoryapi\events\PacketListener;
use refaltor\inventoryapi\inventories\DoubleInventory;
use refaltor\inventoryapi\inventories\SimpleChestInventory;

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

    public function createSimpleChest(bool $isViewOnly = false): SimpleChestInventory {
        $inventory = new SimpleChestInventory();
        $inventory->setViewOnly($isViewOnly);
        return $inventory;
    }

    public function createDoubleChest(bool $isViewOnly = false): SimpleChestInventory {
        $inventory = new DoubleInventory();
        $inventory->setViewOnly($isViewOnly);
        return $inventory;
    }

    public function getDelaySend(): int {
        return $this->getConfig()->get('delay-send-double-chest') ?? 10;
    }
}