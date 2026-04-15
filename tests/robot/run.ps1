param(
    [string]$PhpPath = "C:\MAMP\bin\php\php8.3.1\php.exe",
    [string]$ProjectRoot = "C:\MAMP\htdocs\legalhelp2",
    [int]$Port = 8088
)

$webRoot = Join-Path $ProjectRoot "legalhelp"
$resultsDir = Join-Path $ProjectRoot "tests\robot\results"
$server = $null

try {
    $server = Start-Process -FilePath $PhpPath -ArgumentList "-S", "127.0.0.1:$Port", "-t", $webRoot -PassThru
    Start-Sleep -Seconds 2
    py -3.10 -m robot -d $resultsDir (Join-Path $ProjectRoot "tests\robot\smoke.robot")
}
finally {
    if ($server) {
        Stop-Process -Id $server.Id -ErrorAction SilentlyContinue
    }
}
