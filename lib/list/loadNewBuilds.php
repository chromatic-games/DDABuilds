<table class="table table-responsive table-hover">
    <thead>
    <tr>
        <th><a href="#" id="sortName">Build Name</a></th>
        <th><a href="#" id="sortMap">Map</a></th>
        <th><a href="#" id="sortDifficulty">Difficulty</a></th>
        <th><a href="#" id="sortRating">Rating</a></th>
        <th><a href="#" id="sortViews">Views</a></th>
        <th><a href="#" id="sortDate">Date</a></th>
        <th><a href="#" id="sortAuthor">Author</a></th>
        <th class="text-right"><a href="#" id="gridView" class="disabledlink"><i class="fa fa-th" aria-hidden="true"></i></a> <a href="#" id="listView"><i class="fa fa-bars" aria-hidden="true"></i></a></th>
    </tr>
    </thead>
</table>
<?php
$i = 0;
foreach ($builds as $build) {
    if ($i == 0) {
        echo '<div class="row">';
    }

    include 'loadNewBuild.php';

    $i++;
    if ($i == 3) {
        $i = 0;
        echo '</div>';
    }
}
if ($i != 0) {
    echo '</div>';
}

?>