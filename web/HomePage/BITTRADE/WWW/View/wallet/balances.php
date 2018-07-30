<head>
    <link href="<?= $view['url']['static']?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/common.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom.css" rel="stylesheet">
    <link href="<?= $view['url']['static']?>/assets/css/custom_temp.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/jquery-validate.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/js/utils.min.js"></script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-comm.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-form.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/write/common-pagination.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initHeaderScript();setSocChannel('krw_btc');</script>
</head>

<body class="sub-background">
    <div id="wrap" class="wallet-page">
        <script src="<?=$view['url']['static']?>/assets/write/header-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>

        <!-- CONTAINER -->
        <div id="container">
            <div class="body-title">
                <div class="inner">
                    <p class="tit"><?=Language::langConvert($view['lang'], 'DepositAndWithdrawal');?></p>
                </div>
            </div>
            <div id="contents" class="sub-background none-shadow">
                <div class="balances">
                    <!-- 코인 리스트 -->
                    <div class="assets">
                        <div class="mine">
                            <span class="tit"><?=Language::langConvert($view['lang'], 'assets');?></span>
                            <p><strong class="total-assets">0</strong><span><?=Language::langConvert($view['langcommon'], 'keyCurrency');?></span></p>
                        </div>
                        <div class="ass-table">
                            <table>
                                <colgroup>
                                    <col style="width:150px">
                                    <col style="width:150px">
                                    <col style="">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th><?=Language::langConvert($view['lang'], 'possessionCoin');?></th>
                                        <th><?=Language::langConvert($view['lang'], 'rate');?></th>
                                        <th><?=Language::langConvert($view['langcommon'], 'amount');?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="active" id="parent-cash">
                                        <td class="kind">
                                            <i class="ico-coin ico-krw"></i>
                                            <p><strong><?=Language::langConvert($view['langcommon'], 'KRW_KRW');?></strong><br><?=Language::langConvert($view['langcommon'], 'keyCurrency');?></p>
                                        </td>
                                        <td class="bar">
                                            <p style="width:0%" class="balance-bar-krw"><span class="balance-bar-str-krw">0%</span></p>
                                        </td>
                                        <td class="value">
                                            <p>
                                                <strong class="mb_krw_total">0</strong>
                                                <span><?=Language::langConvert($view['langcommon'], 'keyCurrency');?></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <?php 
                                        foreach ($view['master'] as $key => $value) {
                                            $tmp = explode('_', $key);
                                            $cursymbol = $tmp[1];
                                    ?>
                                    <tr id="parent-<?=strtolower($cursymbol)?>">
                                        <td class="kind">
                                            <i class="ico-coin ico-<?=strtolower($cursymbol)?>"></i>
                                            <p><strong><?=Language::langConvert($view['langcommon'], 'KRW_'.$cursymbol);?></strong><br><?=$cursymbol?></p>
                                        </td>
                                        <td class="bar">
                                            <p style="width:0%" class="balance-bar-<?=strtolower($cursymbol)?>"><span class="balance-bar-str-<?=strtolower($cursymbol)?>">0%</span></p>
                                        </td>
                                        <td class="value">
                                            <p>
                                                <strong class="mb_<?=strtolower($cursymbol)?>_total">0.00000000</strong>
                                                <span><?=$cursymbol?></span>
                                            </p>
                                            <p>
                                                <span class="mb_<?=strtolower($cursymbol)?>_exchange_total">0</span>
                                                <span><?=Language::langConvert($view['langcommon'], 'keyCurrency');?></span>
                                            </p>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- // 코인 리스트 -->

                    <!-- 원화 -->
                    <div class="bank" id="child-cash">
                        <!-- 입금요청 -->
                        <div class="deposit" id="child-cash-deposit">
                            <div class="mine">
                                <span class="tit"><?=Language::langConvert($view['lang'], 'KRW');?></span>
                                <div class="value">
                                    <dl>
                                        <dt><?=Language::langConvert($view['lang'], 'krwBalance');?></dt>
                                        <dd><strong class="mb_krw_total">0</strong> <span><?=Language::langConvert($view['langcommon'], 'keyCurrency');?></span></dd>
                                    </dl>
                                </div>
                            </div>

                            <div class="tab-box">
                                <a href="javascript:selectWallet('deposit');" class="active"><?=Language::langConvert($view['lang'], 'deposit');?></a>
                                <a href="javascript:selectWallet('withdraw');"><?=Language::langConvert($view['lang'], 'withdrawal');?></a>
                                <a href="javascript:selectWallet('history');"><?=Language::langConvert($view['lang'], 'history');?></a>
                            </div>

                            <div class="deposit" >
                                <div class="body">
                                    <div class="depot">
                                        <div class="form">
                                            <dl>
                                                <dt><?=Language::langConvert($view['lang'], 'depositor');?></dt>
                                                <dd><input type="text" name="od_name" class="inp input-alert" value="<?=$view['member']['mb_name']?>" disabled></dd>
                                            </dl>
                                            <dl>
                                                <dt><?=Language::langConvert($view['lang'], 'amount');?></dt>
                                                <dd>
                                                    <input type="text" name="od_temp_bank" class="inp input-calculation" placeholder="<?=Language::langConvert($view['lang'], 'amountInput');?>">
                                                </dd>
                                            </dl>
                                            <button class="btn btn-submit" id="btnDepositCash"><?=Language::langConvert($view['lang'], 'depositButton');?></button>
                                            <div class="error-msg" style="display:none;"></div>
                                        </div>
                                    </div>
                                </div>							
                                <div class="bank-info">
                                    <h4><?=Language::langConvert($view['lang'], 'accountInformation');?></h4>
                                    <p><?=Language::langConvert($view['lang'], 'bank');?> : <span id="cf_bank_name"></span><br><?=Language::langConvert($view['lang'], 'accountHolder');?> : <span id="cf_bank_owner"></span><br><?=Language::langConvert($view['lang'], 'accountNumber');?> : <span id="cf_bank_account"></span></p>
                                </div>
                            </div>
                        </div>
                        <!-- // 입금요청 -->
							
                        <!-- 출금요청 -->
                        <div class="withdraw" id="child-cash-withdraw" style="display: none">
                            <div class="mine">
                                <span class="tit"><?=Language::langConvert($view['lang'], 'KRW');?></span>
                                <div class="value">
                                    <dl>
                                        <dt><?=Language::langConvert($view['lang'], 'krwBalance');?></dt>
                                        <dd><strong class="mb_krw_total">0</strong> <span><?=Language::langConvert($view['langcommon'], 'keyCurrency');?></span></dd>
                                    </dl>
                                </div>
                            </div>

                            <div class="tab-box">
                                <a href="javascript:selectWallet('deposit');"><?=Language::langConvert($view['lang'], 'deposit');?></a>
                                <a href="javascript:selectWallet('withdraw');" class="active"><?=Language::langConvert($view['lang'], 'withdrawal');?></a>
                                <a href="javascript:selectWallet('history');"><?=Language::langConvert($view['lang'], 'history');?></a>
                            </div>
                            
                            <div class="volume">
                                <dl>
                                    <dt><?=Language::langConvert($view['lang'], 'availableKrw');?></dt>
                                    <dd><strong class="mb_krw_poss">0</strong> <?=Language::langConvert($view['langcommon'], 'keyCurrency');?></dd>
                                </dl>
                                <dl>
                                    <dt><?=Language::langConvert($view['lang'], 'dailyMaximumWithdrawal');?></dt>
                                    <dd><strong id="cash_max_limit">0</strong> <?=Language::langConvert($view['langcommon'], 'keyCurrency');?></dd>
                                </dl>
                            </div>

                            <div class="body">
                                <div class="depot">
                                    <div class="form">
                                        <dl>
                                            <dt><?=Language::langConvert($view['lang'], 'withdrawAmount');?></dt>
                                            <dd><input type="text" class="inp input-calculation" name="cash-withdraw-request" placeholder="0"></dd>
                                        </dl>
                                        <dl>
                                            <dt><?=Language::langConvert($view['langcommon'], 'fee');?></dt>
                                            <dd><input type="text" class="inp" name="cash-withdraw-fee" value="0" disabled></dd>
                                        </dl>
                                        <dl>
                                            <dt><?=Language::langConvert($view['lang'], 'withdrawFee');?></dt>
                                            <dd><input type="text" class="inp" name="cash-withdraw-pay" value="0" disabled></dd>
                                        </dl>
                                        <dl>
                                            <dt><?=Language::langConvert($view['lang'], 'bank');?></dt>
                                            <dd><input type="text" class="inp input-alert" name="input_bank_name" placeholder="<?=Language::langConvert($view['lang'], 'TypeYourBankName');?>"></dd>
                                        </dl>
                                        <dl>
                                            <dt><?=Language::langConvert($view['lang'], 'accountNumber');?></dt>
                                            <dd><input type="text" class="inp input-alert" name="input_bank_account" placeholder="<?=Language::langConvert($view['lang'], 'typeYourAccountNumber');?>"></dd>
                                        </dl>
                                        <dl>
                                            <dt><?=Language::langConvert($view['lang'], 'accountHolder');?></dt>
                                            <dd><input type="text" class="inp input-alert" name="input_bank_holder" value="<?=$view['member']['mb_name']?>" disabled></dd>
                                        </dl>
                                        <button class="btn btn-submit" id="btnWithdrawCash"><?=Language::langConvert($view['lang'], 'withdrawal');?></button>
                                        <div class="error-msg" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="bank-info">
                                <h4><?=Language::langConvert($view['lang'], 'aboutKrwWithdrawal');?></h4>
                                <p><?=Language::langConvert($view['lang'], 'youCanWithdrawal2');?> <?=Language::langConvert($view['langcommon'], 'keyCurrency');?><?=Language::langConvert($view['lang'], 'youCanWithdrawal1');?></p>
                            </div>
                        </div>
                        <!-- // 출금요청 -->
								

                        <!-- 입출금내역 -->
                        <div class="history" id="child-cash-history" style="display: none">
                            <div class="mine">
                                <span class="tit"><?=Language::langConvert($view['lang'], 'KRW');?></span>
                                <div class="value">
                                    <dl>
                                        <dt><?=Language::langConvert($view['lang'], 'krwBalance');?></dt>
                                        <dd><strong class="mb_krw_total">0</strong> <span><?=Language::langConvert($view['langcommon'], 'keyCurrency');?></span></dd>
                                    </dl>
                                </div>
                            </div>

                            <div class="tab-box">
                                <a href="javascript:selectWallet('deposit');"><?=Language::langConvert($view['lang'], 'deposit');?></a>
                                <a href="javascript:selectWallet('withdraw');"><?=Language::langConvert($view['lang'], 'withdrawal');?></a>
                                <a href="javascript:selectWallet('history');" class="active"><?=Language::langConvert($view['lang'], 'history');?></a>
                            </div>

                            <div class="body">
                                <div class="title">
                                    <strong class="history-title"><?=Language::langConvert($view['lang'], 'depositsAwaiting');?></strong>
                                    <select class="select1" name="listtype">
                                        <option value="depositwait"><?=Language::langConvert($view['lang'], 'depositsAwaiting');?></option>
                                        <option value="deposit"><?=Language::langConvert($view['lang'], 'deposits');?></option>
                                        <option value="withdraw"><?=Language::langConvert($view['lang'], 'withdrawals');?></option>
                                    </select>
                                </div>

                                <div class="balance-table">
                                    <table class="list-cashhistory">
                                        <thead></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="pagenate" id="pager"></div>
                            </div>
                        </div>	
                        <!-- // 입출금내역 -->
                    </div>
                    <!-- // 원화 -->
						
						
                    <!-- 코인 -->
                    <div class="bank" id="child-coin" style="display: none">
                        <!-- 입금요청 -->
                        <div class="deposit" id="child-coin-deposit">
                            <div class="mine">
                                <span class="tit mb_coin_title">비트코인</span>
                                <div class="value">
                                    <dl>
                                        <dt><?=Language::langConvert($view['lang'], 'coinBalance');?></dt>
                                        <dd><strong class="total_controller mb_btc_total">0.00000000</strong> <span class="coin_currency">BTC</span></dd>
                                    </dl>
                                    <dl>
                                        <dt><?=Language::langConvert($view['lang'], 'evaluatedPrice');?></dt>
                                        <dd><strong class="exchange_total_controller mb_btc_exchange_total">0</strong> <span><?=Language::langConvert($view['langcommon'], 'keyCurrency');?></span></dd>
                                    </dl>
                                </div>
                            </div>

                            <div class="tab-box">
                                <a href="javascript:selectCoinWallet('deposit');" class="active"><?=Language::langConvert($view['lang'], 'deposit');?></a>
                                <a href="javascript:selectCoinWallet('withdraw');"><?=Language::langConvert($view['lang'], 'withdrawal');?></a>
                                <a href="javascript:selectCoinWallet('history');"><?=Language::langConvert($view['lang'], 'history');?></a>
                            </div>

                            <div class="deposit" >
                                <div class="body">
                                    <div class="qrcode-box">
                                        <div class="code">
                                            <p class="tit"><?=Language::langConvert($view['lang'], 'qrcode');?></p>
                                            <div class="qr"></div>
                                        </div>
                                        <div class="addr">
                                            <p class="tit"><?=Language::langConvert($view['lang'], 'depositAddress');?></p>
                                            <div class="addr-box">
                                                <div class="inp-box"><input type="text" id="deposit-address" disabled><a href="#" class="btn-copy"><?=Language::langConvert($view['lang'], 'copy');?></a></div>
                                                <div class="desc"><?=Language::langConvert($view['lang'], 'coinDepositDesc');?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="none-qrcode">
                                        <p class="desc"><?=Language::langConvert($view['lang'], 'coinDepositAddr');?></p>
                                        <button class="btn btn-l btn-primary btn-block" onClick="createWallet()"><?=Language::langConvert($view['lang'], 'createAddress');?></button>
                                    </div>
                                    <div class="wait wait-controller" style="display: none">
                                        <p>입금기능 준비중입니다.</p>
                                    </div>
                                </div>
                            </div>
								
			</div>
			<!-- // 입금요청 -->
							
                        <!-- 출금요청 -->
                        <div class="withdraw" id="child-coin-withdraw" style="display: none">
                            <div class="mine">
                                <span class="tit mb_coin_title">비트코인</span>
                                <div class="value">
                                    <dl>
                                        <dt><?=Language::langConvert($view['lang'], 'coinBalance');?></dt>
                                        <dd><strong class="total_controller mb_btc_total">0.00000000</strong> <span class="coin_currency">BTC</span></dd>
                                    </dl>
                                    <dl>
                                        <dt><?=Language::langConvert($view['lang'], 'evaluatedPrice');?></dt>
                                        <dd><strong class="exchange_total_controller mb_btc_exchange_total">0</strong> <span><?=Language::langConvert($view['langcommon'], 'keyCurrency');?></span></dd>
                                    </dl>
                                </div>
                            </div>

                            <div class="tab-box">
                                <a href="javascript:selectCoinWallet('deposit');"><?=Language::langConvert($view['lang'], 'deposit');?></a>
                                <a href="javascript:selectCoinWallet('withdraw');" class="active"><?=Language::langConvert($view['lang'], 'withdrawal');?></a>
                                <a href="javascript:selectCoinWallet('history');"><?=Language::langConvert($view['lang'], 'history');?></a>
                            </div>

                            <div class="volume coin-body-controller">
                                <dl>
                                    <dt><?=Language::langConvert($view['lang'], 'availableCoin');?></dt>
                                    <dd><strong class="coin_daily_max_limit poss_controller">0.00000000</strong> <span class="coin_currency">BTC</span></dd>
                                </dl>
                                <dl>
                                    <dt><?=Language::langConvert($view['lang'], 'dailyMaximumWithdrawalCoin');?></dt>
                                    <dd><strong class="coin_max_limit">0.00000000</strong> <span class="coin_currency">BTC</span></dd>
                                </dl>
                            </div>

                            <div class="body">
                                <div class="depot coin-body-controller">
                                    <div class="form">
                                        <dl>
                                            <dt><?=Language::langConvert($view['lang'], 'coinAddress');?></dt>
                                            <dd><input type="text" class="inp" name="coin-withdraw-addr" placeholder="<?=Language::langConvert($view['lang'], 'coinAddress');?>"></dd>
                                        </dl>
                                        <dl>
                                            <dt><?=Language::langConvert($view['lang'], 'withdrawalAmount');?></dt>
                                            <dd><input type="text" class="inp input-calculation" name="coin-withdraw-request" maxlength="15" placeholder="0.00000000"></dd>
                                        </dl>
                                        <dl>
                                            <dt><?=Language::langConvert($view['langcommon'], 'fee');?></dt>
                                            <dd><input type="text" class="inp" name="coin-withdraw-fee" value="0.00000000" disabled></dd>
                                        </dl>
                                        <dl>
                                            <dt><?=Language::langConvert($view['lang'], 'withdrawalAmountFee');?></dt>
                                            <dd><input type="text" class="inp" name="coin-withdraw-pay" value="0.00000000" disabled></dd>
                                        </dl>
                                        <?php if($view['member']['otp_withdraw']=='Y'){?>
                                        <dl>
                                            <dt><?php Language::langConvert($view['langcommon'], 'otp');?></dt>
                                            <dd>
                                                <input type="text" class="inp otp-input" name="g_otp" maxlength="6" placeholder="<?php Language::langConvert($view['lang'], 'placeholderOtp');?>">
                                                <button class="btn btn-l btn-add" id="otp-btn"><?=Language::langConvert($view['lang'], 'confirmOtp')?></button>                                            
                                            </dd>
                                        </dl>
                                        <?php }?>
                                        <button class="btn btn-submit" id="btnWithdrawCoin"><?=Language::langConvert($view['lang'], 'withdrawal');?></button>
                                        <div class="error-msg" style="display:none;"></div>
                                    </div>
                                </div>
                                <div class="wait wait-controller" style="display: none">
                                    <p>출금기능 준비중입니다.</p>
                                </div>
                            </div>							
                            <div class="bank-info coin-body-controller">
                                <h4><?=Language::langConvert($view['lang'], 'aboutKrwWithdrawal');?></h4>
                                <p>
                                <?=Language::langConvert($view['lang'], 'withdrawDesc2');?>
                                    <?=Language::langConvert($view['lang'], 'withdrawDesc7');?>
                                </p>
                            </div>
                        </div>
                        <!-- // 출금요청 -->

                        <!-- 입출금내역 -->
                        <div class="history" id="child-coin-history" style="display: none">
                            <div class="mine">
                                <span class="tit mb_coin_title">비트코인</span>
                                <div class="value">
                                    <dl>
                                        <dt><?=Language::langConvert($view['lang'], 'coinBalance');?></dt>
                                        <dd><strong class="total_controller mb_btc_total">0.00000000</strong> <span class="coin_currency">BTC</span></dd>
                                    </dl>
                                    <dl>
                                        <dt><?=Language::langConvert($view['lang'], 'evaluatedPrice');?></dt>
                                        <dd><strong class="exchange_total_controller mb_btc_exchange_total">0</strong> <span><?=Language::langConvert($view['langcommon'], 'keyCurrency');?></span></dd>
                                    </dl>
                                </div>
                            </div>

                            <div class="tab-box">
                                <a href="javascript:selectCoinWallet('deposit');"><?=Language::langConvert($view['lang'], 'deposit');?></a>
                                <a href="javascript:selectCoinWallet('withdraw');"><?=Language::langConvert($view['lang'], 'withdrawal');?></a>
                                <a href="javascript:selectCoinWallet('history');" class="active"><?=Language::langConvert($view['lang'], 'history');?></a>
                            </div>
                            <div class="body">
                                <div class="title">
                                    <strong class="history-title"><?=Language::langConvert($view['lang'], 'depositsAwaiting');?></strong>
                                    <select name="coinlisttype" class="select1">
                                        <option value="depositwait"><?=Language::langConvert($view['lang'], 'depositsAwaiting');?></option>
                                        <option value="deposit"><?=Language::langConvert($view['lang'], 'deposits');?></option>
                                        <option value="withdraw"><?=Language::langConvert($view['lang'], 'withdrawals');?></option>
                                    </select>
                                </div>
                                <div class="balance-table">
                                    <table class="list-history">
                                        <thead></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="pagenate" id="pager_second"></div>
                            </div>
                        </div>	
                        <!-- // 입출금내역 -->
                    </div>
                    <!-- // 비트코인 -->
		        </div>
            </div>
        </div>
        
        <!-- cash deposit modal -->
        <div class="modal fade" id="depositCashModal" tabindex="-1" role="dialog" aria-labelledby="depositCashModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <h4 class="modal-title"><?php Language::langConvert($view['lang'], 'title');?></h4>
                    </div>
                    <div class="modal-body">
                        <span class="depositModalBody"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn_submit-cancel" data-dismiss="modal"><?php Language::langConvert($view['lang'], 'btnCancel');?></button>
                        <button type="button" class="btn btn-primary btn_submit" id="btnDepositCashSubmit"><?php Language::langConvert($view['lang'], 'depositButton');?></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- // cash deposit modal -->
        
        <!-- cash withdraw modal -->
        <div class="modal fade" id="withdrawCashModal" tabindex="-1" role="dialog" aria-labelledby="withdrawCashModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <h4 class="modal-title"><?php Language::langConvert($view['lang'], 'withdrawGuideTitle');?></h4>
                    </div>
                    <div class="modal-body">
                        <span class="withdrawModalBody"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn_submit-cancel" data-dismiss="modal"><?php Language::langConvert($view['lang'], 'btnCancel');?></button>
                        <button type="button" class="btn btn-primary btn_submit" id="btnWithdrawCashSubmit"><span class="withdrawCashModal_btnnm"><?php Language::langConvert($view['lang'], 'btnWithdrawalRequest');?></span></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- // cash withdraw modal -->

        <!-- coin withdraw modal -->
        <div class="modal fade" id="withdrawCoinModal" tabindex="-1" role="dialog" aria-labelledby="withdrawCoinModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <h4 class="modal-title"><?php Language::langConvert($view['lang'], 'withdrawGuideTitle');?></h4>
                    </div>
                    <div class="modal-body">
                        <span class="withdrawCoinModalBody"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn_submit-cancel" id="btnSubmitCancel" data-dismiss="modal"><?php Language::langConvert($view['lang'], 'btnCancel');?></button>
                        <button type="button" class="btn btn-primary btn_submit" id="btnWithdrawCoinSubmit"><span class="withdrawCoinModal_btnnm"><?php Language::langConvert($view['lang'], 'btnWithdrawalRequest');?></span></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- // coin withdraw modal -->
        
        <!-- CONTAINER -->
	<script src="<?=$view['url']['static']?>/assets/write/footer-menu.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    </div>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-wallet.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script src="<?=$view['url']['static']?>/assets/script/src-orgin/controller-history.js?v=<?=Language::langConvert($view['langcommon'], 'version');?>"></script>
    <script>Config.initFooterScript();</script>
    <script>
        // 최소 입금 금액
        depositCashSet.cash_min_limit = <?=$view['krw_min_limit']?>;
        // 수수료
        var withdraw_fee = <?=$view['fee']?>;
        withdrawCashSet.fee.val( withdraw_fee.formatWon() );
        <?php if($view['member']['otp_withdraw']!='Y'){?>
        withdrawCoinSet.otp_use = false;
        <?php }?>
        
        // 코인 리스트
        var coin_arr = new Array();
        // 코인 지갑 사용유무
        var coin_wallet_use = new Array();
        <?php
            foreach ($view['master'] as $key => $value) {
                $tmp = explode('_', $key);
                $cursymbol = $tmp[1];
        ?>
            coin_arr.push('<?=strtolower($cursymbol)?>');
            coin_wallet_use['<?=strtolower($cursymbol)?>'] = new Array();
            coin_wallet_use['<?=strtolower($cursymbol)?>'] = '<?=$view['master'][$key]['itWalletUse']?>';
        <?php }?>
        
        var jsondata;

        $( document ).ready(function() {
            getBankInfo();
            getWithdrawCashLimit();
            depositwait_cash_page();
        });

        function onTickerEvent(data) {
            jsondata = data;
        }
        
        function onChangeBalanceBar(assets, balance){
            var balanceBarWidth = 0.0;
            
            for(var i=0; i < balance.length; i++){
                balanceBarWidth = calfloat('ROUND', parseInt(balance[i]["price"]) / parseInt(assets) * 100, 2);
                $('.balance-bar-str-'+balance[i]['po_type']).html(balanceBarWidth+'%');
                $('.balance-bar-'+balance[i]['po_type']).css('width', balanceBarWidth+'%');
            }
        }
    </script>
</body>