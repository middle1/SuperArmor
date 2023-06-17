<?php

namespace superarmor\middle\Data;

use pocketmine\item\VanillaItems;
use pocketmine\utils\Config;

class Items {

    private $config;

    public function __construct(Config $config) {
		$this->config = $config;
	}
	
	public function getName(string $data){
		switch($data){
			case 'boots':
			$name =  $this->config->getNested("boots.ItemName");
			return VanillaItems::$name(); // Ботинки
			break;
			case 'chest':
			$name = $this->config->getNested("chest.ItemName");
			return VanillaItems::$name(); // Кирасса
			break;
			case 'helmet':
            $name = $this->config->getNested("helmet.ItemName");
			return VanillaItems::$name(); // Шлем
			break;
			case 'leggins':
			$name = $this->config->getNested("leggins.ItemName");
			return VanillaItems::$name(); // Штаны
			break;
			default:
			return null;
			break;
		}
	}
		
	public function setName(string $dataCommand, $dataItem){
		switch($dataCommand){
			case 'boots':
			return $dataItem->setCustomName($this->config->getNested("boots.name"));
			return $boots;
			break;
			case 'chest':
			return $dataItem->setCustomName($this->config->getNested("chest.name"));
			break;
			case 'helmet':
			 return $dataItem->setCustomName($this->config->getNested("helmet.name"));
			break;
			case 'leggins':
			 return $dataItem->setCustomName($this->config->getNested("leggins.name"));
			break;
		}
	}
}
?>