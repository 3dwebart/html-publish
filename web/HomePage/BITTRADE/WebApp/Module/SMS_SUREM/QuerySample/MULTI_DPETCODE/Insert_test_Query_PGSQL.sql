INSERT INTO SUREDATA
(   SEQNO, INTIME, USERCODE, REQNAME, REQPHONE, CALLNAME, CALLPHONE, SUBJECT, MSG, REQTIME, RESULT, KIND, DEPTCODE, TEMPLATECODE)
VALUES 
(
nextval('SUREDATA_seqno')
, TO_CHAR(now(), 'YYYYMMDDHH24MISS')
, 'xxx'           -- usercode (surem ���̵�)
, '������'        -- ȸ���ڸ�
, '0215884640'    -- ȸ���� ��ȣ
, '�޴���'        -- �����ڸ�
, '01012345678'   -- ������ ��ȣ
, '�����Դϴ�'    -- MMS ���� (sms�� �� ''�� �ص� ��)
, '�׽�Ʈ ���๮��' || chr(13) || chr(10) || 'test' || chr(13) || chr(10) ||'��������'    --���� ����
, '00000000000000'  -- ���๮�� ���۽� 'YYYYmmddHHMMss', ������۽� '00000000000000'  
, '0'   -- Default = 0, ( 0 : �������(���� 0) R : �������� )
, 'M' -- M : MMS, S : SMS, I : ��������, L : ���� MMS
, 'XX-XX-XX' -- (surem deptcode)
, '' -- TEMPLATE CODE (�˸������۽ÿ���)
)
