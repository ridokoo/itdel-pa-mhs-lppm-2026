npm install eslint --save-dev
npx eslint --init
npm install eslint eslint-plugin-import --save-dev
npm install --save-dev eslint-import-resolver-alias

npm install pg
npm install mysql2


.vscode/settings.json
```json
{
  "editor.defaultFormatter": "dbaeumer.vscode-eslint",
  "editor.formatOnSave": true,
  "editor.codeActionsOnSave": {
    "source.fixAll.eslint": "always"
  },
  "eslint.validate": ["javascript"]
}
```

adb reverse tcp:6610 tcp:6610


curl -N -X POST "http://localhost:3000/api/v1/website" \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1aWQiOiI4MzU3ZmRhNi02N2Y3LTRhOTktOGYwMS05ODQ3ZDY5MjA1OTYiLCJpYXQiOjE3NjcxNDU0Nzd9.CM7TDJ54U3YDPGPkw6iXK_JqeGQsfZcbpa5h_VNtFyE" \
  -H "Content-Type: application/json" \
  -d '{
    "gitClone": "https://github.com/auxtern/latihan-php.git",
    "domain": "php.local",
    "target": "php",
    "dataDB": {
      "connection": "pgsql",
      "host": "host.docker.internal",
      "port": 5101,
      "database": "db_php",
      "username": "postgres",
      "password": "postgres"
    },
    "options": "test"
  }'
