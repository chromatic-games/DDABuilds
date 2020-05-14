<?php
if (!empty($_POST)) {
    require_once 'steamauth/steamauth.php';
    require_once 'steamauth/userInfo.php';
    
    if (!empty($_POST['highlightTower'])) {
        session_start();
        $bool = filter_var($_POST['highlightTower'], FILTER_VALIDATE_BOOLEAN);
        $_SESSION['highlightTower'] = $bool;
        exit('Error 400');
    }
    
    if (empty($steamprofile['steamid'])) {
        http_response_code(404);
        exit('Not logged into steam');
    }

    if (!empty($_POST['buildid']) && !empty($_POST['delete'])) {
        if (Builds::getBuildOwner($_POST['buildid']) == $steamprofile['steamid']) {
            $build = new Build();
            $build->setID($_POST['buildid']);
            $build->load();
            $build->setData('deleted', 1);
            $build->save();
        }
        exit('Error 200');
    } else if (empty($_POST['mapid']) ||
        empty($_POST['author']) ||
        empty($_POST['buildname']) ||
        $_POST['difficulty'] < 0 ||
        $_POST['difficulty'] > 6 ||
        empty($_POST['buildstatus']) ||
        empty($_POST['placedtower']) ||
        empty($_POST['gamemode'])
    ) {
        http_response_code(404);
        exit('Missing Parameter');
    }

    if (!is_numeric($_POST['hardcore']) ||
        !is_numeric($_POST['buildstatus']) ||
        !is_numeric($_POST['difficulty']) ||
        !is_numeric($_POST['mapid']) ||
        !is_numeric($_POST['afkable']) ||
        $_POST['hardcore'] < 0 ||
        $_POST['hardcore'] > 1 ||
        $_POST['afkable'] < 0 ||
        $_POST['afkable'] > 1
    ) {
        http_response_code(404);
        exit('Not Numeric');
    }

    if ($_POST['gamemode'] == 'purestrategy' && $_POST['hardcore'] == 1) {
        http_response_code(404);
        exit('Pure Strat and HC');
    }

    $campaign = 0;
    $survival = 0;
    $challenge = 0;
    $purestrategy = 0;
    $mixmode = 0;

    $squirestats = array(0,0,0,0);
    $apprenticestats = array(0,0,0,0);
    $huntressstats = array(0,0,0,0);
    $monkstats = array(0,0,0,0);
    $evstats = array(0,0,0,0);
    $summonerstats = array(0,0,0,0);
    $jesterstats = array(0,0,0,0);


    if (isset($_POST['squirestats'])) {
        $squirestats = $_POST['squirestats'];
    }
    if (isset($_POST['apprenticestats'])) {
        $apprenticestats = $_POST['apprenticestats'];
    }
    if (isset($_POST['huntressstats'])) {
        $huntressstats = $_POST['huntressstats'];
    }
    if (isset($_POST['monkstats'])) {
        $monkstats = $_POST['monkstats'];
    }
    if (isset($_POST['evstats'])) {
        $evstats = $_POST['evstats'];
    }
    if (isset($_POST['summonerstats'])) {
        $summonerstats = $_POST['summonerstats'];
    }
    if (isset($_POST['jesterstats'])) {
        $jesterstats = $_POST['jesterstats'];
    }


    if ($_POST['gamemode'] == 'campaign') {
        $campaign = 1;
    } else if ($_POST['gamemode'] == 'survival') {
        $survival = 1;
    } else if ($_POST['gamemode'] == 'challenge') {
        $challenge = 1;
    } else if ($_POST['gamemode'] == 'purestrategy') {
        $purestrategy = 1;
    } else if ($_POST['gamemode'] == 'mixmode') {
        $mixmode = 1;
    }

    //Saving Data -->

    $build = new Build();
    if (!empty($_POST['buildid'])) {
        $oDBH = Database::getInstance();
        $build->setID($_POST['buildid']);
        if (!$build->load()) {
            http_response_code(404);
            exit("The Build you try to edit doesn't exists!");
        }
        if ($build->getData('fk_user') != $steamprofile['steamid'] && $steamprofile['steamid'] != "76561198004171907") {
            http_response_code(404);
            exit('You are not the owner of the build!');
        }
        if ($build->getData('deleted')) {
            http_response_code(404);
            exit('The Build you try to edit is deleted!');
        }
        $buildID = $build->getID();
        Placeds::deletePlacedsForBuild($_POST['buildid'], $oDBH);
        BuildWaves::deleteBuildwavesForBuild($_POST['buildid'], $oDBH);
    }
    $build->setData('author', $_POST['author']);
    $build->setData('name', $_POST['buildname']);
    $build->setData('map', $_POST['mapid']);
    //$build->setData('isdda', $_POST['isdda'] === "true" ? 1 : 0);
    $build->setData('difficulty', $_POST['difficulty']);
    $build->setData('description', $_POST['builddescription']);
    //if ($steamprofile['steamid'] != "76561198004171907") {
        $build->setData('fk_user', $steamprofile['steamid']);
    //}
    $build->setData('fk_buildstatus', $_POST['buildstatus']);
    $build->setData('campaign', $campaign);
    $build->setData('survival', $survival);
    $build->setData('purestrategy', $purestrategy);
    $build->setData('hardcore', $_POST['hardcore']);
    $build->setData('challenge', $challenge);
    $build->setData('mixmode', $mixmode);
    $build->setData('afkable', $_POST['afkable']);
    $build->setData('squirehp', $squirestats[0]);
    $build->setData('squirerate', $squirestats[1]);
    $build->setData('squiredamage', $squirestats[2]);
    $build->setData('squirerange', $squirestats[3]);
    $build->setData('apprenticehp', $apprenticestats[0]);
    $build->setData('apprenticerate', $apprenticestats[1]);
    $build->setData('apprenticedamage', $apprenticestats[2]);
    $build->setData('apprenticerange', $apprenticestats[3]);
    $build->setData('huntresshp', $huntressstats[0]);
    $build->setData('huntressrate', $huntressstats[1]);
    $build->setData('huntressdamage', $huntressstats[2]);
    $build->setData('huntressrange', $huntressstats[3]);
    $build->setData('monkhp', $monkstats[0]);
    $build->setData('monkrate', $monkstats[1]);
    $build->setData('monkdamage', $monkstats[2]);
    $build->setData('monkrange', $monkstats[3]);
    $build->setData('evhp', $evstats[0]);
    $build->setData('evrate', $evstats[1]);
    $build->setData('evdamage', $evstats[2]);
    $build->setData('evrange', $evstats[3]);
    $build->setData('summonerhp', $summonerstats[0]);
    $build->setData('summonerrate', $summonerstats[1]);
    $build->setData('summonerdamage', $summonerstats[2]);
    $build->setData('summonerrange', $summonerstats[3]);
    $build->setData('jesterhp', $jesterstats[0]);
    $build->setData('jesterrate', $jesterstats[1]);
    $build->setData('jesterdamage', $jesterstats[2]);
    $build->setData('jesterrange', $jesterstats[3]);

    if (isset($_POST['timeperrun'])) {
        $build->setData('timeperrun', $_POST['timeperrun']);
    }
    if (isset($_POST['expperrun'])) {
        $build->setData('expperrun', $_POST['expperrun']);
    }
    if (!isset($buildID)) {
        $buildID = $build->save();
        if (!$buildID) {
            exit('A Database error occured while trying to save your build.');
        }
    } else {
        if (!$build->save()) {
            exit('A Database error occured while trying to save your build.');
        }
    }

    if (empty($_POST['buildid'])) {
        if (!$buildID) {
            exit('You did not send a "buildid" and you did not have created a new build.');
        }
    }

    function savePlaced($placed, $buildID, $buildwave) {
        $placedtower = new Placed();
        $placedtower->setData('fk_build', $buildID);
        $placedtower->setData('fk_tower', $placed['defenseid']);
        $placedtower->setData('x', intval($placed['x']));
        $placedtower->setData('y', intval($placed['y']));
        $placedtower->setData('rotation', $placed['rotation']);
        $placedtower->setData('fk_buildwave', $buildwave);
        $placedtower->save();
    }

    foreach ($_POST['placedtower'] as $placed) {
        if ($placed['wave'] === '0') {
            savePlaced($placed, $buildID, 0);
        }
    }

    if (isset($_POST['customwaves'])) {
        foreach ($_POST['customwaves'] as $customwave) {
            if (!isset($customwave['id']) || empty($customwave['id'])){
                continue;
            }
            $wave = new BuildWave();
            if ($customwave['name'] == '') {
                $customwave['name'] = 'Custom Wave';
            }
            $wave->setData('name', $customwave['name']);
            $wave->setData('fk_build', $buildID);
            $oldWaveID = $customwave['id'];
            $newWaveID = $wave->save();

            foreach ($_POST['placedtower'] as $placed) {
                if ($placed['wave'] == $oldWaveID) {
                    savePlaced($placed, $buildID, $newWaveID);
                }
            }
        }
    }


    if (empty($_POST['buildid'])) {
        if (isset($_POST['image']) && !empty($_POST['image'])) {
            saveScreenshot($_POST['image'], $buildID);
        }
        exit($buildID);
    } else {
        if (isset($_POST['image']) && !empty($_POST['image'])) {
            saveScreenshot($_POST['image'], $_POST['buildid']);
        }
        exit($_POST['buildid']);
    }
}

