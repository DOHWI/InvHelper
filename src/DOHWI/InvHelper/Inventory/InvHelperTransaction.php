<?php

declare(strict_types=1);

namespace DOHWI\InvHelper\Inventory;

use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;

final class InvHelperTransaction implements Cancellable
{
    use CancellableTrait;

    public function __construct(private Player $player,private int $slot,private Item $sourceItem,private Item $targetItem) {}

    public function getPlayer() : Player
    {
        return $this->player;
    }

    public function getSlot() : int
    {
        return $this->slot;
    }

    public function getSourceItem() : Item
    {
        return $this->sourceItem;
    }

    public function getTargetItem() : Item
    {
        return $this->targetItem;
    }
}