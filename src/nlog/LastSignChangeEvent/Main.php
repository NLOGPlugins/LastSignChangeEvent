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
use pocketmine\Player;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\network\mcpe\protocol\ServerSettingsResponsePacket;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\network\mcpe\protocol\BookEditPacket;

class Main extends PluginBase implements Listener {
	
	/* @var array */
	private $writing = [];

  public function onEnable() {
  	$this->getServer()->getPluginManager()->registerEvents($this, $this);
  	MainLogger::getLogger()->info("LastSignChangeEvent 플러그인 활성화");
  }
  
  public function check(Player $player) {
  	if (isset($this->writing[$player->getId()])) {
  		$this->onProcess($player);
  	}
  }
  
  public function onProcess(Player $player) {
  	unset($this->writing[$player->getId()]);
  	$ev = new LastSignChangeEvent($this->writing[$player->getId()], $player);
  	$this->getServer()->getPluginManager()->callEvent($ev);
  	if ($ev->isCancelled()) {
  		return;
  	}
  	$ev->getSign()->onUpdate();
  }
  
  public function onRecieved (DataPacketReceiveEvent $ev) {
  	$packet = $ev->getPacket();
  	if ($packet instanceof MovePlayerPacket || 
  		$packet instanceof InteractPacket || 
  		$packet instanceof PlayerActionPacket || 
  		$packet instanceof ModalFormResponsePacket ||
  		$packet instanceof ServerSettingsResponsePacket ||
  		$packet instanceof TextPacket ||
  		$packet instanceof ContainerOpenPacket || $packet instanceof ContainerClosePacket ||
  		/*$packet instanceof AnimatePacket*/ $packet instanceof BookEditPacket
  		) {
  		self::check($ev->getPlayer());
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
