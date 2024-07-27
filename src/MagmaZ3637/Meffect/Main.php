<?php

namespace MagmaZ3637\Meffect;

use pocketmine\entity\effect\RegenerationEffect;
use pocketmine\permission\DefaultPermissions;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\Server;
use pocketmine\utils\Config;

use Vecnavium\FormsUI\FormsUI;
use Vecnavium\FormsUI\SimpleForm;

class Main extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->saveResource("config.yml");
        $this->getLogger()->info("Plugin Enabled");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        switch ($command->getName()){
            case "meffectui":
                if ($sender instanceof Player){
                    $this->getEffectForm($sender);
                }else{
                    $sender->sendMessage("Only Player");
                }
                break;
        }
        return true;
    }

    public function getEffectForm(Player $player)
    {
        $form = new SimpleForm(function (Player $player, $data){
           if (is_null($data)){
               return;
           }
           switch ($data){
               case 0:
                   # REGENERATION
                   if($player->hasPermission("effect.regen") or $player->hasPermission(DefaultPermissions::ROOT_OPERATOR)){
                       $player->getServer()->dispatchCommand(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()),  str_replace('{player}', $player->getName(), "effect {player} regeneration 999999 3"));
                       $player->sendToastNotification($this->getConfig()->get("name-server"), $this->getConfig()->get("message-success"). " §eRegeneration");
                   } else {
                       $player->sendToastNotification($this->getConfig()->get("server-name"), $this->getConfig()->get("message-perm"));
                   }
                   # TODO: Add More
           }
        });
        $form->setTitle($this->getConfig()->get("Title"));
        $form->setContent($this->getConfig()->get("Content"));
        # REGENERATION
        if($player->hasPermission("effect.regen") or $player->hasPermission(DefaultPermissions::ROOT_OPERATOR)){
    	$form->addButton("§l§cREGENERATION\n§rClick to use", 0, "textures/ui/check");
    } else {
    	$form->addButton("§l§cREGENERATION\n§r§cYou don't have permission", 0, "textures/blocks/barrier");
    }
        # TODO: Add More
        $form->addButton("Close\nClick To Close", 0, "textures/ui/cancel");
        $form->sendToPlayer($player);
    }
}
