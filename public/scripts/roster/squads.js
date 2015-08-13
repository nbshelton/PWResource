var renameSquadDialog, deletePlayerDialog, newPlayerDialog, ws;
var maxPlayerID = 0;
function connectWS() {
    console.log("Attempting to open connection...");
    ws = new WebSocket("ws://localhost:8080/");
    ws.onmessage = function(e) {
        //TODO: PROCESS INCOMING WEBSOCKET MESSAGES
        console.log("NEW MESSAGE: "+e.data);
    }
    ws.onopen = function(e) {
        console.log("CONNECTION OPENED");
    }
    ws.onclose = function(e) {
        console.log("CONNECTION CLOSED");
        ws.close();
        setTimeout(function() {
            if (ws.readyState !== 1) {
                connectWS();
            }
        }, 5000);
    }
}
function sendWSMessage(message) {
    setTimeout(function() {
        if (ws.readyState === 1) {
            ws.send(message);
        } else {
            console.log("Connection not ready, retrying in 0.5s...");
            sendWSMessage(message);
        }
    }, 500);
}

/**
 * Extracts the character class from a player.
 * @param {JQuery} player   the player to extract from.
 * @return {String?} class   the player's character class, or false if not found.
 */
function extractClassName(player) {
    var classes = player.attr("class").match(/\S+/g);
    for(var i=0; i<classes.length; i++) {
        var c = classes[i];
        if (c.indexOf("class-") === 0) return c.replace("class-", "");
    }
    return false;
}

var maxSquad = 0;
/**
 * Creates a new squad item and appends it to the shortest (by pixel height) squad column.
 * @return {JQuery} squad   the newly-created squad element
 */
function makeNewSquad() {
    var cols = $(".squad-column");
    var shortcol = cols.first();

    cols.slice(1).each(function() {
        shortcol = ($(this).innerHeight() < shortcol.innerHeight()) ? $(this) : shortcol;
    });

    var squad = $("<div class='squad'></div>");
    var list = $("<ul class='player_list'></ul>").sortable({
        connectWith: ".player_list"
    });
    squad.attr("id", "squad"+maxSquad++)
        .append("<div class='squad-header'>New Squad</div>")
        .append($("<div class='squad-list'></div>").append(list))
        .addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
        .find(".squad-header")
        .addClass("ui-widget-header ui-corner-all")
        .prepend("<span class='ui-icon ui-icon-pencil squad-rename'></span>")
        .prepend("<span class='ui-icon ui-icon-close squad-close'></span>");

    shortcol.append(squad);
    return squad;
}

/**
 * Renames the given squad.
 * @param {JQuery} squad    the squad to rename.
 * @param {String} newName  the name to give the squad (20 characters or shorter)
 * @return {Boolean} success    true if the squad was successfully renamed, false otherwise.
 */
function renameSquad(squad, newName) {
    if (newName.length < 21) {
        squad.children(".squad-header").get(0).lastChild.nodeValue = newName;
        return true;
    } else return false;
}

/**
 * Places the given player list element in its appropriate class list.
 * @param {JQuery} player   the player list element to unorphan.
 */
function unorphanPlayer(player) {
    $("#class-list-"+extractClassName(player)).append(player);
}

/**
 * Removes the given player from the roster, after showing a confirmation.
 * @param {JQuery} player   the player  to remove
 */
function removePlayer(player) {
    deletePlayerDialog.data("player-id", player.attr("id"));
    deletePlayerDialog.dialog("open");
}

/**
 * Adds a new player to the player lists.
 * @param {String} name the name of the player to add (12 characters or shorter)
 * @param {String} className    the character class of the player to add
 * @return {Boolean} success    true if the player was successfully added, false otherwise
 */
function addPlayer(name, className) {
    unorphanPlayer($("<li/>", {
        "id": "player"+(++maxPlayerID),
        "class": "ui-state-default class-"+className,
        "text": name
    }));
    var counter = $("#class-count-"+className);
    counter.text(Math.max(Number(counter.text())+1), 0);
}

