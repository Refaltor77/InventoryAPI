<?php

namespace refaltor\inventoryapi\inventories;

use pocketmine\block\VanillaBlocks;
use pocketmine\block\inventory\BlockInventory;
use pocketmine\block\inventory\BlockInventoryTrait;
use pocketmine\inventory\SimpleInventory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\player\Player;
use pocketmine\block\tile\Nameable;
use pocketmine\world\Position;

class SimpleChestInventory extends SimpleInventory implements BlockInventory {

    use BlockInventoryTrait;
    protected string $name = "Chest";
    protected bool $hasViewOnly = false;
    protected $clickListener = null;
    protected $closeListener = null;
    private bool $transactionCancel = false;


    public function __construct(int $size = 27) {
        parent::__construct($size);
    }

    public function getName() : string{
        return $this->name;
    }

    public function transactionCancel(): void {
        $this->transactionCancel = true;
    }

    public function isCancelTransaction(): bool {
        return $this->transactionCancel;
    }

    public function reloadTransaction(): void {
        $this->transactionCancel = false;
    }

    public function setName(string $value){
        $this->name = $value;
    }

    public function setViewOnly(bool $value = true){
        $this->hasViewOnly = $value;
    }

    public function isViewOnly() : bool{
        return $this->hasViewOnly;
    }

    public function getClickListener(){
        return $this->clickListener;
    }

    public function setClickListener(?callable $callable){
        $this->clickListener = $callable;
    }

    public function getCloseListener(){
        return $this->closeListener;
    }

    public function setCloseListener(?callable $callable){
        $this->closeListener = $callable;
    }

    public function onClose(Player $who) : void {
        parent::onClose($who);
        $who->getNetworkSession()->sendDataPacket(UpdateBlockPacket::create(BlockPosition::fromVector3($this->holder), RuntimeBlockMapping::getInstance()->toRuntimeId($who->getWorld()->getBlock($this->holder)->getFullId()), UpdateBlockPacket::FLAG_NETWORK, UpdateBlockPacket::DATA_LAYER_NORMAL));
        $closeListener = $this->getCloseListener();
        if ($closeListener !== null){
            $closeListener($who, $this);
        }
    }

    public function send(Player $player){
        $this->holder = new Position((int)$player->getPosition()->getX(), (int)$player->getPosition()->getY() + 3, (int)$player->getPosition()->getZ(), $player->getWorld());
        $player->getNetworkSession()->sendDataPacket(UpdateBlockPacket::create(BlockPosition::fromVector3($this->holder), RuntimeBlockMapping::getInstance()->toRuntimeId(VanillaBlocks::CHEST()->getFullId()), UpdateBlockPacket::FLAG_NETWORK, UpdateBlockPacket::DATA_LAYER_NORMAL));
        $nbt = CompoundTag::create()->setString(Nameable::TAG_CUSTOM_NAME, $this->getName());
        $packet = BlockActorDataPacket::create(BlockPosition::fromVector3($this->holder), new CacheableNbt($nbt));
        $player->getNetworkSession()->sendDataPacket($packet);
        $player->setCurrentWindow($this);
    }
}