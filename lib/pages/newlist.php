<?php
require_once 'config.php';
include "header.php";
include 'list/listGetHandler.php';
?>

<body>
<?php
include "navbar.php";
?>
<div class="container">
    <div class="row">
        <div class="col-md-1">

        </div>
        <div class="col-md-10 text-center">
            <?php
            include 'list/loadListFilter.php';
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <?php
            include 'list/loadNewBuilds.php';
            ?>

            <ul class="pagination">
                <?php
                $curSite = '';
                for ($i = 1; $i <= $pages; $i++) {
                    if ($site == $i) {
                        $curSite = 'active';
                    }
                    echo '<li class="' . $curSite . '"><a href="?pageNo=' . $i . Utility::getGetParameter() . '">' . $i . '</a></li>';
                    $curSite = '';
                }
                ?>
            </ul>
        </div>
    </div>
</div>
</body>
<script>
    $(document).ready(function () {

        var myUrl = window.location.href;
        $('#search').on('click', function (event) {
            event.preventDefault();
            myUrl = '//' + location.host + location.pathname;
            var bname = $('#bname').val();
            var author = $('#author').val();
            var difficulty = $('#difficultyselect').val();
            var map = $('#mapselect').val();

            if (bname) {
                addQSParm('bname', bname);
            }
            if (author) {
                addQSParm('author', author);
            }
            if (difficulty != 0) {
                addQSParm('difficulty', difficulty);
            }
            if (map != 0) {
                addQSParm('map', map);
            }
            window.location.replace(myUrl);
        });

        $('#sortName').on('click', function (event) {
            event.preventDefault();
            addQSParm('by', 'name');
            if (GET.by == 'name' && GET.order == 'ASC') {
                addQSParm('order', 'DESC');
            } else {
                addQSParm('order', 'ASC');
            }

            window.location.replace(myUrl);
        });
        $('#sortMap').on('click', function (event) {
            event.preventDefault();
            addQSParm('by', 'map');
            if (GET.by == 'map' && GET.order == 'ASC') {
                addQSParm('order', 'DESC');
            } else {
                addQSParm('order', 'ASC');
            }
            window.location.replace(myUrl);
        });
        $('#sortDifficulty').on('click', function (event) {
            event.preventDefault();
            addQSParm('by', 'difficulty');
            if (GET.by == 'difficulty' && GET.order == 'ASC') {
                addQSParm('order', 'DESC');
            } else {
                addQSParm('order', 'ASC');
            }
            window.location.replace(myUrl);
        });
        $('#sortRating').on('click', function (event) {
            event.preventDefault();
            addQSParm('by', 'votes');
            if (GET.by == 'votes' && GET.order == 'DESC') {
                addQSParm('order', 'ASC');
            } else {
                addQSParm('order', 'DESC');
            }
            window.location.replace(myUrl);
        });
        $('#sortViews').on('click', function (event) {
            event.preventDefault();
            addQSParm('by', 'views');
            if (GET.by == 'views' && GET.order == 'DESC') {
                addQSParm('order', 'ASC');
            } else {
                addQSParm('order', 'DESC');
            }
            window.location.replace(myUrl);
        });
        $('#sortDate').on('click', function (event) {
            event.preventDefault();
            addQSParm('by', 'date');
            if (GET.by == 'date' && GET.order == 'DESC') {
                addQSParm('order', 'ASC');
            } else {
                addQSParm('order', 'DESC');
            }
            window.location.replace(myUrl);
        });
        $('#sortAuthor').on('click', function (event) {
            event.preventDefault();
            addQSParm('by', 'author');
            if (GET.by == 'author' && GET.order == 'ASC') {
                addQSParm('order', 'DESC');
            } else {
                addQSParm('order', 'ASC');
            }
            window.location.replace(myUrl);
        });
        var GET = new /**
         * @return {string}
         */
            function () {
            try {
                return JSON.parse('{"' + window.location.href.split('?')[1].split('&').join('", "').split('=').join('":"') + '"}');
            } catch (e) {
                return '';
            }
        };

        fillFilter();

        function fillFilter() {
            var bname = GET.bname;
            var author = GET.author;
            var difficulty = GET.difficulty;
            var map = GET.map;
            if (bname) {
                $('#bname').val(bname);
            }
            if (author) {
                $('#author').val(author);
            }
            if (difficulty) {
                $('#difficultyselect').val(difficulty);
            }
            if (map) {
                $('#mapselect').val(map);
            }
            console.log(map)
        }

        function addQSParm(name, value) {
            var re = new RegExp("([?&]" + name + "=)[^&]+", "");

            function add(sep) {
                myUrl += sep + name + "=" + encodeURIComponent(value);
            }

            function change() {
                myUrl = myUrl.replace(re, "$1" + encodeURIComponent(value));
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
    });
</script>
</html>