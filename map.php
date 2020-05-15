<?php
require_once 'config.php';
include 'map/mapPostHandler.php';
include "header.php";

$oDBH = Database::getInstance();
$create = false;
$isCreator = false;

if (isset($_GET['name'])) {
//TODO: Name lookup functionality
    $nope = 1;
} else {
    if (!empty($_GET['id']) && !empty($_GET['load'])) {
        http_response_code(404);
        exit('404');
    }

    if (empty($_GET['id']) && empty($_GET['load'])) {
        http_response_code(404);
        exit('404');
    }
}

if (isset($_GET['id']) && isset($steamprofile['steamid'])) { //Are they the creator?
    $iMapID = $_GET['id'];
    $create = true;
} else if (isset($_GET['load'])) { //If not creator, new build.
    $build = new Build();
    $build->setID($_GET['load']);
    if (!$build->load()) {
        http_response_code(404);
        exit('404');
    }
    if ($build->getData('deleted') == 1) {
        http_response_code(404);
        exit('The Build you are trying to access got deleted. <br> If the deletion was made by mistake please contact the sites administrator!');
    }
    $comments = Comments::getAllCommentsForBuild($build->getID(), $oDBH);
    $buildStatus = $build->getData('fk_buildstatus');
    $iMapID = $build->getData('map');
    $creator = $build->getData('fk_user');
    if (isset($steamprofile) && isset($steamprofile['steamid']) && $steamprofile['steamid'] == $creator) {
        $isCreator = true;
    } else {
        $build->increaseViewCount();
    }
    if ($buildStatus == 3 && !$isCreator) {
        exit('Sorry, this build is private');
    }
} else {
    exit('Please Login before trying to create a build!');
}

if (!is_numeric($iMapID) || $iMapID < 0 || $iMapID != round($iMapID, 0)) {
    http_response_code(404);
    exit('404');
}
$squireStats = [0, 0, 0, 0];
$apprenticeStats = [0, 0, 0, 0];
$huntressStats = [0, 0, 0, 0];
$monkStats = [0, 0, 0, 0];
if (!HIDETHIS) {
    $evStats = [0, 0, 0, 0];
    $summonerStats = [0, 0, 0, 0];
    $jesterStats = [0, 0, 0, 0];
}

if (isset($_GET['load'])) {

    $squireStats = [
        $build->getData('squirehp'),
        $build->getData('squirerate'),
        $build->getData('squiredamage'),
        $build->getData('squirerange'),
    ];
    $apprenticeStats = [
        $build->getData('apprenticehp'),
        $build->getData('apprenticerate'),
        $build->getData('apprenticedamage'),
        $build->getData('apprenticerange'),
    ];
    $huntressStats = [
        $build->getData('huntresshp'),
        $build->getData('huntressrate'),
        $build->getData('huntressdamage'),
        $build->getData('huntressrange'),
    ];
    $monkStats = [
        $build->getData('monkhp'),
        $build->getData('monkrate'),
        $build->getData('monkdamage'),
        $build->getData('monkrange'),
    ];
    if (!HIDETHIS) {
        $evStats = [
            $build->getData('evhp'),
            $build->getData('evrate'),
            $build->getData('evdamage'),
            $build->getData('evrange'),
        ];
        $summonerStats = [
            $build->getData('summonerhp'),
            $build->getData('summonerrate'),
            $build->getData('summonerdamage'),
            $build->getData('summonerrange'),
        ];
        $jesterStats = [
            $build->getData('jesterhp'),
            $build->getData('jesterrate'),
            $build->getData('jesterdamage'),
            $build->getData('jesterrange'),
        ];
    }
}
$aMap = new Map();
$aMap->setID($iMapID);
$aMap->load();
$mapName = $aMap->getData('name');
//$mapScale = $aMap->getData('scale');

if (isset($steamprofile['steamid']) && $steamprofile['steamid'] == "76561198051185047" && !$create) {
    $isCreator = true;
}

if (isset($_GET['viewermode']) && !$create) {
    $isCreator = false;
}

?>
<body>

