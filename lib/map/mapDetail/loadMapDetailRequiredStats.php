<?php
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 09.06.2017
 * Time: 09:39
 *
 * <option value="ev">Series-EV</option>
 * <option value="summoner">Summoner</option>
 * <option value="jester">Jester</option>
 */

if ($create || $isCreator) {
    echo '
    <div class="form-group">
        <label for="requiredstatsselect">Required Attributes:</label>
        <select class="form-control" id="requiredstatsselect">
            <option value="squire">Squire</option>
            <option value="apprentice">Apprentice</option>
            <option value="huntress">Huntress</option>
            <option value="monk">Monk</option>
        </select>
        <div class="col-md-3">
        <label for="requiredstatshpinput">Fortify:</label>
        <input class="form-control" id="requiredstatshpinput" value="0">
        </div>
        <div class="col-md-3">
        <label for="requiredstatsdamageinput">Power:</label>
        <input class="form-control" id="requiredstatsdamageinput" value="0">
        </div>
        <div class="col-md-3">
        <label for="requiredstatsrangeinput">Range:</label>
        <input class="form-control" id="requiredstatsrangeinput" value="0">
        </div>
        <div class="col-md-3">
        <label for="requiredstatsrateinput">Def. Rate:</label>
        <input class="form-control" id="requiredstatsrateinput" value="0">
        </div>
    </div>';
} else {
    $placeds = Placeds::getAllPlacedsForBuild($build->getID());
    $classesUsed = array();
    $classesUsed["squire"] = false;
    $classesUsed["apprentice"] = false;
    $classesUsed["huntress"] = false;
    $classesUsed["monk"] = false;
    $classesUsed["ev"] = false;
    $classesUsed["summoner"] = false;
    $classesUsed["jester"] = false;

    $towerIDS = array();
    $towerIDS["squire"] = [1, 5];
    $towerIDS["apprentice"] = [6, 10];
    $towerIDS["huntress"] = [11, 15];
    $towerIDS["monk"] = [16, 20];
    $towerIDS["ev"] = [54, 71];
    $towerIDS["summoner"] = [26, 31];
    $towerIDS["jester"] = [32, 36];

    $tableHead = '';
    $tableEnd = '';
    $classData = '';
    $classStatUsed = false;

    foreach ($placeds as $placed) {
        foreach ($towerIDS as $key => $towerID) {
            if ($placed->getData('fk_tower') >= $towerIDS[$key][0] && $placed->getData('fk_tower') <= $towerIDS[$key][1]) {
                $classesUsed[$key] = true;
                $tableHead = '
                    <table class="table table-responsive table-hover">
                        <caption class="text-center">Required Hero Stats</caption>
                        <thead>
                        <tr>
                            <th>Hero</th>
                            <th>HP</th>
                            <th>Damage</th>
                            <th>Range</th>
                            <th>Rate</th>
                        </tr>
                        </thead>
                        <tbody>
                  ';
                $tableEnd = '</tbody></table>';
            }
        }
    }

    foreach ($classesUsed as $class => $used) {
        if ($used) {
            if ($build->getData($class . 'hp') > 0 || $build->getData($class . 'rate') > 0 || $build->getData($class . 'damage') > 0 || $build->getData($class . 'range') > 0) {
                $classStatUsed = true;
                $classData .= '<tr>
                  <td>' . ucfirst($class) . '</td>
                  <td>' . $build->getData($class . 'hp') . '</td>
                  <td>' . $build->getData($class . 'damage') . '</td>
                  <td>' . $build->getData($class . 'range') . '</td>
                  <td>' . $build->getData($class . 'rate') . '</td>
                  </tr>';
            }
        }
    }
    if ($classStatUsed){
        echo $tableHead;
        echo $classData;
        echo $tableEnd;
    }

}