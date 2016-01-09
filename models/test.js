var members = [
    1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15,
    16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30,
    31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43
];

memberPm(members);

function memberPm(members) {
    var y = members.length,
        x = Math.ceil(y / 20),
        names = [];

    // iterate windows
    for (w = 0; w < x; w++) {

        // iterate members
        for (i = w * 20; i < w * 20 + 20; i++) {
            if (i >= y) {
                break;
            } else {
                names.push(members[i])
            }
        }

        var link = "<a href='/send/pm/to/" + implode(';', names) + "/'>PM Link</a><br />";

        $(".links").append(link);
        names = [];
    }
}

function implode(glue, pieces) {
    var i = '',
        retVal = '',
        tGlue = '';
    if (arguments.length === 1) {
        pieces = glue;
        glue = '';
    }
    if (typeof pieces === 'object') {
        if (Object.prototype.toString.call(pieces) === '[object Array]') {
            return pieces.join(glue);
        }
        for (i in pieces) {
            retVal += tGlue + pieces[i];
            tGlue = glue;
        }
        return retVal;
    }
    return pieces;
}