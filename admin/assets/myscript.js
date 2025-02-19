var imgf_change = function (ctrl, to, df) {
    if (df == null) df = "../images/2148875971.jpg";
    var input = $(ctrl)[0];
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(to).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
        var name = input.files[0].name;
        var size = input.files[0].size;
        var type = input.files[0].type; // "image/jpeg" | image/png | image/gif | image/pjpeg
        var arr = name.split(".");
        var fType = (arr[arr.length - 1]).toLowerCase();;
        if (arr.length >= 2 && (fType == "jpg" || fType == "png" || fType == "gif" || fType == "JPEG" || fType == "JPG" || fType == "PNG" || fType == "GIF" || fType == "jpeg" || fType == "pjpeg")) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(to).attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            alert("รูปแบบไม่รองรับ รองรับเฉพาะ .jpg, .png, .gif");
            $(ctrl).val('');
            $(to).attr('src', df);
        }
    } else {
        alert("รูปแบบไม่รองรับ รองรับเฉพาะ .jpg, .png, .gif");
        $(ctrl).val('');
        $(to).attr('src', df);
    }
}

function errorImage(ctrl, df) {
    $(ctrl).attr("src", df);
}

var defaultCalendar = {
    sameDay: '[Today at] LT',
    nextDay: '[Tomorrow at] LT',
    nextWeek: 'dddd [at] LT',
    lastDay: '[Yesterday at] LT',
    lastWeek: '[Last] dddd [at] LT',
    sameElse: 'L'
};

function calendar(key, mom, now) {
    var output = this._calendar[key] || this._calendar['sameElse'];
    return isFunction(output) ? output.call(mom, now) : output;
}

var defaultLongDateFormat = {
    LTS: 'h:mm:ss A',
    LT: 'h:mm A',
    L: 'MM/DD/YYYY',
    LL: 'MMMM D, YYYY',
    LLL: 'MMMM D, YYYY h:mm A',
    LLLL: 'dddd, MMMM D, YYYY h:mm A'
};

function longDateFormat(key) {
    var format = this._longDateFormat[key],
        formatUpper = this._longDateFormat[key.toUpperCase()];

    if (format || !formatUpper) {
        return format;
    }

    this._longDateFormat[key] = formatUpper.replace(/MMMM|MM|DD|dddd/g, function (val) {
        return val.slice(1);
    });

    return this._longDateFormat[key];
}

var defaultInvalidDate = 'Invalid date';

function invalidDate() {
    return this._invalidDate;
}

var defaultOrdinal = '%d';
var defaultDayOfMonthOrdinalParse = /\d{1,2}/;

function ordinal(number) {
    return this._ordinal.replace('%d', number);
}

var defaultRelativeTime = {
    future: 'in %s',
    past: '%s ago',
    s: 'a few seconds',
    ss: '%d seconds',
    m: 'a minute',
    mm: '%d minutes',
    h: 'an hour',
    hh: '%d hours',
    d: 'a day',
    dd: '%d days',
    M: 'a month',
    MM: '%d months',
    y: 'a year',
    yy: '%d years'
};

function relativeTime(number, withoutSuffix, string, isFuture) {
    var output = this._relativeTime[string];
    return (isFunction(output)) ?
        output(number, withoutSuffix, string, isFuture) :
        output.replace(/%d/i, number);
}

function pastFuture(diff, output) {
    var format = this._relativeTime[diff > 0 ? 'future' : 'past'];
    return isFunction(format) ? format(output) : format.replace(/%s/i, output);
}