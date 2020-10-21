#echo "Enter your message"
#read message
#git add .
#git commit -m"${message}"
#git push -u origin develop

COMMIT_TIMESTAMP=`date +'%Y-%m-%d %H:%M:%S %Z'`

git status
git add .
git commit -m "Automated commit on ${COMMIT_TIMESTAMP}"
git push https://keeloren:Duynguyen1996@github.com/keeloren/file-management.git