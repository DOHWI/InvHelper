<?php

declare(strict_types=1);

namespace DOHWI\InvHelper\Type;

use DOHWI\InvHelper\Inventory\InventoryHelper;

use pocketmine\player\Player;
use pocketmine\world\Position;
use pocketmine\block\BlockFactory;
use pocketmine\block\tile\Nameable;
use pocketmine\block\VanillaBlocks;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\block\BlockLegacyIds;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;

final class SingleBlockType extends BaseType
{
    public function __construct(private int $windowType) {}

    public function getSize() : int
    {
        return match($this->windowType)
        {
            WindowTypes::DISPENSER => 9,
            WindowTypes::HOPPER => 5,
            default => 27
        };
    }

    public function onOpen(Player $who,InventoryHelper $inventoryHelper) : void
    {
        $vector = $who->getPosition()->add(0,4,0);
        $this->holder = new Position($vector->x,$vector->y,$vector->z,$who->getWorld());
        self::sendFakeBlock($vector,$who,match($this->windowType)
        {
            WindowTypes::DISPENSER => BlockFactory::getInstance()->get(BlockLegacyIds::DISPENSER,0),
            WindowTypes::HOPPER => VanillaBlocks::HOPPER(),
            default => VanillaBlocks::CHEST()
        });
        self::sendBlockActorData($vector,$who,CompoundTag::create()->setString(Nameable::TAG_CUSTOM_NAME,$this->custom_name));
        self::sendContainerOpenPacket($vector,$who,$who->getNetworkSession()->getInvManager()->getWindowId($inventoryHelper),$this->windowType);
        $inventoryHelper->openHandler($who);
    }

    public function onClose(Player $who,InventoryHelper $inventoryHelper) : void
    {
        self::removeFakeBlock($this->holder,$who);
        $inventoryHelper->closeHandler($who);
    }
}