#!/bin/bash
set -e
base="http://172.236.19.52"
stamp=$(date +%s)
user="test${stamp}"
email="test${stamp}@example.com"
curl -s -c /tmp/reg_sess.txt -b /tmp/reg_sess.txt "$base/register" -o /tmp/register.html
response=$(curl -s -b /tmp/reg_sess.txt -c /tmp/reg_sess.txt -X POST "$base/requests.php?f=register" \
  -H 'X-Requested-With: XMLHttpRequest' \
  --data-urlencode "username=$user" \
  --data-urlencode "email=$email" \
  --data-urlencode "password=Test1234x!" \
  --data-urlencode "confirm_password=Test1234x!" \
  --data-urlencode "gender=male" \
  --data-urlencode "accept_terms=on")
echo "USER=$user"
echo "EMAIL=$email"
echo "$response"
