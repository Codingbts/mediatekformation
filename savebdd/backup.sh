#!/bin/sh
DATE=`date -I`
find /home/codingbts/savebdd/bdd* -mtime -1 -exec rm {} \;
mysqldump -u root -pvmAqGCAeUUxWRoPPowwjhOkzqQQxzGVk --databases railway --single-transaction | gzip > /home/[logincompte]/savebdd/bddbackup_${DATE}.sql.gz
