var _x17805 = [
    'generic-pool',
    './../defined/config.json',
    'mysql',
    'Pool',
    'createConnection',
    'mysqldb',
    'connect',
    'log',
    'end',
    'on',
    'exit',
    'drain',
    '[mysql]pool db destroyAllNow : ',
    'destroyAllNow',
    'exports'
];
var generic_pool = require(_x17805[0]), config = require(_x17805[1]);
var mysql = require(_x17805[2]);
var pool = generic_pool[_x17805[3]]({
    name: _x17805[2],
    create: function (callback) {
        var client = mysql[_x17805[4]](config[_x17805[5]]);
        client[_x17805[6]](function (error) {
            if (error) {
                console[_x17805[7]](error);
            }
            callback(error, client);
        });
    },
    destroy: function (client) {
        client[_x17805[8]]();
    },
    min: 1,
    max: 100,
    idleTimeoutMillis: 30000,
    maxWaitingClients: 500,
    autostart: true,
    log: false
});
process[_x17805[9]](_x17805[10], function () {
    pool[_x17805[11]](function () {
        console[_x17805[7]](_x17805[12] + new Date());
        pool[_x17805[13]]();
    });
});
module[_x17805[14]] = pool;