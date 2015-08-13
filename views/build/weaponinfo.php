<?php $this->renderScript("database/item.js"); ?>

<script type="text/javascript">
    $(".addon").on("change", ".addon_slider", function() {
        $(this).parent().siblings(".addon_text").children(".addon_value").html($(this).val());
    });
    $(".addon").on("click", ".addon_inc", function() {
        var slider = $(this).siblings(".addon_slider");
        slider.val(function(i, v) {
            var ret = v + slider.attr("step");
            return Math.max(slider.attr("min"), Math.min(slider.attr("max"), parseFloat(v)+parseFloat(slider.attr("step"))));
        });
        slider.trigger("change");
    });
    $(".addon").on("click", ".addon_dec", function() {
        var slider = $(this).siblings(".addon_slider");
        slider.val(function(i, v) {
            return Math.max(slider.attr("min"), Math.min(slider.attr("max"), parseFloat(v)-parseFloat(slider.attr("step"))));
        });
        slider.trigger("change");
    });
    
    //@ sourceURL=/build/weaponInfo
</script>

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
        <p><?=$model->subType?></p>
        <p>Lv. <?=$model->grade?></p>
        <p>Attack Rate (Atks/sec) <?=number_format($model->attack_rate, 2)?></p>
        <p>Range <?=number_format($model->attack_range, 2)?></p>
        <?=$model->ranged ? "<p> Min. Effective Range ".number_format($model->attack_range_min, 2)."</p>" : ""?>
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
            <p>Durability: <?=$model->durability?>/<?=$model->durability?></p>
        <?=($model->min_level ? "<p>Requisite Lv. ".$model->min_level."</p>" : "")?>
        <?=($model->min_str ? "<p>Requisite Strength ".$model->min_str."</p>" : "")?>
        <?=($model->min_dex ? "<p>Requisite Dexterity ".$model->min_dex."</p>" : "")?>
        <?=($model->min_mag ? "<p>Requisite Magic ".$model->min_mag."</p>" : "")?>
        <?=($model->min_vit ? "<p>Requisite Vitality ".$model->min_vit."</p>" : "")?>
        <?=($model->min_rep ? "<p>Requisite Reputation: ".$model->min_rep."</p>" : "")?>

        <?php
        if ($model->addons_fixed) {
            foreach($model->addons_normal as $addon_data) {
                $addon = $addon_data[0];
                if ($addon->var_count == 0) {
                    echo("<p class='addon'><span class='addon_text'>".$addon->text."</span></p>");
                } else {
                    $addon->text = str_replace("%.2f", "<span class='addon_value'>%.2f</span>",
                                       str_replace("%d", "<span class='addon_value'>%d</span>", $addon->text));
                    printf("<p class='addon'><span class='addon_text'>".$addon->text."</span>", $addon->params[0]);
                    if ($addon->var_count > 1) {
                        if (strpos($addon->text, "%d") !== false) {
                            //Integer variable, step by 1
                            printf("<span class='addon_editor'><button class='addon_dec'>-</button>"
                                    . "<input class='addon_slider' type='range' min='%d' max='%d' value='%d' step='1'>"
                                    . "<button class='addon_inc'>+</button></span>",
                                    $addon->params[0], $addon->params[1], $addon->params[0]);
                        } else {
                            //Float variable, need to determine step
                            switch($addon->group_id) {
                                case 9:
                                    $step = 0.05;
                                    break;
                                
                                case 10:
                                case 81:
                                    $step = 1;
                                    break;
                                    
                                case 48:
                                    $step = 0.1;
                                    break;
                                
                                default:
                                    $step = 0.01;
                            }
                            printf("<span class='addon_editor'><span class='addon_dec'>-</span>"
                                    . "<input class='addon_slider' type='range' min='%.2f' max='%.2f' value='%.2f' step='%.2f'>"
                                    . "<span class='addon_inc'>+</span></span>",
                                    $addon->params[0], $addon->params[1], $addon->params[0], $step);
                        }
                    }
                    echo("</p>");
                }
            }
        }
        ?>
        <p>Price: <?=number_format($model->sell_price)?> / <?=number_format($model->buy_price)?></p>
    </section>
<?php /*
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
            echo("<p>".$addon->text);
            if ($addon->text == "::Unknown Parameter::") {
                echo(" (".$addon->id);
                foreach($addon->params as $param) {
                    echo(", ".$param);
                }
                echo(")");
            }
            if(!$model->addons_fixed)
                printf(" - %.2f%%", $probability*100);
            echo("</p>");
        }
        ?>
    </section>*/?>
</article>