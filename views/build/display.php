<article>
    <section style="float:left;clear:left;">
        <select id="weapon_type_select" onchange="updateSubtypes()">
            <?php
            $types = \Controller::getController("API", "weaponTypes", array())->run()->obj;
            foreach($types as $type) {
                echo "<option id='".$type["id"]."'>".$type["name"]."</option>";
            }
            ?>
        </select>
        <select id="weapon_subtype_select" onchange="updateItems()">
            <?php
            $subtypes = \Controller::getController("API", "weaponSubtypes", array("id" => $types[0]["id"]))->run()->obj;
            foreach($subtypes as $subtype) {
                echo "<option id='".$subtype["id"]."'>".$subtype["name"]."</option>";
            }
            ?>
        </select>
        <br /><br />
        <select size="35" id="weapon_item_select" onchange="getItemInfo()">
            <?php
            $items = \Controller::getController("API", "weaponList", array("id" => $subtypes[0]["id"]))->run()->obj;
            foreach($items as $item) {
                echo "<option class='color".$item->color."' id='".$item->id."'>".$item->name."</option>";
            }
            ?>
        </select>
    </section>
    <section id="info" style="width:40%;float:right;clear:right;">
        
    </section>
    <section style="clear:both;">
    </section>
</article>