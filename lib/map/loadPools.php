<div class="row">
    <div class="col-sm-6">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Squire <label><input type="checkbox" class="disableckbx" value="squire"/>
                        Disable View</label><br><button class="front-tower" value="squire">Front</button><button class="back-tower" value="squire">Back</button></div>
                <div class="panel-body">
                    <?php
                    $aTowers = Towers::getTowersForClass(1);
                    include 'loadPool.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Apprentice <label><input type="checkbox" class="disableckbx"
                                                                    value="apprentice"/> Disable View</label>
                    <br><button class="front-tower" value="apprentice">Front</button><button class="back-tower" value="apprentice">Back</button></div>
                <div class="panel-body">
                    <?php
                    $aTowers = Towers::getTowersForClass(2);
                    include 'loadPool.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Monk <label><input type="checkbox" class="disableckbx" value="monk"/> Disable
                        View</label>
                    <br><button class="front-tower" value="monk">Front</button><button class="back-tower" value="monk">Back</button></div>
                <div class="panel-body">
                    <?php
                    $aTowers = Towers::getTowersForClass(4);
                    include 'loadPool.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Huntress <label><input type="checkbox" class="disableckbx" value="huntress"/>
                        Disable View</label>
                    <br><button class="front-tower" value="huntress">Front</button><button class="back-tower" value="huntress">Back</button></div>
                <div class="panel-body">
                    <?php
                    $aTowers = Towers::getTowersForClass(3);
                    include 'loadPool.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <?php if (!HIDETHIS) { ?>
    <div class="col-sm-6">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Series-EV <label><input type="checkbox" class="disableckbx"
                                                                   value="series-ev"/> Disable View</label>
                    <br><button class="front-tower" value="ev">Front</button><button class="back-tower" value="ev">Back</button></div>
                <div class="panel-body">
                    <?php
                    $aEVBeams = array('proton', 'physical', 'reflection', 'shock', 'towerbuff');
                    foreach ($aEVBeams as $beam) {
                        include 'loadEVPool.php';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
</div>
<div class="row">
<?php if (!HIDETHIS) { ?>
    <div class="col-sm-6">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Summoner <label><input type="checkbox" class="disableckbx" value="summoner"/>
                        Disable View</label>
                    <br><button class="front-tower" value="summoner">Front</button><button class="back-tower" value="summoner">Back</button></div>
                <div class="panel-body">
                    <?php
                    $aTowers = Towers::getTowersForClass(6);
                    include 'loadPool.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Jester <label><input type="checkbox" class="disableckbx" value="jester"/>
                        Disable View</label>
                    <br><button class="front-tower" value="jester">Front</button><button class="back-tower" value="jester">Back</button></div>
                <div class="panel-body">
                    <?php
                    $aTowers = Towers::getTowersForClass(7);
                    include 'loadPool.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php }?>
    <div class="col-sm-6">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">World <label><input type="checkbox" class="disableckbx" value="world"/>
                        Disable View</label>
                    <br><button class="front-tower" value="world">Front</button><button class="back-tower" value="world">Back</button></div>
                <div class="panel-body">
                    <?php
                    $aTowers = Towers::getTowersForClass(20);
                    include 'loadPool.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Hints <label><input type="checkbox" class="disableckbx" value="hints"/>
                        Disable View</label>
                    <br><button class="front-tower" value="hints">Front</button><button class="back-tower" value="hints">Back</button></div>
                <div class="panel-body">
                    <?php
                    $aTowers = Towers::getTowersForClass(21);
                    include 'loadPool.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Arrows <label><input type="checkbox" class="disableckbx" value="arrow"/>
                        Disable View</label>
                    <br><button class="front-tower" value="arrow">Front</button><button class="back-tower" value="arrow">Back</button></div>
                <div class="panel-body">
                    <?php
                    $aTowers = Towers::getTowersForClass(22);
                    include 'loadPool.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
