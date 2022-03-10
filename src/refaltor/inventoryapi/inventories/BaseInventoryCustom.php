<?php

namespace refaltor\inventoryapi\inventories;

use pocketmine\block\inventory\BlockInventory;
use pocketmine\block\inventory\BlockInventoryTrait;
use pocketmine\inventory\SimpleInventory;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\player\Player;

abstract class BaseInventoryCustom extends SimpleInventory implements BlockInventory
{
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
}