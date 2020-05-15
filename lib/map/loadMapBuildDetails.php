<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Details:</div>
            <div class="panel-body">
                <?php
                include 'mapDetail/loadMapDetailRequiredStats.php';
                include 'mapDetail/loadMapDetailStatus.php';
                include 'mapDetail/loadMapDetailDifficulty.php';
                include 'mapDetail/loadMapDetailMode.php';
                include 'mapDetail/loadMapDetailHardcore.php';
                include 'mapDetail/loadMapDetailMixMode.php';
                include 'mapDetail/loadMapDetailAfkable.php';
                include 'mapDetail/loadMapDetailXPPerRun.php';
                include 'mapDetail/loadMapDetailTimePerRun.php';

                ?>
                <h4>Mana Used: <b><span class="curMana">0</span></b></h4>
                <h4>Mana to Upgrade: <b><span class="upgradeMana">0</span></b></h4>
                <?php
                if (!$isCreator && !$create) {
                    echo '<br><br>';
                    echo 'More Builds from <a href=/list.php?author=' . $build->getData('author') . '>' . $build->getData('author') . '</a>';
                }
                ?>
            </div>
        </div>
    </div>
</div>