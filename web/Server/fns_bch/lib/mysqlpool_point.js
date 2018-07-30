var _x51434 = [
    'generic-pool',
    './../defined/config.json',
    'mysql',
    'Pool',
    'createConnection',
    'mysqldb_point',
    'connect',
    'log',
    'end',
    '[mysql]point db destroy ',
    ' , client:',
    'toString',
    'on',
    'exit',
    'drain',
    '[mysql]popool_point db destroyAllNow : ',
    'destroyAllNow',
    'beginTransaction',
    'rollback',
    'apply',
    'commit',
    'acquire',
    'release',
    'inTransaction'
];
var generic_pool_point = require(_x51434[0]), config = require(_x51434[1]);
var mysql = require(_x51434[2]);
var popool_point = generic_pool_point[_x51434[3]]({
    name: _x51434[2],
    create: function (callback) {
        var client = mysql[_x51434[4]](config[_x51434[5]]);
        client[_x51434[6]](function (error) {
            if (error) {
                console[_x51434[7]](error);
            }
            callback(error, client);
        });
    },
    destroy: function (client) {
        client[_x51434[8]]();
        console[_x51434[7]](_x51434[9] + new Date() + _x51434[10] + client[_x51434[11]]());
    },
    min: 1,
    max: 200,
    idleTimeoutMillis: 30000,
    maxWaitingClients: 500,
    autostart: true,
    log: false
});
process[_x51434[12]](_x51434[13], function () {
    popool_point[_x51434[14]](function () {
        console[_x51434[7]](_x51434[15] + new Date());
        popool_point[_x51434[16]]();
    });
});
var inTransaction = function (body, callback) {
    withConnection(function (db, done) {
        db[_x51434[17]](function (err) {
            if (err)
                return done(err);
            body(db, finished);
        });
        function finished(err) {
            var context = this;
            var args = arguments;
            if (err) {
                if (err == _x51434[18]) {
                    args[0] = err = null;
                }
                db[_x51434[18]](function () {
                    done[_x51434[19]](context, args);
                });
            } else {
                db[_x51434[20]](function (err) {
                    args[0] = err;
                    done[_x51434[19]](context, args);
                });
            }
        }
    }, callback);
};
function withConnection(body, callback) {
    popool_point[_x51434[21]](function (err, db) {
        if (err)
            return callback(err);
        body(db, finished);
        function finished() {
            popool_point[_x51434[22]](db);
            callback[_x51434[19]](this, arguments);
        }
    });
}
exports[_x51434[23]] = inTransaction;