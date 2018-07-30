var _x60556 = [
    'q',
    'promiseWhile',
    'exports',
    'defer',
    'resolve',
    'when',
    'reject',
    'nextTick',
    'promise',
    'promiseDelay',
    'delay'
];
var Q = require(_x60556[0]);
module[_x60556[2]][_x60556[1]] = function (condition, body) {
    var done = Q[_x60556[3]]();
    function loop() {
        if (!condition())
            return done[_x60556[4]]();
        Q[_x60556[5]](body(), loop, done[_x60556[6]]);
    }
    Q[_x60556[7]](loop);
    return done[_x60556[8]];
};
module[_x60556[2]][_x60556[9]] = function (sec) {
    return Q[_x60556[10]](sec);
};