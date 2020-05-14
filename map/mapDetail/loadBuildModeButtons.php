<?php
$campaign = 'checked';
$survival = '';
$purestrategy = '';
$challenge = '';
$mixmode = '';
if ($isCreator) {
    if ($build->getData('survival')) {
        $survival = 'checked';
    } else if ($build->getData('challenge')) {
        $challenged = 'checked';
    } else if ($build->getData('purestrategy')) {
        $purestrategy = 'checked';
    } else if ($build->getData('mixmode')) {
        $mixmode = 'checked';
    }
}
echo '<label for="campaign">Game Mode:</label><br>';
echo '<label class="radio-inline"><input type="radio" name="gamemode" id="campaign" ' . $campaign . '>Campaign</label>';
echo '<label class="radio-inline"><input type="radio" name="gamemode" id="survival" ' . $survival . '>Survival</label>';
echo '<label class="radio-inline"><input type="radio" name="gamemode" id="challenge" ' . $challenge . '>Challenge</label>';
echo '<label class="radio-inline"><input type="radio" name="gamemode" id="purestrategy" ' . $purestrategy . '>Pure Strategy</label>';
echo '<label class="radio-inline"><input type="radio" name="gamemode" id="mixmode" ' . $mixmode . '>Mix Mode</label>';
?>