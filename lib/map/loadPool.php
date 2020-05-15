<?php
$type = '';
$id = 0;
$br = '';
foreach ($aTowers as $tower) {
    if ($tower->getID() <= 20 && $tower->getID() >= 16) {
        $type = "aura";
    } else if ($tower->getID() <= 15 && $tower->getID() >= 11) {
        $type = "trap";
    } else if ($tower->getID() <= 53 && $tower->getID() >= 42) {
        $type = "arrow";
    } else if ($tower->getID() <= 71 && $tower->getID() >= 54) {
        $type = "beam";
    } else if ($tower->getID() <= 31 && $tower->getID() >= 26) {
        $type = "summoner";
    }
    
    if ($type == "beam") {
        $br = '<br>';
    }

    $wave = 0;
    if (!empty($buildwave)) {
        $wave = $buildwave;
    }
    
    $menu = '';
    
    if ($type != 'aura' && $type != 'trap' && $type != 'summoner') {
        $menu = '<div class="menu">
                    <i class="fa fa-repeat"></i>
                </div>';
    }

    echo '
    <div class="col-md-2 defense-placeholder ' . $type . '">
        <div class="tower-container defense ' . $type . '"
             defenseid="' . $tower->getData('id') . '"
             mu="' . $tower->getData('mu') . '"
             unitcost="' . $tower->getData('unitcost') . '"
             manacost="' . $tower->getData('manacost') . '"
             wave="' . $wave . '"
        >
    
            <div class="pool">
                <img class="tower pool ' . $type . '" src="' . $tower->getImage() . '"
                     title="' . $tower->getData('name') . '"
                >
            </div>
    
            ' . $menu . '
        </div>
    </div>
    ' . $br;
    $id++;
}
