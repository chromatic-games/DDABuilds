<div class="col-md-2 dropdown tower-container">
    <img class="dropbtn tower" src="images/tower/<?php echo $beam; ?>beam.png">
    <div class="dropdown-content">
        <?php
        $aTowers = Towers::getEVBeams($beam, $oDBH);
        include 'loadPool.php';
        ?>
    </div>
</div>