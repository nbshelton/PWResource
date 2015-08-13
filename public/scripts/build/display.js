function updateSubtypes() {
    var type = $("#weapon_type_select option:selected").attr("id");
    $.getJSON("api/weaponSubtypes", {type: type}).done(function(data) {
        var subSelect = $("#weapon_subtype_select");
        subSelect.empty();
        $.each(data, function(i, subtype) {
            subSelect.append("<option id='"+subtype.id+"'>"+subtype.name+"</option>");
        });
        updateItems();
    });
}

function updateItems() {
    var subtype = $("#weapon_subtype_select option:selected").attr("id");
    $.getJSON("api/weaponList", {subtype: subtype}).done(function(data) {
        var itemSelect = $("#weapon_item_select");
        itemSelect.empty();
        $.each(data, function(i, item) {
            itemSelect.append("<option class='color"+item.color+"' id='"+item.id+"'>"+item.name+"</option>");
        });
        itemSelect.scrollTop(0);
    });
}

function getItemInfo() {
    var item = $("#weapon_item_select option:selected").attr("id");
    $.get("build/weaponInfo", {id: item}, function(data) {
        $("#info").html(data);
    });
}