<?php
$up = '';
$down = '';
$votedOption = Votes::userAlreadyVoted($build->getID(), $steamprofile['steamid'], $oDBH);
if ($votedOption) {
    $vote = new Vote();
    $vote->setID($votedOption);
    $vote->load();
    if ($vote->getData('vote') == 1) {
        $up = 'disabled';
    } else {
        $down = 'disabled';
    }
}
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Vote:
    </div>
    <div class="panel-body">
        <button type="button" class="btn btn-success btn-lg" <?php echo $up; ?> id="upvote">
            <span class="glyphicon glyphicon-thumbs-up"></span> Up
        </button>

    </div>
</div>