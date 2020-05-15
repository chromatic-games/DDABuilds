<?php
if (!empty($_GET['load']) || isset($_GET['thumbnail'])) {

    $placeds = Placeds::getPlacedsForBuild($build->getID(), $buildwave, $oDBH);
    foreach ($placeds as $oTowerPlaced) {
        $type = '';
        $curTower = new Tower();
        $curTower->setID($oTowerPlaced->getData('fk_tower'));
        $curTower->load();
        if ($curTower->getID() <= 20 && $curTower->getID() >= 16) {
            $type = "aura";
        } else if ($curTower->getID() <= 15 && $curTower->getID() >= 11) {
            $type = "trap";
        } else if ($curTower->getID() <= 53 && $curTower->getID() >= 42) {
            $type = "arrow";
        } else if ($curTower->getID() <= 71 && $curTower->getID() >= 54) {
            $type = "beam";
        } else if ($curTower->getID() <= 31 && $curTower->getID() >= 26) {
            $type = "summoner";
        }
        
        $menu = '';
        
        if ($type != 'aura' && $type != 'trap' && $type != 'summoner') {
            $menu = '<div class="menu">
                    <i class="fa fa-repeat"></i>
                </div>';
        }
        
        $du = $curTower->getData('unitcost');
        
        if ($oTowerPlaced->getData('override_du') > 0 ) {
            $du = $oTowerPlaced->getData('override_du');
        }
        
        echo '
            <div class="tower-container placed ' . $type . '" 
                wave="' . $oTowerPlaced->getData('fk_buildwave') . '"
                defenseid="' . $curTower->getData('id') . '"
                mu="' . $curTower->getData('mu') . '"
                unitcost="' . $du . '"
                manacost="' . $curTower->getData('manacost') . '"
                style="position: absolute;
                left: ' . $oTowerPlaced->getData('x') . 'px;
                top: ' . $oTowerPlaced->getData('y') . 'px;
                transform: rotate(' . $oTowerPlaced->getData('rotation') . 'deg);
                transform-origin: 50% 50% 0px;"
            >
            
                <div class="placed">
                    <img class="placed tower ' . $type . '" src="' . $curTower->getImage() . '" 
                    title="' . $curTower->getData('name') . '"
                    >
                </div>
                
                ' . $menu . '
            </div>
            ';

    }
}
