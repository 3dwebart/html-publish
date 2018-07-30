var _x43241 = [
    'redis',
    'connectCacheServer',
    'createClient',
    'port',
    'redis_member',
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
var redis = require(_x43241[0]);
var cacheserver;
var is_cacheserver_run = false;
exports[_x43241[1]] = function (callback) {
    cacheserver = redis[_x43241[2]](global[_x43241[5]][_x43241[4]][_x43241[3]], global[_x43241[5]][_x43241[4]][_x43241[6]], {
        retry_strategy: function (options) {
            if (options[_x43241[8]][_x43241[7]] === _x43241[9]) {
                return new Error(new Date() + _x43241[10]);
            }
            if (options[_x43241[11]] > 1000 * 60 * 60) {
                return new Error(new Date() + _x43241[12]);
            }
            if (options[_x43241[13]] > 10) {
                return undefined;
            }
            return Math[_x43241[14]](options[_x43241[15]] * 100, 3000);
        }
    });
    if (typeof callback === _x43241[16]) {
        callback(cacheserver, _x43241[17]);
    }
    cacheserver[_x43241[18]](_x43241[17], function () {
        is_cacheserver_run = true;
        console[_x43241[19]](new Date() + _x43241[20] + config[_x43241[0]][_x43241[6]] + _x43241[21] + config[_x43241[0]][_x43241[3]]);
        if (typeof callback === _x43241[16]) {
            callback(cacheserver, _x43241[17]);
        }
    });
    cacheserver[_x43241[18]](_x43241[8], function (err) {
        is_cacheserver_run = false;
        require(_x43241[23])[_x43241[22]](_x43241[24], _x43241[25], _x43241[26] + err);
        console[_x43241[19]](new Date() + _x43241[27] + err);
        if (typeof callback === _x43241[16]) {
            callback(cacheserver, _x43241[8]);
        }
    });
    cacheserver[_x43241[18]](_x43241[28], function (err) {
        is_cacheserver_run = false;
        require(_x43241[23])[_x43241[22]](_x43241[24], _x43241[29], _x43241[26] + err);
        console[_x43241[19]](new Date() + _x43241[27] + err);
        if (typeof callback === _x43241[16]) {
            callback(cacheserver, _x43241[28]);
        }
    });
};