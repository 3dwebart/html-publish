INSERT INTO SUREData
(   INTIME, USERCODE, REQNAME, REQPHONE, CALLNAME, CALLPHONE, SUBJECT, MSG, REQTIME, RESULT, KIND, TEMPLATECODE)
VALUES 
(

CAST(DATE_FORMAT(NOW(),'%Y%m%d%H%i%s') As char)
, 'usercode'           -- usercode (surem ���̵�)
, '������'        -- ȸ���ڸ�
, '0215884640'    -- ȸ���� ��ȣ
, '�޴���'        -- �����ڸ�
, '01012345678'   -- ������ ��ȣ
, '�����Դϴ�'    -- MMS ���� (sms�� �� ''�� �ص� ��)
, '�׽�Ʈ ����\r��������'    -- ���� ����
, '00000000000000'  -- ���๮�� ���۽� 'YYYYmmddHHMMss', ������۽� '00000000000000'  
, '0'   -- Default = 0, ( 0 : �������(���� 0) R : �������� )
, 'S' -- M : MMS, S : SMS, I : ��������, L : ���� MMS
, '' -- TEMPLATE CODE (�˸������۽ÿ���)
)