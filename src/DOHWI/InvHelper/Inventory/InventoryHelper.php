<?php

declare(strict_types=1);

namespace DOHWI\InvHelper\Inventory;

use DOHWI\InvHelper\Type\BaseType;
use DOHWI\InvHelper\Type\InvHelperTypes;
use DOHWI\InvHelper\Type\DoubleChestType;
use DOHWI\InvHelper\Type\SingleBlockType;

use pocketmine\player\Player;
use pocketmine\world\Position;
use pocketmine\inventory\SimpleInventory;
use pocketmine\block\inventory\BlockInventory;

class InventoryHelper extends SimpleInventory implements BlockInventory
{
    protected BaseType $type;

    public function transactionHandler(InvHelperTransaction $transaction) : void {}

    public function openHandler(Player $player) : void {}

    public function closeHandler(Player $player) : void {}

    public function __construct(InvHelperTypes $type)
    {
        $this->type = match($type)
        {
            InvHelperTypes::TYPE_DOUBLE_CHEST() => new DoubleChestType(),
            default => new SingleBlockType($type->getTypeId())
        };
        parent::__construct($this->type->getSize());
    }

    final public function onOpen(Player $who) : void
    {
        parent::onOpen($who);
        $this->type->onOpen($who,$this);
    }

    final public function onClose(Player $who) : void
    {
        parent::onClose($who);
        $this->type->onClose($who,$this);
    }

    final public function getHolder() : Position
    {
        return $this->type->holder ?? new Position(0,0,0,null);
    }

    public function send(Player $player,string $name = '') : void
    {
        $this->type->custom_name = $name;
        $player->setCurrentWindow($this);
    }
}