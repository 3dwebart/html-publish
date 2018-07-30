var mineStatus = '\
<div class="user-mine">\
        <div class="inner">\
            <div class="list">\
                <div class="article">\
                    '+langConvert('lang.viewExchangeBuybtcOrderDesc1','')+' <span id="mb_krw_total">0</span>\
                    <div class="over">\
                        <dl>\
                            <dt>'+langConvert('lang.commonPossible','')+'</dt>\
                            <dd id="mb_krw_poss">0</dd>\
                        </dl>\
                        <dl>\
                            <dt>'+langConvert('lang.commonUsed','')+'</dt>\
                            <dd id="mb_krw_use">0</dd>\
                        </dl>\
                    </div>\
                </div>\
                <div class="article">\
                    '+langConvert('lang.bitCoin','')+' <span id="mb_btc_total">0.00000000</span>\
                    <div class="over">\
                        <dl>\
                            <dt>'+langConvert('lang.commonPossible','')+'</dt>\
                            <dd id="mb_btc_poss">0.00000000</dd>\
                        </dl>\
                        <dl>\
                            <dt>'+langConvert('lang.commonUsed','')+'</dt>\
                            <dd id="mb_btc_use">0.00000000</dd>\
                        </dl>\
                    </div>\
                </div>\
                <div class="article">\
                    '+langConvert('lang.bitCoinCash','')+' <span id="mb_bch_total">0.00000000</span>\
                    <div class="over">\
                        <dl>\
                            <dt>'+langConvert('lang.commonPossible','')+'</dt>\
                            <dd id="mb_bch_poss">0.00000000</dd>\
                        </dl>\
                        <dl>\
                            <dt>'+langConvert('lang.commonUsed','')+'</dt>\
                            <dd id="mb_bch_use">0.00000000</dd>\
                        </dl>\
                    </div>\
                </div>\
                <div class="article">\
                    '+langConvert('lang.liteCoin','')+' <span id="mb_ltc_total">0.00000000</span>\
                    <div class="over">\
                        <dl>\
                            <dt>'+langConvert('lang.commonPossible','')+'</dt>\
                            <dd id="mb_ltc_poss">0.00000000</dd>\
                        </dl>\
                        <dl>\
                            <dt>'+langConvert('lang.commonUsed','')+'</dt>\
                            <dd id="mb_ltc_use">0.00000000</dd>\
                        </dl>\
                    </div>\
                </div>\
                <div class="article">\
                    '+langConvert('lang.ethereum','')+' <span id="mb_eth_total">0.00000000</span>\
                    <div class="over">\
                        <dl>\
                            <dt>'+langConvert('lang.commonPossible','')+'</dt>\
                            <dd id="mb_eth_poss">0.00000000</dd>\
                        </dl>\
                        <dl>\
                            <dt>'+langConvert('lang.commonUsed','')+'</dt>\
                            <dd id="mb_eth_use">0.00000000</dd>\
                        </dl>\
                    </div>\
                </div>\
                <div class="article">\
                    '+langConvert('lang.ethereumClassic','')+' <span id="mb_etc_total">0.00000000</span>\
                    <div class="over">\
                        <dl>\
                            <dt>'+langConvert('lang.commonPossible','')+'</dt>\
                            <dd id="mb_etc_poss">0.00000000</dd>\
                        </dl>\
                        <dl>\
                            <dt>'+langConvert('lang.commonUsed','')+'</dt>\
                            <dd id="mb_etc_use">0.00000000</dd>\
                        </dl>\
                    </div>\
                </div>\
            </div>\
        </div>\
    </div>\
';
if( get_member.hasOwnProperty('mb_id') ){
    document.write(mineStatus);
}