function saveScreenshot($base64, $buildID) {
    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
    $img = imagecreatefromstring($data);

    $width = imagesx($img);
    $height = imagesy($img);
    $top = 0;
    $bottom = 0;
    $left = 0;
    $right = 0;

    $bgcolor = imagecolorat( $img, $top, $left ); // This works with any color, including transparent backgrounds
    for(; $top < $height; ++$top) {
        for($x = 0; $x < $width; ++$x) {
            if(imagecolorat($img, $x, $top) != $bgcolor) {
                break 2; //out of the 'top' loop
            }
        }
    }
    for(; $bottom < $height; ++$bottom) {
        for($x = 0; $x < $width; ++$x) {
            if(imagecolorat($img, $x, $height - $bottom-1) != $bgcolor) {
                break 2; //out of the 'bottom' loop
            }
        }
    }
    for(; $left < $width; ++$left) {
        for($y = 0; $y < $height; ++$y) {
            if(imagecolorat($img, $left, $y) != $bgcolor) {
                break 2; //out of the 'left' loop
            }
        }
    }
    for(; $right < $width; ++$right) {
        for($y = 0; $y < $height; ++$y) {
            if(imagecolorat($img, $width - $right-1, $y) != $bgcolor) {
                break 2; //out of the 'right' loop
            }
        }
    }
    $new_width = $width-($left+$right);
    $new_height = $height-($top+$bottom);
    $newimg = imagecreatetruecolor($new_width, $new_height);
    imagealphablending($newimg, false);
    imagesavealpha($newimg, true);

    imagecopy($newimg, $img, 0, 0, $left, $top, imagesx($newimg), imagesy($newimg));

    $lastimg = imagecreatetruecolor(200,200);
    imagealphablending($lastimg, false);
    imagesavealpha($lastimg, true);

    imagecopyresampled($lastimg, $newimg, 0, 0, 0, 0, 200, 200, $new_width, $new_height);

    $sucess = imagepng($lastimg, DOCROOT . '/images/thumbnails/' . $buildID . '.png');
}
