# Local dev launcher - do not deploy. Uses the local XAMPP "nysefinance" DB instead of .env's production DB.
$env:DB_DATABASE = "nysefinance"
$env:DB_USERNAME = "root"
$env:DB_PASSWORD = "null"   # Laravel reads "null" as an empty password (PowerShell deletes vars set to "")
$env:PUBLIC_HOME = "true"   # show the new Vue homepage instead of redirecting to login
Set-Location $PSScriptRoot   # getImage() checks files relative to the web root
Write-Host "NyseFinance running at http://127.0.0.1:8000  (Ctrl+C to stop)"
php -S 127.0.0.1:8000 -t $PSScriptRoot "$PSScriptRoot\dev-router.php"
