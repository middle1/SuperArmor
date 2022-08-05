<?php

namespace Q;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\ItemFactory;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use libs\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener {

private $config;

public function onEnable() : void{

$this->getServer()->getPluginManager()->registerEvents($this, $this); 
//Создание конфига
if(!is_dir($this->getDataFolder())){
mkdir($this->getDataFolder());
}
$this->saveResource("config.yml");
$this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
}

public function onCommand(CommandSender $p, Command $cmd, string $label, array $args) : bool {
if($cmd->getName() == "armor") {

if (!$p instanceof Player) {
$this->getLogger()->info("Только игрок");
return false;
}

if(!$p->isSurvival()){
$p->sendPopup("§cТолько в выживание!");
return false;
}

$inventory = $p->getInventory();

$form = new SimpleForm(function (Player $sender, ?int $result = null) {
if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
		$item_id = $sender->getInventory()->getItemInHand()->getId(); //переменная для проверки id предмета в руке игрока
		$item_count = $sender->getInventory()->getItemInHand()->getCount(); //переменная для счёта кол-во предметов в руке игрока
		if($item_id != $this->config->getNested("buy-armor.id-item-for-buy") || $item_count < $this->config->getNested("full-armor.cost")){
		$sender->sendMessage("Возьмите в руку ". $this->config->getNested("full-armor.cost")  . " алмазов");
		return false;
		}
		$sender->getInventory()->removeItem(ItemFactory::getInstance()->get($this->config->getNested("buy-armor.id-item-for-buy"), 0, $this->config->getNested("full-armor.cost")));
		$position = $sender->getPosition();
		//Все предметы
		$boots = ItemFactory::getInstance()->get($this->config->getNested("boots.id"), 0, 1); // Ботинки
		$chest = ItemFactory::getInstance()->get($this->config->getNested("chest.id"), 0, 1); // Кирасса
		$helmet = ItemFactory::getInstance()->get($this->config->getNested("helmet.id"), 0, 1); // Шлем
		$legins = ItemFactory::getInstance()->get($this->config->getNested("leggins.id"), 0, 1); // Штаны
		// Все чары
		$boots = $boots->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId($this->config->getNested("boots.enchants.id")), $this->config->getNested("boots.enchants.level")));
		$chest = $chest->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId($this->config->getNested("chest.enchants.id")), $this->config->getNested("chest.enchants.level")));
		$helmet = $helmet->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId($this->config->getNested("helmet.enchants.id")), $this->config->getNested("helmet.enchants.level")));
		$legins = $legins->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId($this->config->getNested("leggins.enchants.id")), $this->config->getNested("leggins.enchants.level")));
		// Имена предметов
		$boots = $boots->setCustomName($this->config->getNested("boots.name"));
		$chest = $chest->setCustomName($this->config->getNested("chest.name"));
		$helmet= $helmet->setCustomName($this->config->getNested("helmet.name"));
		$legins = $legins->setCustomName($this->config->getNested("leggins.name"));


if(!$sender->getInventory()->canAddItem($boots)){
$position->getWorld()->dropItem($position, $boots);
}else{
	$sender->getInventory()->addItem($boots);
}

if(!$sender->getInventory()->canAddItem($chest)){
$position->getWorld()->dropItem($position, $chest);
}else{
    $sender->getInventory()->addItem($chest);
}

if(!$sender->getInventory()->canAddItem($helmet)){
$position->getWorld()->dropItem($position, $helmet);
}else{
    $sender->getInventory()->addItem($helmet);
}

if(!$sender->getInventory()->canAddItem($legins)){
$position->getWorld()->dropItem($position, $legins);
}else{
    $sender->getInventory()->addItem($legins);
}
                break;
		case 1:
		$sender->sendMessage("После покупки вам будет выдана броня " . $this->config->getNested("leggins.enchants.level")  . " защиты\n Стоимость этой брони " . $this->config->getNested("full-armor.cost") . " алмазов");
		break;

                case 2:
  		return false;
                break;
            }
            return false;
        });
        $form->setTitle("§n§dПокупка супер-брони");
        $form->addButton("§8Купить супер-броню", 0, "textures/items/chainmail_chestplate.png");
	$form->addButton("§7Что это такое?", 0, "textures/items/book_normal.png");
        $form->addButton("§o§cВыйти");
	$form->sendToPlayer($p);
return true;
}
}

}
?>
