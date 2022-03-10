<h1 align="center">InventoryAPI | 4.0.0</h1>
<p align="center">
  <img width="250" height="250" src="https://github.com/Refaltor77/InventoryAPI/blob/main/icon.png">
</p>

<h2 align="center">Simple API to create GUI for your server ! 🎊</h2>


Uses: 
`use refaltor\inventoryapi\inventories\{SimpleChestInventory, DoubleInventory};`

Create Simple Inventory: 
```PHP
$simpleInventory = InventoryAPI::createSimpleChest(isViewOnly: false);
        $simpleInventory->setName('SimpleInventory');
        $simpleInventory->setContents([
            VanillaItems::GOLD_INGOT(),
            VanillaItems::STEAK()
        ]);
        $simpleInventory->setClickListener(function (Player $player, BaseInventoryCustom $inventory, Item $sourceItem, Item $targetItem, int $slot): void {
            if ($slot === 0) {
                $inventory->transactionCancel();
            } else {
                $inventory->addItem(VanillaItems::COAL()->setCustomName("Example"));
            }
        });
        $simpleInventory->setCloseListener(function (Player $player, BaseInventoryCustom $inventory): void {
            Server::getInstance()->broadcastMessage("Hello close chest !");
        });
        $simpleInventory->send($player);
```

Create Double Chest Inventory:
```PHP
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
```
