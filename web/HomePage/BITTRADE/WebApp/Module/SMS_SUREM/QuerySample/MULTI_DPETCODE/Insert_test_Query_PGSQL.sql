INSERT INTO SUREDATA
(   SEQNO, INTIME, USERCODE, REQNAME, REQPHONE, CALLNAME, CALLPHONE, SUBJECT, MSG, REQTIME, RESULT, KIND, DEPTCODE, TEMPLATECODE)
VALUES 
(
nextval('SUREDATA_seqno')
, TO_CHAR(now(), 'YYYYMMDDHH24MISS')
, 'xxx'           -- usercode (surem 아이디)
, '전송자'        -- 회신자명
, '0215884640'    -- 회신자 번호
, '받는자'        -- 수신자명
, '01012345678'   -- 수신자 번호
, '제목입니다'    -- MMS 제목 (sms일 땐 ''로 해도 됨)
, '테스트 개행문자' || chr(13) || chr(10) || 'test' || chr(13) || chr(10) ||'마지막줄'    --문자 내용
, '00000000000000'  -- 예약문자 전송시 'YYYYmmddHHMMss', 즉시전송시 '00000000000000'  
, '0'   -- Default = 0, ( 0 : 즉시전송(숫자 0) R : 예약전송 )
, 'M' -- M : MMS, S : SMS, I : 국제문자, L : 국제 MMS
, 'XX-XX-XX' -- (surem deptcode)
, '' -- TEMPLATE CODE (알림톡전송시에만)
)
