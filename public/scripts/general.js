function refine_amount(level, base) {
    level = Math.max(Math.min(level, 12), 0);
    var multiplier = 0;
    switch(level) {
        case 12:
            multiplier += 8.5;
        case 11:
            multiplier += 6.97;
        case 10:
            multiplier += 4.98;
        case 9:
            multiplier += 4.05;
        case 8:
            multiplier += 3.05;
        case 7:
            multiplier += 2.4;
        case 6:
            multiplier += 1.8;
        case 5:
            multiplier += 1.45;
        case 4:
            multiplier += 1.25;
        case 3:
            multiplier += 1.05;
        case 2:
            multiplier += 1;
        case 1:
            multiplier += 1;
    }
    return base*multiplier;
}