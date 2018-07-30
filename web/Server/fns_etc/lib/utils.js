var _x32960 = [
    'crypto',
    'funhansoftbugnot',
    'aedcbi0876543219',
    'nodeDecrypt',
    'exports',
    'run',
    'createDecipheriv',
    'aes-128-cbc',
    'update',
    'hex',
    'utf-8',
    'final',
    'nodeEecrypt',
    'createCipheriv',
    'calfloat',
    'number',
    'ROUND',
    'toFixed',
    'pow',
    'CEIL',
    'ceil',
    'FLOOR',
    'floor'
];
var crypto = require(_x32960[0]), key = _x32960[1], iv = _x32960[2];
module[_x32960[4]][_x32960[3]] = function (crypted) {
    domain[_x32960[5]](function () {
        var decrypted;
        var decipher = crypto[_x32960[6]](_x32960[7], key, iv), decrypted = decipher[_x32960[8]](crypted, _x32960[9], _x32960[10]);
        try {
            decrypted = decipher[_x32960[11]](_x32960[10]);
        } catch (er2) {
        }
        return decrypted;
    });
};
module[_x32960[4]][_x32960[12]] = function (text) {
    var cipher = crypto[_x32960[13]](_x32960[7], key, iv);
    var crypted = cipher[_x32960[8]](text, _x32960[10], _x32960[9]);
    crypted += cipher[_x32960[11]](_x32960[9]);
    return crypted;
};
exports[_x32960[14]] = function (strMode, nCalcVal, nDigit) {
    try {
        if (typeof nCalcVal === _x32960[15]) {
            nCalcVal = parseFloat(nCalcVal);
        } else {
            return nCalcVal;
        }
    } catch (e) {
        return nCalcVal;
    }
    if (strMode == _x32960[16]) {
        if (nDigit < 0) {
            nDigit = -nDigit;
            nCalcVal = (nCalcVal / Math[_x32960[18]](10, nDigit))[_x32960[17]](0) * Math[_x32960[18]](10, nDigit);
        } else {
            nCalcVal = nCalcVal[_x32960[17]](nDigit);
        }
    } else if (strMode == _x32960[19]) {
        if (nDigit < 0) {
            nDigit = -nDigit;
            nCalcVal = Math[_x32960[20]](nCalcVal / Math[_x32960[18]](10, nDigit)) * Math[_x32960[18]](10, nDigit);
        } else {
            nCalcVal = Math[_x32960[20]](nCalcVal * Math[_x32960[18]](10, nDigit)) / Math[_x32960[18]](10, nDigit);
        }
    } else if (strMode == _x32960[21]) {
        if (nDigit < 0) {
            nDigit = -nDigit;
            nCalcVal = Math[_x32960[22]](nCalcVal / Math[_x32960[18]](10, nDigit)) * Math[_x32960[18]](10, nDigit);
        } else {
            nCalcVal = Math[_x32960[22]](nCalcVal * Math[_x32960[18]](10, nDigit)) / Math[_x32960[18]](10, nDigit);
        }
    } else {
        nCalcVal = nCalcVal[_x32960[17]](0);
    }
    return parseFloat(nCalcVal);
};