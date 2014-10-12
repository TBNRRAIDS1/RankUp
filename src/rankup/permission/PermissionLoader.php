<?php
namespace rankup\permission;

use rankup\RankUp;

class PermissionLoader{
    private $plugin;
    public function __construct(RankUp $plugin){
        $this->plugin = $plugin;
    }
    public function load(){
        if($this->plugin->getConfig()->get('preferred-groupmanager') !== false){
            $name = $this->plugin->getConfig()->get('preferred-groupmanager');
            if(class_exists($name) && is_subclass_of($name, BasePermissionManager::class)){
                $permManager = new $name($this->plugin);
                if($permManager instanceof BasePermissionManager){
                    if($permManager->isReady()){
                        $this->plugin->set($permManager);
                        $this->plugin->getLogger()->info("Loaded " . $permManager->getName());
                    }
                    else{
                        $this->plugin->getLogger()->critical("The preferred-groupmanager you specified is not loaded.");
                    }
                }
            }
            else{
                $this->plugin->getLogger()->critical("The preferred-groupmanager you specified is not supported.");
            }
        }
        else{
            //TODO autoload xPermissions and RankUpDoesGroups
        }
    }
}