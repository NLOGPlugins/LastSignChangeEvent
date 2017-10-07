<?php

namespace nlog\LastSignChangeEvent;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\MainLogger;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\network\mcpe\protocol\MovePlayerPacket;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;

class Main extends PluginBase implements Listener {
	
	/* @var array */
	private $writing = [];

  public function onEnable() {
  	$this->getServer()->getPluginManager()->registerEvents($this, $this);
  	MainLogger::getLogger()->info("LastSignChangeEvent 플러그인 활성화");
  }
  
  public function onRecieved (DataPacketReceiveEvent $ev) {
  	$packet = $ev->getPacket();
  	if ($packet instanceof MovePlayerPacket || $packet instanceof InteractPacket || $packet instanceof PlayerActionPacket) {
  		if (isset($this->writing[$ev->getPlayer()->getId()])) {
  			unset($this->writing[$ev->getPlayer()->getId()]);
  			$ev = new LastSignChangeEvent($this->writing[$ev->getPlayer()->getId()], $ev->getPlayer());
  			$this->getServer()->getPluginManager()->callEvent($ev);
  			if ($ev->isCancelled()) {
  				return;
  			}
  			$ev->getSign()->onUpdate();
  			return;
  		}
  	}
  }
  
  public function onQuit (PlayerQuitEvent $ev) {
  	unset($this->writing[$ev->getPlayer()->getId()]);
  }
  
  public function onSignChange (SignChangeEvent $ev) {
  	$this->writing[$ev->getPlayer()->getId()] = $ev->getBlock();
  }
  
  
  
 

}//클래스 괄호

?>