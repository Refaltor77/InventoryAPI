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
        $doubleChestInventory = InventoryAPI::createDoubleChest(isViewOnly: false);
        $doubleChestInventory->setName('DoubleChestInventory');
        $doubleChestInventory->setContents([
            VanillaItems::GOLD_INGOT(),
            VanillaItems::STEAK()
        ]);
        $doubleChestInventory->setClickListener(function (Player $player, BaseInventoryCustom $inventory, Item $sourceItem, Item $targetItem, int $slot): void {
            if ($slot === 0) {
                $inventory->transactionCancel();
            } else {
                $inventory->addItem(VanillaItems::COAL()->setCustomName("Example"));
            }
        });
        $doubleChestInventory->setCloseListener(function (Player $player, BaseInventoryCustom $inventory): void {
            Server::getInstance()->broadcastMessage("Hello close chest !");
        });
        $doubleChestInventory->send($player);


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