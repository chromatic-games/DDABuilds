<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="..">DDA Builder</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php
                if (isset($_SESSION['steamid'])) {
                    echo '
                    <li>
                        <a href="/maps.php">Create</a>
                    </li>
                ';
                }
                ?>
                <li>
                    <a href="/list.php">List</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>

                    <?php
                    if (!isset($_SESSION['steamid'])) {
                        echo '<div class="navbar-brand" style="margin-top:-8px";><a href="'.BASE_URL.'/?login">Login to Create or Vote on Builds:</a> ';
                        loginbutton('rectangle'); //login button
                        echo '</div>';
                    } else {
                        echo '
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' . $steamprofile['personaname'] . '<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="myBuilds.php">My Builds</a>
                                    </li>
                                    <li>
                                        <a href="notifications.php">Notifications</a>
                                    </li>
                                    <li>
                                        <a href="index.php?logout=">Logout</a>
                                    </li>
                                </ul>
                            </li>
                            ';
                    }
                    ?>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
<?php
if (isset($_SESSION['steamid'])) {
    $oDBH = Database::getInstance();
    $newNotifications = count(Notifications::getUnreadNotificationsForUser($steamprofile['steamid'], $oDBH));
    if ($newNotifications) {
        echo '
        <div class="container">
            <div class="row text-middle">
                <div class="col-md-7">
                </div>
                <div class="col-md-5">
                    <div class="alert alert-success">
                        Hello ' . Utility::getSteamName($_SESSION['steamid']) . ' you have: <a href="notifications.php" class="alert-link">' . $newNotifications . ' unread notifications</a>.
                    </div>
                </div>
            </div>
        </div>
        ';
    }
}

?>