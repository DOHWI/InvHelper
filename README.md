<h1>InvHelper</h1>

<a href="https://www.php.net">
    <img src="https://img.shields.io/badge/PHP-777BB4?style=flat&logo=PHP&logoColor=white">
</a>

<a href="https://github.com/pmmp/Pocketmine-MP">
    <img src="https://img.shields.io/badge/PMMP-gray?style=flat">
</a>

<a href="https://github.com/poggit/devirion">
    <img src="https://img.shields.io/badge/Virion-gray?style=flat">
</a>

<details>
<summary>How to register a InvHelper ?</summary>
<div markdown="1">

```php
<?php

use DOHWI\InvHelper\InvHelper;

use pocketmine\plugin\PluginBase;

class YourPlugin extends PluginBase
{
    InvHelper::register($this);
}

?>
```

</div>
</details>

<details>
<summary>How to handle open,close,transaction ?</summary>
<div markdown="1">

```php
<?php

use DOHWI\InvHelper\Inventory\InventoryHelper;
use DOHWI\InvHelper\Inventory\InvHelperTransaction;

use pocketmine\player\Player;

class ExampleInventory extends InventoryHelper
{
    protected function openHandler(Player $player) : void
    {
        # Do Something
    }
    
    protected function closeHandler(Player $player) : void
    {
        # Do Something
    }
    
    public function transactionHandler(InvHelperTransaction $transaction) : void
    {
        $player = $transaction->getPlayer();
        $slot = $transaction->getSlot();
        $sourceItem = $transaction->getSourceItem();
        $targetItem = $transaction->getTargetItem();
        $transaction->cancel(); # if you want cancel
    }

?>
```

</div>
</details>

<details>
<summary>How to send to Player ?</summary>
<div markdown="1">

```php
<?php

use DOHWI\InvHelper\Type\InvHelperTypes;

$inv = new ExampleInventory(InvHelperTypes::TYPE);
$inv->send(Player,"Inventory Title");

?>
```

</div>
</details>
