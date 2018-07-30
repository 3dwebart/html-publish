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
    public const no = 1;
    public const none = 1;
    public const ok = -100;

    //DB관련

    public const dbPrepare = -21;
    public const dbConn = -20;
    public const noResult = 0; //결과 값이 없음
    public const noResultById = -90; //결과 값이 없음

    //파라미터 관련
    public const paramEmptyGet = -100;
    public const paramEmptyPost = -101;
    public const paramEmptyFile = -105;
    public const paramUnMatchPri = -160; //프라임키와 파라미터 언일치
    public const paramRequiredValue = -180; //필수입력 란에 빠진 항목

    //접근관련
    public const accessIp = -200; //접근 불가 아이피
    public const access = -210; //접근 불가

    //권한관련
    public const auth = -800;

    //업뎃관련
    public const token = -900;
    public const writeSec = -910;
    public const referer = -930;
    public const unique = -940; //중복데이터 에러

    public const captcha = -950; //자동등록방지코드
    public const password = -951; //비밀번호
    public const chartype = -990; //형식오류
    public const exception = -999;
}

?>