$(function() {
    connectWS();
    /********************
     *  Create dialogs  *
     ********************/
    deletePlayerDialog = $("#dialog-confirm-delete-player").dialog({
        autoOpen: false,
        resizable: false,
        height: 225,
        width: 450,
        modal: true,
        buttons: {
            "Remove Player": function() {
                var player = $("#"+deletePlayerDialog.data("player-id"));
                var counter = $("#class-count-"+extractClassName(player));
                counter.text(Math.max(Number(counter.text())-1), 0);
                player.remove();
                deletePlayerDialog.dialog("close");
            },
            Cancel: function() {
                deletePlayerDialog.dialog("close");
            }
        }
    });


    /**
     * Creates and returns a dialog for the given dialog form.
     * @param {JQuery} object   the object to create the dialog from
     * @param {Function} submitFunction the function to execute on form submission
     * @param {Function} closeFunction  the function to execute on dialog closure
     * @return {JQuery} dialog  the created dialog object
     */
    var setupFormDialog = function(object, submitFunction, closeFunction) {
        var dialog = object.dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            buttons: {
                "Submit": submitFunction,
                Cancel: function() {
                    dialog.dialog("close");
                }
            },
            close: closeFunction
        });
        dialog.find("form").on("submit", function(event) {
            event.preventDefault();
            submitFunction();
        });
        return dialog;
    };
    renameSquadDialog = setupFormDialog($("#dialog-form-rename-squad"), function() {
        var name = $("#squadName").val();
        if (renameSquad($("#"+renameSquadDialog.data("squad-id")), name))
            renameSquadDialog.dialog("close");
    }, function() {
        $("#squadName").val("").removeClass("ui-state-error");
    });
    newPlayerDialog = setupFormDialog($("#dialog-form-add-player"), function() {
        var name = $("#newPlayerName").val();
        var className = $("#newPlayerClass").children("option:selected").val();
        addPlayer(name, className);
        newPlayerDialog.dialog("close");
    }, function() {
        $("#newPlayerName").val("").removeClass("ui-state-error");
        $("#newPlayerClass").val("Blademaster").removeClass("ui-state-error");
    });

    /***************************
     * Initialize player lists *
     ***************************/
    $(".player_list").sortable({
        connectWith: ".player_list",
        receive: function(event, ui) {
            var item = $(ui.item);
            if (!item.hasClass($(event.target).attr("id").replace("class-list-", "class-")))
                unorphanPlayer(item);
        }
    }).disableSelection();
    $("#class_list").accordion();
    maxPlayerID = Math.max.apply(Math, $("li.ui-state-default").map(function() {
        return Number($(this).attr("id").replace("player", ""));
    }).get());
    $("#newPlayerButton").button({
        icons: {
            primary: "ui-icon-plusthick"
        }
    }).click(function() {
        newPlayerDialog.dialog("open");
    });

    /**************************
     * Initialize squad lists *
     **************************/
    makeNewSquad();
    $( ".squad-column" ).sortable({
        connectWith: ".squad-column",
        handle: ".squad-header",
        cancel: ".squad-toggle, .squad-rename",
        placeholder: "squad-placeholder ui-corner-all"
    }).on("click", ".squad-close", function() {
        var squad = $(this).parents(".squad");
        squad.find(".player_list").children().each(function() {
            unorphanPlayer($(this));
        });
        squad.remove();
    }).on("click", ".squad-rename", function() {
        var currentSquad = $(this).closest(".squad").attr("id");
        renameSquadDialog.data("squad-id", currentSquad);
        $("#squadName").val($("#"+currentSquad).children(".squad-header").text());
        renameSquadDialog.dialog("open");
    });
    $("#newSquadButton").button({
        icons: {
            primary: "ui-icon-plusthick"
        }
    }).click(function() {
        var squad = makeNewSquad();
        renameSquadDialog.data("squad-id", squad.attr("id"));
        renameSquadDialog.dialog("open");
    });


    /************************
     * Set up context menus *
     ************************/
    $.contextMenu({
        // define which elements trigger this menu
        selector: "li.ui-state-default",
        // define the elements of the menu
        items: {
            delete: {
                name: "Delete", callback: function(key, opt) {
                    removePlayer(this);
                }
            }
    });
});


