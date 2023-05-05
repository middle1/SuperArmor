<?php

namespace superarmor\middle\Data;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\utils\Config;

class Items {
	
	public function __construct(Config $config) {
		$this->config = $config;
	}
	
	public function getId(string $data){
		switch($data){
			case 'boots':
			return $boots = ItemFactory::getInstance()->get($this->config->getNested("boots.id"), 0, 1); // Ботинки
			break;
			case 'chest':
			return $chest = ItemFactory::getInstance()->get($this->config->getNested("chest.id"), 0, 1); // Кирасса
			break;
			case 'helmet':
			return $helmet = ItemFactory::getInstance()->get($this->config->getNested("helmet.id"), 0, 1); // Шлем
			break;
			case 'legins':
			return $legins = ItemFactory::getInstance()->get($this->config->getNested("leggins.id"), 0, 1); // Штаны
			break;
			default:
			return null;
			break;
		}
	}
		
	public function setName(string $dataCommand, $dataItem){
		switch($dataCommand){
			case 'boots':
			 $boots = $dataItem->setCustomName($this->config->getNested("boots.name"));
			return $boots;
			break;
			case 'chest':
			 $chest = $dataItem->setCustomName($this->config->getNested("chest.name"));
			return $chest;
			break;
			case 'helmet':
			 $helmet = $dataItem->setCustomName($this->config->getNested("helmet.name"));
			return $helmet;
			break;
			case 'legins':
			 $legins = $dataItem->setCustomName($this->config->getNested("leggins.name"));
			return $legins;
			break;
		}
	}
}
?>