<?php
include "navbar.php";
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-md-3 text-center">
            <h3>Map:
                <b><?php
                    $tmp = $aMap->getData('name');
                    echo $tmp;
                    ?>
                </b>
            </h3>
        </div>
        <div class="col-md-3 text-center">
            <?php
            $value = '';
            if ($create || $isCreator) {
                if (!empty($_GET['load'])) {
                    $value = 'value="' . $build->getData('name') . '"';
                }
                echo '<label>Build Name:</label>';
                echo '<input type="text" placeholder="Build Name" class="form-control" id="buildname" maxlength="128"' . $value . '>';
            } else {
                echo '<h2><b><u>' . htmlspecialchars($build->getData('name')) . '</u></b></h2>';
            }
            ?>
        </div>
        <div class="col-md-3 text-center">
            <?php
            if ($create || $isCreator) {
                if (!empty($_GET['load'])) {
                    $value = ' value="' . $build->getData('author') . '"';
                } else {
                    $value = ' value="' . $steamprofile['personaname'] . '"';
                }
                echo '<label>Author:</label>';
                echo '<input type="text" placeholder="Author" class="form-control" id="author" maxlength="20"' . $value . '>';
            } else {
                echo '<h3>Author: <b><a href="http://steamcommunity.com/profiles/' . $build->getData('fk_user') . '">' . htmlspecialchars($build->getData('author')) . '</a></b></h3>';
            }
            ?>
        </div>
        <div class="col-md-3 text-center">
            <h3>DU: <b><span id="curDU">0</span>/<span id="maxDU"><?php echo $aMap->getData('units') ?></span></b><?php if(!HIDETHIS) {?> MU:
            <b><span id="curMU">0</span>/<span id="maxMU"><?php echo $aMap->getData('units') ?></span></b><?php } ?></h3>
        </div>
    </div>

    <div class="row">
        <ul class="nav nav-tabs">
            <li class="mainbuild <?php if (!isset($_GET['comments'])) {echo 'active';} ?>" wave="0"><a data-toggle="tab" href="#0">Build</a></li>
            <?php
            if (isset($build)) {
                $buildwaves = BuildWaves::getBuildwavesForBuild($build->GetID(), $oDBH);
                foreach ($buildwaves as $buildwave) {
                    $wavename = htmlspecialchars($buildwave->getData("name"));
                    if ($create || $isCreator) {
                        $wavename = '<input type="text" placeholder="Custom wave name" class="form-control" value="' . htmlspecialchars($buildwave->getData("name")) . '">';
                    }
                    echo '<li class="customwave ' . $buildwave->getID() . '" wave="' . $buildwave->getID() . '"><a data-toggle="tab" href="#' . $buildwave->getID() . '">' . $wavename . '</a></li>';
                }
            }
            if ($create || $isCreator) {
                echo '<li id="newwave"><a href="#">+</a></li>';
            } else {
                echo '<li><a>|</a></li>';
            }

            ?>

            <li class="<?php if (isset($_GET['comments'])) {echo 'active';} ?>">
                <a data-toggle="tab" href="#comments">Comments (<span><?php if (isset($comments)) {echo count($comments);} ?></span>)</a>
            </li>


        </ul>

        <div class="tab-content">
            <div id="0" class="mainbuild tab-pane fade in <?php if (!isset($_GET['comments'])) {echo 'active';} ?>">
                <?php
                $buildwave = 0;
                include 'map/tab/buildTab.php';
                ?>
            </div>
            <?php
            if (isset($build)) {
                foreach ($buildwaves as $buildwave) {
                    echo '<div id="' . $buildwave->getID() . '" class="tab-pane fade in ' . $buildwave->getID() . '">';
                    $buildwave = $buildwave->getID();
                    include 'map/tab/buildTab.php';
                    echo '</div>';
                }
            }
            ?>

            <div id="comments" class="tab-pane fade in
            <?php if (isset($_GET['comments'])) {
                echo 'active';
            } ?>">
                <div class="container">
                    <?php if (isset($comments)) {
                        include 'map/tab/commentsTab.php';
                    } ?>
                </div>
            </div>
        </div>

    </div>

