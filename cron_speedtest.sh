#!/usr/bin/env bash

max_tries=5
LOG_PATH="/home/craig/Utils/speedmon/logs/speedtest.log"

good_result=false
try_count=0
while [[ "${try_count}" -lt "${max_tries}" ]]; do
  try_count="$((${try_count} + 1))"
  if result=$(/usr/local/bin/speedtest --simple); then
    good_result=true
    break;
  fi
done

if [[ "$good_result" == true ]]; then
  parsed_result=$(printf "${result}\"" | sed ':a;N;$!ba;s/\n/" /g' | sed 's/: /="/g')
  printf "[$(date)] ${parsed_result} Tries=\"${try_count}\"\n" >> "${LOG_PATH}"
else
  printf "[$(date)] error Tries=\"${try_count}\"\n" >> "${LOG_PATH}"
  exit 1
fi
exit 0
