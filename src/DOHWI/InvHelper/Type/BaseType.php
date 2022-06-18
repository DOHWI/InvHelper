<?php

declare(strict_types=1);

namespace DOHWI\InvHelper\Type;

use DOHWI\InvHelper\Inventory\InventoryHelper;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\block\tile\Spawnable;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;

abstract class BaseType
{
    public ? Position $holder = null;
    public string $custom_name = '';

    protected static function sendFakeBlock(Vector3 $vector,Player $player,Block $block) : void
    {
        $player->getNetworkSession()->sendDataPacket(UpdateBlockPacket::create(BlockPosition::fromVector3($vector),RuntimeBlockMapping::getInstance()->toRuntimeId($block->getFullId()),UpdateBlockPacket::NETWORK_ID,UpdateBlockPacket::DATA_LAYER_NORMAL));
    }

    protected static function removeFakeBlock(Vector3 $vector,Player $player) : void
    {
        $world = $player->getWorld();
        self::sendFakeBlock($vector,$player,$world->getBlock($vector));
        $tile = $world->getTile($vector);
        if($tile instanceof Spawnable) $player->getNetworkSession()->sendDataPacket(BlockActorDataPacket::create(BlockPosition::fromVector3($vector),$tile->getSerializedSpawnCompound()));
    }

    protected static function sendContainerOpenPacket(Vector3 $vector,Player $player,int|null $windowId,int $windowType) : void
    {
        if($windowId === null) return;
        $player->getNetworkSession()->sendDataPacket(ContainerOpenPacket::blockInv($windowId,$windowType,BlockPosition::fromVector3($vector)));
    }

    protected static function sendBlockActorData(Vector3 $vector,Player $player,CompoundTag $nbt) : void
    {
        $player->getNetworkSession()->sendDataPacket(BlockActorDataPacket::create(BlockPosition::fromVector3($vector),new CacheableNbt($nbt)));
    }

    abstract public function onOpen(Player $who,InventoryHelper $inventoryHelper) : void;

    abstract public function onClose(Player $who,InventoryHelper $inventoryHelper) : void;

    abstract public function getSize() : int;
}
