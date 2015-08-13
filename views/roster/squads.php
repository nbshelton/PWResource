<article>
    <div id="dialog-form-rename-squad" title="Rename squad">
        <form>
            <fieldset>
                <input type="text" name="squadName" id="squadName" maxlength="20">
                <input type="submit" tabindex="-1" style="display:none;">
            </fieldset>
        </form>
    </div>
    <div id="dialog-confirm-delete-player" title="Delete player">
        <p>
            <span class="ui-icon ui-icon-alert"></span>
            Are you sure you want to remove this player from the roster?
        </p>
    </div>
    <div id="dialog-form-add-player" title="Add player">
        <form>
            <fieldset>
                <input type="text" name="newPlayerName" id="newPlayerName" maxlength="12">
                <select name="newPlayerClass" id="newPlayerClass">
                    <?php foreach(array_keys($model->members) as $class) { ?>
                        <option value="<?=$class?>"><?=$class?></option>
                    <? } ?>
                </select>
                <input type="submit" tabindex="-1" style="display:none;">
            </fieldset>
        </form>
    </div>

    <div id="class_list">
        <?php foreach($model->members as $class => $members) { ?>
            <h3><?=$class?> (<span id="class-count-<?=$class?>"><?=sizeof($members)?></span>)</h3>
            <div>
                <ul class="player_list" id="class-list-<?=$class?>">
                    <?php foreach ($members as $member) { ?>
                        <li class="ui-state-default class-<?=$class?>" id="player<?=$member["player_id"]?>"><?=$member["player_name"]?></li>
                    <? } ?>
                </ul>
            </div>
        <? } ?>
    </div>
    <div>
        <div class="squad-column">
        </div>

        <div class="squad-column">
        </div>

        <div class="squad-column">
        </div>
    </div>

    <button id="newSquadButton">Add Squad</button>
    <button id="newPlayerButton">Add Player</button>
</article>