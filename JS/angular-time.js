/**
 * Created by nabossha on 21.12.2016.
 */
angular
    .module('angular-time.filter', [ ])
.filter('time', function() {

    var conversions = {
        'ss': angular.identity,
        'mm': function(value) { return value * 60; },
        'hh': function(value) { return value * 3600; }
    };

    var padding = function(value, length) {
        var zeroes = length - ('' + (value)).length,
            pad = '';
        while(zeroes-- > 0) pad += '0';
        return pad + value;
    };

    return function(value, unit, format, isPadded) {
        var totalSeconds = conversions[unit || 'ss'](value),
            hh = Math.floor(totalSeconds / 3600),
            mm = Math.floor((totalSeconds % 3600) / 60),
            ss = totalSeconds % 60;

        format = format || 'hh:mm:ss';
        isPadded = angular.isDefined(isPadded)? isPadded: true;
        hh = isPadded? padding(hh, 2): hh;
        mm = isPadded? padding(mm, 2): mm;
        ss = isPadded? padding(ss, 2): ss;

        return format.replace(/hh/, hh).replace(/mm/, mm).replace(/ss/, ss);
    };
});
angular
    .module('angular-time', [
        'angular-time.filter',
    ]);