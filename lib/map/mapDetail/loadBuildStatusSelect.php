<div class="form-group">
    <label for="buildstatusselect">Build Status:</label>
    <select class="form-control" id="buildstatusselect">
        <?php
        $buildStatuses = Buildstatuses::getAllStatuses();
        foreach ($buildStatuses as $buildStatus) {
            $buildStatusID = $buildStatus->getID();
            $statusName = $buildStatus->getData('name');
            $selected = '';
            if (isset($build)) {
                if ($buildStatusID == $build->getData('fk_buildstatus')) {
                    $selected = 'selected="selected"';
                }
            }
            echo '<option ' . $selected . ' value="' . $buildStatusID . '">' . $statusName . '</option>';
        }
        ?>
    </select>
</div>