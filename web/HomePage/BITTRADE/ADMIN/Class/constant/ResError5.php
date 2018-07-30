<?php
/**
* Description of BaseDAO
* @author admin@bugnote.net
* @date 2013-08-07
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
* @example * 에러코드 정의
* -700대이하 파라미터 오류
* -600대이하 뷰관련 오류
* -100대이하 DB오류
*/

class ResError {
    const no = 1;
    const none = 1;
    const ok = -100;

    //DB관련

    const dbPrepare = -21;
    const dbConn = -20;
    const noResult = 0; //결과 값이 없음
    const noResultById = -90; //결과 값이 없음

    //파라미터 관련
    const paramEmptyGet = -100;
    const paramEmptyPost = -101;
    const paramEmptyFile = -105;
    const paramUnMatchPri = -160; //프라임키와 파라미터 언일치
    const paramRequiredValue = -180; //필수입력 란에 빠진 항목

    //접근관련
    const accessIp = -200; //접근 불가 아이피
    const access = -210; //접근 불가

    //권한관련
    const auth = -800;

    //업뎃관련
    const token = -900;
    const writeSec = -910;
    const referer = -930;
    const unique = -940; //중복데이터 에러

    const captcha = -950; //자동등록방지코드
    const password = -951; //비밀번호
    const chartype = -990; //형식오류
    const exception = -999;
}

?>
