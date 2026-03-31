#!/bin/bash
base="http://172.236.19.52"
stamp=$(date +%s)
user="quick${stamp}"
email="quick${stamp}@example.com"
curl -s -m 10 -c /tmp/reg_quick_sess.txt -b /tmp/reg_quick_sess.txt "$base/register" -o /tmp/register_quick.html
curl -s -m 15 -D /tmp/reg_quick_headers.txt -o /tmp/reg_quick_body.txt -b /tmp/reg_quick_sess.txt -c /tmp/reg_quick_sess.txt -X POST "$base/requests.php?f=register" \
  -H 'X-Requested-With: XMLHttpRequest' \
  --data-urlencode "username=$user" \
  --data-urlencode "email=$email" \
  --data-urlencode "password=Test1234x!" \
  --data-urlencode "confirm_password=Test1234x!" \
  --data-urlencode "gender=male" \
  --data-urlencode "accept_terms=on"
printf 'USER=%s\nEMAIL=%s\n' "$user" "$email"
echo '---HEADERS---'
cat /tmp/reg_quick_headers.txt
echo '---BODY---'
cat /tmp/reg_quick_body.txt
