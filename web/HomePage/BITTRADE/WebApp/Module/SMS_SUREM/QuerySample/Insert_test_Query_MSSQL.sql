INSERT INTO SUREData
(   INTIME, USERCODE, REQNAME, REQPHONE, CALLNAME, CALLPHONE, SUBJECT, MSG, REQTIME, RESULT, KIND, TEMPLATECODE )
VALUES 
(

 CONVERT(VARCHAR(10),GETDATE(),112) + 
   REPLACE(CONVERT(VARCHAR(10),GETDATE(),108),':','') 
, 'usercode'           -- usercode (surem ���̵�)
, '������'        -- ȸ���ڸ�
, '0215884640'    -- ȸ���� ��ȣ
, '�޴���'        -- �����ڸ�
, '01012345678'   -- ������ ��ȣ
, '�����Դϴ�'    -- MMS ���� (sms�� �� ''�� �ص� ��)
, '�׽�Ʈ ����'+CHAR(10)+'��������'    --���� ����
, '00000000000000'  -- ���๮�� ���۽� 'YYYYmmddHHMMss', ������۽� '00000000000000'  
, '0'   -- Default = 0, ( 0 : �������(���� 0) R : �������� )
, 'S' -- M : MMS, S : SMS, I : ��������, L : ���� MMS
, '' -- TEMPLATE CODE (�˸������۽ÿ���)
)