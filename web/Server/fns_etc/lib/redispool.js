var _x26263 = [
    'redis',
    'connectCacheServer',
    'createClient',
    'port',
    'config',
    'host',
    'code',
    'error',
    'ECONNREFUSED',
    'The server refused the connection',
    'total_retry_time',
    'Retry time exhausted',
    'times_connected',
    'max',
    'attempt',
    'function',
    'connect',
    'on',
    'log',
    'redis connected - ',
    ':',
    'setServerErrorLog',
    './../models/logic_db',
    '-500',
    '[error]redis Error',
    '',
    'redis Error ',
    'end',
    '[end]redis Error'
];
var redis = require(_x26263[0]);
var cacheserver;
var is_cacheserver_run = false;
exports[_x26263[1]] = function (callback) {
    cacheserver = redis[_x26263[2]](global[_x26263[4]][_x26263[0]][_x26263[3]], global[_x26263[4]][_x26263[0]][_x26263[5]], {
        retry_strategy: function (options) {
            if (options[_x26263[7]][_x26263[6]] === _x26263[8]) {
                return new Error(new Date() + _x26263[9]);
            }
            if (options[_x26263[10]] > 1000 * 60 * 60) {
                return new Error(new Date() + _x26263[11]);
            }
            if (options[_x26263[12]] > 10) {
                return undefined;
            }
            return Math[_x26263[13]](options[_x26263[14]] * 100, 3000);
        }
    });
    if (typeof callback === _x26263[15]) {
        callback(cacheserver, _x26263[16]);
    }
    cacheserver[_x26263[17]](_x26263[16], function () {
        is_cacheserver_run = true;
        console[_x26263[18]](new Date() + _x26263[19] + config[_x26263[0]][_x26263[5]] + _x26263[20] + config[_x26263[0]][_x26263[3]]);
        if (typeof callback === _x26263[15]) {
            callback(cacheserver, _x26263[16]);
        }
    });
    cacheserver[_x26263[17]](_x26263[7], function (err) {
        is_cacheserver_run = false;
        require(_x26263[22])[_x26263[21]](_x26263[23], _x26263[24], _x26263[25] + err);
        console[_x26263[18]](new Date() + _x26263[26] + err);
        if (typeof callback === _x26263[15]) {
            callback(cacheserver, _x26263[7]);
        }
    });
    cacheserver[_x26263[17]](_x26263[27], function (err) {
        is_cacheserver_run = false;
        require(_x26263[22])[_x26263[21]](_x26263[23], _x26263[28], _x26263[25] + err);
        console[_x26263[18]](new Date() + _x26263[26] + err);
        if (typeof callback === _x26263[15]) {
            callback(cacheserver, _x26263[27]);
        }
    });
};