function calculate_refine() {
    var input = $("select#refine");
    var base = input.parents("article").first().data("refine");
    var level = input.children(":selected").val();
    
    var refine = refine_amount(level, base);
    
    $(".refine_element").each(function() {
        $(this).text(Math.floor($(this).data("original")+refine));
        if (level != 0)
            $(this).css("color", "#dd4444");
        else
            $(this).removeAttr("style");
    });
}