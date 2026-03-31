#!/bin/bash
# Login and get home page to check CSS
echo "=== Logging in ==="
curl -s -c /tmp/test_cookies.txt \
  -X POST \
  -H "X-Requested-With: XMLHttpRequest" \
  -H "Referer: http://172.236.19.52/" \
  -d "username=facesofnaija&password=Naija2026!" \
  "http://172.236.19.52/requests.php?f=login"

echo ""
echo "=== Session cookie ==="
cat /tmp/test_cookies.txt | grep -i "PHPSESSID\|user_id\|_us"

echo ""
echo "=== Home page first 500 chars ==="
curl -s -b /tmp/test_cookies.txt "http://172.236.19.52/" | head -c 500

echo ""
echo "=== Checking for DOCTYPE and style.css in home page ==="
home_content=$(curl -s -b /tmp/test_cookies.txt "http://172.236.19.52/")
echo "$home_content" | grep -i "DOCTYPE\|style\.css\|welcome\.css" | head -5
