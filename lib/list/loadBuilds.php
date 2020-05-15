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
        <th class="text-right"><a href="#" id="gridView"><i class="fa fa-th" aria-hidden="true"></i></a></th>
        <th class="text-right"><a href="#" id="listView" class="disabledlink"><i class="fa fa-bars" aria-hidden="true"></i></a></th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($builds as $build) {
        include 'loadBuild.php';
    }
    ?>
    </tbody>
</table>