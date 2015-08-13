<?php $this->renderScript("database/item.js"); ?>

<article id="item_info" data-id="<?=$model->id?>" data-refine="<?=$model->refine_amount?>">
    <h1 class="color<?=$model->color?>">
        <?=$model->name?>
        <select name="refine_level" id="refine" onchange="calculate_refine()">
            <option value="0">+0</option>
            <option value="1">+1</option>
            <option value="2">+2</option>
            <option value="3">+3</option>
            <option value="4">+4</option>
            <option value="5">+5</option>
            <option value="6">+6</option>
            <option value="7">+7</option>
            <option value="8">+8</option>
            <option value="9">+9</option>
            <option value="10">+10</option>
            <option value="11">+11</option>
            <option value="12">+12</option>
        </select>
    </h1>
    <section id="item_overview">
        <h2>Item Overview</h2>
        <p><?=$model->subType?><?=($model->ranged ? " (Ranged)" : "")?></p>
        <p>Lv. <?=$model->grade?></p>
        <p>Attack Rate (Atks/sec) <?=number_format($model->attack_rate, 2)?></p>
        <p>Range <?=number_format($model->attack_range, 2)?></p>
        <?=$model->ranged ? "<p> Min. Effective Range ".number_format($model->attack_range_min, 2)."</p>" : ""?>
        
        <br />
        
        <?php if ($model->damage_high) { ?>
            <p>
                Physical Attack <span class='refine_element' data-original='<?=$model->damage_low?>'><?=$model->damage_low?></span>-<span class='refine_element' data-original='<?=$model->damage_high?>'><?=$model->damage_high?></span>
            </p>
        <?php }
        if ($model->magic_damage_high) {?>
            <p>
                Magic Attack <span class='refine_element' data-original='<?=$model->magic_damage_low?>'><?=$model->magic_damage_low?></span>-<span class='refine_element' data-original='<?=$model->magic_damage_high?>'><?=$model->magic_damage_high?></span>
            </p>
        <?php } ?>

        <br />

        <?=($model->min_level ? "<p>Requisite Lv. ".$model->min_level."</p>" : "")?>
        <?=($model->min_str ? "<p>Requisite Strength ".$model->min_str."</p>" : "")?>
        <?=($model->min_dex ? "<p>Requisite Dexterity ".$model->min_dex."</p>" : "")?>
        <?=($model->min_mag ? "<p>Requisite Magic ".$model->min_mag."</p>" : "")?>
        <?=($model->min_vit ? "<p>Requisite Vitality ".$model->min_vit."</p>" : "")?>
        <?=($model->min_rep ? "<p>Requisite Reputation: ".$model->min_rep."</p>" : "")?>

        <br />
        
        <p>Durability: <?=$model->durability?></p>
        <p>Price: <?=number_format($model->sell_price)?> / <?=number_format($model->buy_price)?></p>
        <p>Repair Fee: <?=number_format($model->repair_fee)?></p>
    </section>

    <section id="item_addons">
        <h2>Item Addons</h2>
        <h4>Addon Type: <?=$model->addons_fixed ? "Fixed" : "Random"?></h4>
        <?php
        if (!$model->addons_fixed) {
            for($i=0; $i<=4; $i++) {
                if ($model->addon_probabilities[$i] != 0) {
                    printf("<p>Probability of %d addons: %.2f%%</p>", $i, $model->addon_probabilities[$i]*100);
                }
            }
        } ?>
        <br />
        <?php
        foreach($model->addons_normal as $addon_data) {
            $addon = $addon_data[0];
            $probability = $addon_data[1];
            switch($addon->var_count) {
                case 2:
                    $addon->text = str_replace("%.2f", "%.2f~%.2f", str_replace("%d", "%d~%d", $addon->text));
                    printf("<p>".$addon->text, $addon->params[0], $addon->params[1]);
                    break;
                case 1:
                    printf("<p>".$addon->text, $addon->params[0]);
                    break;
                default:
                    echo("<p>".$addon->text);
            }
            if(!$model->addons_fixed)
                printf(" - %.2f%%", $probability*100);
            echo("</p>");
        }
        ?>
    </section>
</article>