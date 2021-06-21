#!/usr/bin/env bash

LOG_PATH="/home/craig/Utils/speedmon/logs/speedtest.log"

if result=$(/usr/local/bin/speedtest --simple); then
  parsed_result=$(printf "${result}\"" | sed ':a;N;$!ba;s/\n/" /g' | sed 's/: /="/g')
  printf "[$(date)] ${parsed_result}\n" >> "${LOG_PATH}"
else
  if result=$(/usr/local/bin/speedtest --simple); then
    # try a second time
    parsed_result=$(printf "${result}\"" | sed ':a;N;$!ba;s/\n/" /g' | sed 's/: /="/g')
    printf "[$(date)] ${parsed_result} *2 tries\n" >> "${LOG_PATH}"
  else
    printf "[$(date)] error\n" >> "${LOG_PATH}"
    exit 1
  fi
fi
exit 0
