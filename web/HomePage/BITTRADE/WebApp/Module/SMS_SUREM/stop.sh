#!/bin/sh
echo  "DB_EXE Client / 4.2.6"
echo  "Copyright (C) 2016, SureM Corporation., Ltd."
pid=`ps -ef | grep com.surem.dbexe_*.* | grep -v 'grep' | awk '{print $2}'`

if [ -z $pid ]; then
#-z 옵션은 null 일 때 true
  echo "Already DB_EXE Stopped."
  exit 1
else
  kill -9 $pid
fi
sleep 1
CMS_PROCESS_COUNT=`ps -ef | grep com.surem.dbexe_*.*  | grep -v 'grep' | awk '{print $2}' | wc | awk '{print $1}'`
#wc 명령은 count 를 세는 명령이므로 이렇게 하면 밑에 처럼 숫자로 표시 가능
echo "Operating Application Count : "$CMS_PROCESS_COUNT
if [ "$CMS_PROCESS_COUNT" = "0" ]; then
  echo "DB_EXE stopped."
else
  echo "DB_EXE shutting down fail."
fi