</div>
</body>
<script>
    $(document).ready(function () {
        var stats = [];
        stats["squire"] = [<?php echo $squireStats[0] . ',' . $squireStats[1] . ',' . $squireStats[2] . ',' . $squireStats[3]?>];
        stats["apprentice"] = [<?php echo $apprenticeStats[0] . ',' . $apprenticeStats[1] . ',' . $apprenticeStats[2] . ',' . $apprenticeStats[3]?>];
        stats["huntress"] = [<?php echo $huntressStats[0] . ',' . $huntressStats[1] . ',' . $huntressStats[2] . ',' . $huntressStats[3]?>];
        stats["monk"] = [<?php echo $monkStats[0] . ',' . $monkStats[1] . ',' . $monkStats[2] . ',' . $monkStats[3]?>];
        <?php /* !HIDETHIS
            stats["ev"] = [<?php echo $evStats[0] . ',' . $evStats[1] . ',' . $evStats[2] . ',' . $evStats[3]?>];
            stats["summoner"] = [<?php echo $summonerStats[0] . ',' . $summonerStats[1] . ',' . $summonerStats[2] . ',' . $summonerStats[3]?>];
            stats["jester"] = [<?php echo $jesterStats[0] . ',' . $jesterStats[1] . ',' . $jesterStats[2] . ',' . $jesterStats[3]?>]; */
        ?>
        var classesUsed = [];
        classesUsed["squire"] = false;
        classesUsed["apprentice"] = false;
        classesUsed["huntress"] = false;
        classesUsed["monk"] = false;
        <?php /* !HIDETHIS
            classesUsed["ev"] = false;
            classesUsed["summoner"] = false;
            classesUsed["jester"] = false; */
        ?>
        classesUsed["world"] = false;
        classesUsed["arrow"] = false;
        classesUsed["hints"] = false;

        var towerIDS = [];
        towerIDS["squire"] = [1, 5];
        towerIDS["apprentice"] = [6, 10];
        towerIDS["huntress"] = [11, 15];
        towerIDS["monk"] = [16, 20];
        <?php /* !HIDETHIS
            towerIDS["ev"] = [54, 71];
            towerIDS["summoner"] = [26, 31];
            towerIDS["jester"] = [32, 36]; */
        ?>
        towerIDS["world"] = [200, 210];
        towerIDS["hints"] = [211, 221];
        towerIDS["arrow"] = [222, 240];

        var newBuildingWave = 0;
        var mapname = "<?php echo $iMapID; ?>";
        var isUser = true;
        <?php
        if ($isCreator || $create) {
            echo 'isUser = false;';
        }
        ?>
        changeBoxes($('input[name=gamemode]:checked').attr('id'));
        init();
        var myUrl = window.location.href;

        update(0);
        updateBuildersUsed();


        $('#viewer').on("click", function (event) {
            event.preventDefault();
            if (window.confirm('All unsaved changes will be Lost.\n\nStill Continue to Viewer Mode?'))
            {
                myUrl += '&viewermode';
                window.location.replace(myUrl);
            }
        });

        function updateBuildersUsed() {
            $(".tower-container.placed").each(function (i, obj) {
                for (var key in towerIDS) {
                    if ($(this).attr("defenseid") >= towerIDS[key][0] && $(this).attr("defenseid") <= towerIDS[key][1]) {
                        classesUsed[key] = true;
                    }
                }
            });
            if (isUser) {
                for (var key in classesUsed) {
                    if (!classesUsed[key]) {
                        $('.disableckbx[value="' + key +'"]').parent().remove();
                    }
                }
            }
        }

        $('#requiredstatshpinput').on("change" , function (event) {
            var selectedClass = $('#requiredstatsselect :selected').val();
            stats[selectedClass][0] = $('#requiredstatshpinput').val();
        });
        $('#requiredstatsrateinput').on("change" , function (event) {
            var selectedClass = $('#requiredstatsselect :selected').val();
            stats[selectedClass][1] = $('#requiredstatsrateinput').val();
        });
        $('#requiredstatsdamageinput').on("change" , function (event) {
            var selectedClass = $('#requiredstatsselect :selected').val();
            stats[selectedClass][2] = $('#requiredstatsdamageinput').val();
        });
        $('#requiredstatsrangeinput').on("change" , function (event) {
            var selectedClass = $('#requiredstatsselect :selected').val();
            stats[selectedClass][3] = $('#requiredstatsrangeinput').val();
        });

        $('#requiredstatsselect').on("change", function (event) {
            var selectedClass = $('#requiredstatsselect :selected').val();
            $('#requiredstatshpinput').val(stats[selectedClass][0]);
            $('#requiredstatsrateinput').val(stats[selectedClass][1]);
            $('#requiredstatsdamageinput').val(stats[selectedClass][2]);
            $('#requiredstatsrangeinput').val(stats[selectedClass][3]);
        });

        $('#upvote').on("click", function (event) {
            event.preventDefault();
            var parameters = {
                'buildid': "<?php if (isset($build)) {
                    echo $build->getID();
                } ?>",
                'rating': "1"
            };
            $.post(
                "rating.php", parameters,
                function (data) {
                    $('#upvote').prop('disabled', true);
                    $('#downvote').prop('disabled', false);
                }
            );
        });

        $('#downvote').on("click", function (event) {
            event.preventDefault();
            var parameters = {
                'buildid': "<?php if (isset($build)) {
                    echo $build->getID();
                } ?>",
                'rating': "-1"
            };
            $.post(
                "rating.php", parameters,
                function (data) {
                    $('#downvote').prop('disabled', true);
                    $('#upvote').prop('disabled', false);
                }
            );
        });



        $('#newwave').on("click", function (event) {
            event.preventDefault();
            newBuildingWave++;
            var customWave = 'customwave' + newBuildingWave;

            var parameters = {
                'creator': "true",
                'customwave': customWave,
                'map': mapname
            };
            $.get(
                "map/tab/buildTab.php", parameters,
                function (data) {
                    addNavTab(customWave);
                    addNavContent(data, customWave);
                    init();
                }, 'html'
            );
        });

        function addNavTab(customwave) {
            var navTab = '<li class="customwave ' + customwave + '" wave="' + customwave + '"><a data-toggle="tab" href="#'+ customwave + '"><input type="text" placeholder="Custom wave name" class="form-control" value="' + customwave + '"></a></li>';
            $(navTab).insertBefore('#newwave');
        }

        function addNavContent(content, customwave) {
            var navContent = '<div id="' + customwave + '" class="tab-pane fade in ' + customwave + '">' + content + '</div>';
            $(navContent).insertBefore('#comments');
            update();
        }
        //TODO: Improve whats below
        $('.disableckbx').on("change", function (e) {
            var state = $(this).is(":checked");
            var towerType = $(this).attr("value");
            var allTower = $('.active').eq(1).children('.col-lg-9').children('.canvas').children('.tower-container.placed');
            var towerids;
            var squire = [1, 5];
            var apprentice = [6, 10];
            var huntress = [11, 15];
            var monk = [16, 20];
            <?php /* !HIDETHIS
                var seriesev = [54, 71];
                var summoner = [26, 31];
                var jester = [32, 36]; */?>
            var world = [200, 210];
            var hint = [211, 221];
            var arrow = [222, 240];

            if (towerType == "squire") {
                towerids = squire;
            } else if (towerType == "apprentice") {
                towerids = apprentice;
            } else if (towerType == "huntress") {
                towerids = huntress;
            } else if (towerType == "monk") {
                towerids = monk;
            <?php /* !HIDETHIS
            } else if (towerType == "series-ev" && !HIDETHIS) {
                towerids = seriesev;
            } else if (towerType == "summoner" && !HIDETHIS) {
                towerids = summoner;
            } else if (towerType == "jester" && !HIDETHIS) {
                towerids = jester; */?>
            } else if (towerType == "world") {
                towerids = world;
            } else if (towerType == "hints") {
                towerids = hint;
            } else if (towerType == "arrow") {
                towerids = arrow;
            }
            var selectedTower = allTower.filter(function () {
                return $(this).attr("defenseid") >= towerids[0] && $(this).attr("defenseid") <= towerids[1];
            });
            selectedTower.each(function (i, obj) {
                if (state == true) {
                    $(obj).css("display", "none");
                } else if (state == false) {
                    $(obj).css("display", "initial");
                }
            });
        });

        function update(wave = false) {
            var du = 0;
            var mu = 0;
            var mana = 0;
            var upgradeMana = 0;

            var currentPlaced = $('.active').eq(1).children('.col-lg-9').children('.canvas').children('.tower-container.placed');

            if (wave) {
                currentPlaced = $('#' + wave).children('.col-lg-9').children('.canvas').children('.tower-container.placed');
            }

            currentPlaced.each(function (i, obj) {
                if (obj.getAttribute("mu") == 1) {
                    mu += Number(obj.getAttribute("unitcost"));
                } else {
                    du += Number(obj.getAttribute("unitcost"));
                }
                mana += Number(obj.getAttribute("manacost"));
                if (obj.getAttribute("defenseid") >= 37 && obj.getAttribute("defenseid") <= 53) {

                } else {
                    upgradeMana++;
                }
            });
            upgradeMana *= 2620;
            $("#curDU").text(du);
            $("#curMU").text(mu);
            $(".curMana").text(mana);
            $(".upgradeMana").text(upgradeMana);

            if (parseInt($("#curDU").text()) > parseInt($("#maxDU").text())) {
                $("#curDU").css('color', "red");
            } else {
                $("#curDU").css('color', "black");
            }

            if (parseInt($("#curMU").text()) > parseInt($("#maxMU").text())) {
                $("#curMU").css('color', "red");
            } else {
                $("#curMU").css('color', "black");
            }

            if ($('#requiredstatsselect :selected').val()) {
                updateBuildersUsed();
            }

            $(".tower-container.placed").draggable({
                containment: "parent",
                start: function (event, ui) {
                    console.log("Dragging");
                    var left = parseInt($(this).css('left'), 10);
                    left = isNaN(left) ? 0 : left;
                    var top = parseInt($(this).css('top'), 10);
                    top = isNaN(top) ? 0 : top;
                    recoupLeft = left - ui.position.left;
                    recoupTop = top - ui.position.top;
                },
                drag: function (event, ui) {
                    ui.position.left += recoupLeft;
                    ui.position.top += recoupTop;
                }
            });
        }

        if ($("#builddescription").length != 0) {
            CKEDITOR.replace('builddescription');
        }

        $("input[name=gamemode]:radio").change(function (e) {
            var gamemode = $(this).attr('id');
            changeBoxes(gamemode);
        });

        function changeBoxes(gamemode) {
            var hardcore = $('#hardcore');

            if (gamemode == "purestrategy") {
                hardcore.prop('checked', false);
                hardcore.prop('disabled', true);
            } else {
                hardcore.prop('disabled', false);
            }
        }

        $(document).on('click', 'li', function (e) {
            update($(this).attr('wave'));
        });

        $(document).on('mousedown', '.menu', function (e) {
            rrotating_defense = $(this).parent();
            offset = rrotating_defense.offset();
            $(document).mousemove(function (e) {
                mouse_x = e.pageX - offset.left - rrotating_defense.width() / 2;
                mouse_y = e.pageY - offset.top - rrotating_defense.height() / 2;
                top_rotate = (Math.sqrt(Math.pow(mouse_x, 2) + Math.pow(mouse_y, 2)) * (-1));
                mouse_cur_angle = Math.atan2(mouse_y, mouse_x);
                rotate_angle = mouse_cur_angle * (180 / Math.PI) + 90;
                rrotating_defense.css('transform', 'rotate(' + rotate_angle + 'deg)');
            });
        });

        $(document).on('mouseover', '.menu', function (e) {
            rotating_defense = $(this).parent();
            rotating_defense.draggable('disable');
        });

        $(document).on('mouseout', '.menu', function (e) {
            rotating_defense = $(this).parent();
            rotating_defense.draggable('enable');
        });

        $(document).on('mouseup', function (e) {
            $(document).unbind("mousemove");
        });

        $(document).on('contextmenu', '.tower-container.placed', function (event) {
            $(this).remove();
            update();
            return false;
        });

        $('#save').on('click', function (event) {
            event.preventDefault();

            var customwaves = [];
            $(".customwave").each(function (i, obj) {
                customwaves[i] = {
                    name: $(obj).children("a").children("input").val(),
                    id: obj.getAttribute("wave")
                };
            });

            var placedtower = [];
            $(".tower-container.placed").each(function (i, obj) {
                placedtower[i] = {
                    defenseid: obj.getAttribute("defenseid"),
                    x: $(obj).css("left"),
                    y: $(obj).css("top"),
                    rotation: getRotationDegrees($(this)),
                    wave: $(obj).attr("wave")
                };
            });

            if ($("#author").val() == "") {
                alert("Please input an Author!");
                return;
            }

            if ($("#buildname").val() == "") {
                alert("Please input an Build Name!");
                return;
            }

            if (placedtower.length == 0) {
                alert("You didn't placed any tower?");
                return;
            }

            var builddescription = CKEDITOR.instances.builddescription.getData();
            var gamemode = $('input[name=gamemode]:checked').attr('id');
            var hardcore = 0;
            var afkable = 0;
            var renderedCanvas = 0;

            if ($('#hardcore').prop('checked')) {
                hardcore = 1;
            }
            if ($('#afkable').prop('checked')) {
                afkable = 1;
            }
            $('#save').prop('disabled', true);
            html2canvas($('.active').eq(1).children('.col-lg-9').children('.canvas'), {
                onrendered: function(canvas) {
                    renderedCanvas = canvas.toDataURL('image/png');

                    var parameters = {
                        'mapid': "<?php echo $iMapID ?>",
                        'builddescription': builddescription,
                        'author': $("#author").val(),
                        'buildname': $("#buildname").val(),
                        'difficulty': $("#difficultyselect").val(),
                        'gamemode': gamemode,
                        'hardcore': hardcore,
                        'afkable': afkable,
                        'customwaves': customwaves,
                        'placedtower': placedtower,
                        'buildstatus': $("#buildstatusselect").val(),
                        'timeperrun': $("#timeperrun").val(),
                        'expperrun': $("#expperrun").val(),
                        'squirestats': stats["squire"],
                        'apprenticestats': stats["apprentice"],
                        'huntressstats': stats["huntress"],
                        'monkstats': stats["monk"],
                        <?php /* !HIDETHIS
                            'evstats': stats["ev"],
                            'summonerstats': stats["summoner"],
                            'jesterstats': stats["jester"],*/
                        ?>
                        'image': renderedCanvas
                        <?php if ($isCreator) {
                        echo ", 'buildid': " . '"' . str_replace(' ', '', $_GET['load']) . '"';
                    }?>};

                    $.post(
                        "map.php", parameters,
                        function (data) {
                            $('#save').prop('disabled', false);
                            myUrl = '//' + location.host + location.pathname + "?load=" + Number(data);
                            window.location.replace(myUrl);
                        }
                    ).fail(function(jqXHR, textStatus, errorThrown){
                        if(jqXHR.status == 404) {
                            alert('Error while saving: ' + jqXHR.responseText);
                            $('#save').prop('disabled', false);
                        }
                    });
                }
            });
        });

        $('#delete').on('click', function (event) {
            event.preventDefault();
            if (window.confirm("Do you really want to Delete your Build?")) {
                var parameters = {
                    'delete': 1,
                    'buildid': "<?php if (isset($build)) {
                        echo $build->getID();
                    }?>"
                };
                $.post(
                    "map.php", parameters,
                    function (data) {
                        myUrl = '//' + location.host + '/list.php';
                        window.location.replace(myUrl);
                    }
                );
            }
        });



        function init() {
            $(".canvas").each(function () {
                var canvas = $(this);
                canvas.droppable({
                    accept: ".defense",
                    drop: function (event, ui) {
                        if ($(ui.helper).hasClass("defense")) {
                            var clone = ui.helper.clone();
                            offset = canvas.offset();
                            var canvasOffsetTop = offset.top;
                            var canvasOffsetLeft = offset.left;
                            var defenseOffset = 15;
                            clone.css("top", ui.offset.top - canvasOffsetTop);
                            clone.css("left", ui.offset.left - canvasOffsetLeft + defenseOffset);
                            clone.removeClass("defense").addClass("placed");
                            clone.find(".pool").each(function (i, e) {
                                $(this).removeClass("pool").addClass("placed");
                            });
                            clone.draggable();
                            clone.appendTo(canvas);
                            update();
                        }
                    }
                })
            });

            $(".defense").draggable({
                helper: 'clone'
            });

            $('.deletewave').on("click", function (event) {
                event.preventDefault();
                if (window.confirm("Do you really want to Delete this Wave?")) {
                    var wave = this.getAttribute('wave');
                    $('.' + wave).removeClass('in');
                    $('.' + wave).remove();
                    $('.mainbuild').addClass('active in');
                }
            });



            if ($('#requiredstatsselect :selected').val()) {
                var selectedClass = $('#requiredstatsselect :selected').val();
                $('#requiredstatshpinput').val(stats[selectedClass][0]);
                $('#requiredstatsrateinput').val(stats[selectedClass][1]);
                $('#requiredstatsdamageinput').val(stats[selectedClass][2]);
                $('#requiredstatsrangeinput').val(stats[selectedClass][3]);
            }
        }

        $('.js-upvote').on("click", function (event) {
            event.preventDefault();
            var commentid = this.getAttribute('commentid');
            var parameters = {
                'commentid': commentid,
                'rating': "1"
            };
            $.post(
                "rating.php", parameters,
                function (data) {
                    $('#upvote' + commentid).addClass('disabledlink');
                    $('#downvote' + commentid).removeClass('disabledlink');
                    updateVotes(commentid, data);
                }
            );
        });

        $('.js-downvote').on("click", function (event) {
            event.preventDefault();
            var commentid = this.getAttribute('commentid');
            var parameters = {
                'commentid': commentid,
                'rating': "-1"
            };
            $.post(
                "rating.php", parameters,
                function (data) {
                    $('#downvote' + commentid).addClass('disabledlink');
                    $('#upvote' + commentid).removeClass('disabledlink');
                    updateVotes(commentid, data);
                }
            );
        });



        $('#sendcomment').on('click', function (event) {
            event.preventDefault();
            $('#sendcomment').prop('disabled', true);
            var commentbox = $('#commentbox').val();
            var parameters = {
                'buildid': "<?php if (isset($build)) {
                    echo $build->getID();
                } ?>",
                'comment': commentbox
            };
            $.post(
                "commenthandler.php", parameters,
                function (data) {
                    addQSParm('comments', 'y#' + data);
                    window.location.replace(myUrl);
                }
            );
        });

        function updateVotes(commentid, data) {
            var upvoteSpan = $('#upvotetext' + commentid);
            var downvoteSpan = $('#downvotetext' + commentid);
            upvoteSpan.text(JSON.parse(data).upvotes);
            downvoteSpan.text(JSON.parse(data).downvotes);
        }

        function getRotationDegrees(obj) {
            obj.css("display", "initial");
            var matrix = obj.css("transform");
            if (matrix !== 'none') {
                var values = matrix.split('(')[1].split(')')[0].split(',');
                var a = values[0];
                var b = values[1];
                var angle = Math.round(Math.atan2(b, a) * (180 / Math.PI));
            } else {
                var angle = 0;
            }
            return (angle < 0) ? angle + 360 : angle;
        }

        function addQSParm(name, value) {
            var re = new RegExp("([?&]" + name + "=)[^&]+", "");

            function add(sep) {
                myUrl += sep + name + "=" + value;
            }

            function change() {
                myUrl = myUrl.replace(re, "$1" + value);
            }

            if (myUrl.indexOf("?") === -1) {
                add("?");
            } else {
                if (re.test(myUrl)) {
                    change();
                } else {
                    add("&");
                }
            }
            }
        }
    );
</script>
</html>
