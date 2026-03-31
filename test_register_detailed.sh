#!/bin/bash
base="http://172.236.19.52"
stamp=$(date +%s)
user="test${stamp}"
email="test${stamp}@example.com"
curl -s -c /tmp/reg_sess.txt -b /tmp/reg_sess.txt "$base/register" -o /tmp/register.html
curl -s -D /tmp/reg_headers.txt -o /tmp/reg_body.txt -b /tmp/reg_sess.txt -c /tmp/reg_sess.txt -X POST "$base/requests.php?f=register" \
  -H 'X-Requested-With: XMLHttpRequest' \
  --data-urlencode "username=$user" \
  --data-urlencode "email=$email" \
  --data-urlencode "password=Test1234x!" \
  --data-urlencode "confirm_password=Test1234x!" \
  --data-urlencode "gender=male" \
  --data-urlencode "accept_terms=on"
printf 'USER=%s\nEMAIL=%s\n' "$user" "$email"
echo '---HEADERS---'
cat /tmp/reg_headers.txt
echo '---BODY_LEN---'
wc -c /tmp/reg_body.txt
echo '---BODY_TEXT---'
cat /tmp/reg_body.txt
echo
echo '---BODY_HEX---'
xxd /tmp/reg_body.txt | head -20
