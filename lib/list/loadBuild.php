<?php
$map = new Map();
$map->setID($build->getData('map'));
$map->load();
$difficulty = new Difficulty();
$difficulty->setID($build->getData('difficulty'));
$difficulty->load();
$votes = Votes::getBuildVoting($build->getID());
$color = "";
if ($votes > 0) {
    $color = ' style="color:green"';
} else if ($votes < 0) {
    $color = ' style="color:red"';
}
echo '
    <tr>
        <td><a href="?page=map&load=' . $build->getID() . '">' . htmlspecialchars($build->getData('name')) . '</a></td>
        <td>' . $map->getData('name') . '</td>
        <td>' . $difficulty->getData('name') . '</td>
        <td><span' . $color . '>' . $votes . '</span></td>
        <td>' . $build->getData('views') . '</td>
        <td>' . date('d F Y', strtotime($build->getData('date'))) . '</td>
        <td>' . $build->getData('author') . '</td>
    </tr>
    ';