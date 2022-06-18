<?php

declare(strict_types=1);

namespace DOHWI\InvHelper\Type;

use DOHWI\InvHelper\InvHelper;
use DOHWI\InvHelper\Inventory\InventoryHelper;

use pocketmine\player\Player;
use pocketmine\world\Position;
use pocketmine\block\tile\Tile;
use pocketmine\block\tile\Chest;
use pocketmine\block\tile\Nameable;
use pocketmine\block\VanillaBlocks;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\scheduler\ClosureTask;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;

final class DoubleChestType extends BaseType
{
    public function getSize() : int
    {
        return 54;
    }

    public function onOpen(Player $who,InventoryHelper $inventoryHelper) : void
    {
        $vector = $who->getPosition()->add(0,4,0)->floor();
        $this->holder = new Position($vector->x,$vector->y,$vector->z,$who->getWorld());
        self::sendFakeBlock($vector,$who,VanillaBlocks::CHEST());
        self::sendFakeBlock($vector->add(1,0,0),$who,VanillaBlocks::CHEST());
        self::sendBlockActorData($vector,$who,
            CompoundTag::create()
                ->setString(Nameable::TAG_CUSTOM_NAME,$this->custom_name)
                ->setInt(Tile::TAG_X,$vector->x)
                ->setInt(Tile::TAG_Y,$vector->y)
                ->setInt(Tile::TAG_Z,$vector->z)
                ->setInt(Chest::TAG_PAIRX,$vector->x+1)
                ->setInt(Chest::TAG_PAIRZ,$vector->z)
        );
        $holder = $this->holder;
        InvHelper::$plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(static function() use ($who,$inventoryHelper,$holder) : void
        {
            self::sendContainerOpenPacket($holder,$who,$who->getNetworkSession()->getInvManager()->getWindowId($inventoryHelper),WindowTypes::CONTAINER);
            $inventoryHelper->openHandler($who);
        }),10);
    }

    public function onClose(Player $who,InventoryHelper $inventoryHelper) : void
    {
        self::removeFakeBlock($this->holder,$who);
        self::removeFakeBlock($this->holder->add(1,0,0),$who);
        $inventoryHelper->closeHandler($who);
    }
}