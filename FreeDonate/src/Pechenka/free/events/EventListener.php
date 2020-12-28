<?php

/**
 * ██████╗ ███████╗ █████╗ ██╗  ██╗███████╗███╗  ██╗██╗  ██╗ █████╗
 * ██╔══██╗██╔════╝██╔══██╗██║  ██║██╔════╝████╗ ██║██║ ██╔╝██╔══██╗
 * ██████╔╝█████╗  ██║  ╚═╝███████║█████╗  ██╔██╗██║█████═╝ ███████║
 * ██╔═══╝ ██╔══╝  ██║  ██╗██╔══██║██╔══╝  ██║╚████║██╔═██╗ ██╔══██║
 * ██║     ███████╗╚█████╔╝██║  ██║███████╗██║ ╚███║██║ ╚██╗██║  ██║
 * ╚═╝     ╚══════╝ ╚════╝ ╚═╝  ╚═╝╚══════╝╚═╝  ╚══╝╚═╝  ╚═╝╚═╝  ╚═╝
 *
 * DEVELOPED BY 𝗣𝗘𝗖𝗛𝗘𝗡𝗞𝗔
 * VK: 𝓿𝓴.𝓬𝓸𝓶/𝓿𝓸𝓿𝓪𝓷446
 */

namespace Pechenka\free\events;

use Pechenka\free\FreeDonate;
use Pechenka\free\utils\TimeUtil;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

/**
 * Class EventListener
 *
 * @package Pechenka\free\events
 */
class EventListener implements Listener
{
    
    /** @var array */
    public static $players = [];
    
    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        if (!FreeDonate::$data->exists($name)) return;
        
        if (($time = FreeDonate::$data->get($name)) >= FreeDonate::ONLINE_TIME) {
            FreeDonate::givePrize($player);
            return;
        }
        self::$players[$name] = time();
        $player->sendMessage("§7(§cFreeDonate§7) §fДо получения награды осталось: ".TimeUtil::toString(FreeDonate::ONLINE_TIME - $time));
    }
    
    /**
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event)
    {
        $name = $event->getPlayer()->getLowerCaseName();
        if (!isset(self::$players[$name])) return;
        
        $data = FreeDonate::$data->get($name);
        $data += time() - self::$players[$name];
        FreeDonate::$data->set($name, $data);
        unset(self::$players[$name]);
    }
}
