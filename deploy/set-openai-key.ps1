param(
    [string]$ProjectRoot = (Resolve-Path (Join-Path $PSScriptRoot "..")).Path
)

$envPath = Join-Path $ProjectRoot ".env"
$templatePath = Join-Path $ProjectRoot ".env.production.example"

if (-not (Test-Path $envPath)) {
    if (-not (Test-Path $templatePath)) {
        throw "Impossible de trouver .env ou .env.production.example"
    }

    Copy-Item -Path $templatePath -Destination $envPath
}

$secureKey = Read-Host "Colle ta cle OpenAI API puis appuie sur Entree" -AsSecureString
$bstr = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($secureKey)

try {
    $apiKey = [Runtime.InteropServices.Marshal]::PtrToStringBSTR($bstr)
}
finally {
    [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($bstr)
}

if ([string]::IsNullOrWhiteSpace($apiKey)) {
    throw "La cle OpenAI est vide. Aucune modification effectuee."
}

$lines = Get-Content -Path $envPath
$updated = $false

$lines = $lines | ForEach-Object {
    if ($_ -match "^OPENAI_API_KEY=") {
        $updated = $true
        "OPENAI_API_KEY=$apiKey"
    }
    else {
        $_
    }
}

if (-not $updated) {
    $lines += "OPENAI_API_KEY=$apiKey"
}

Set-Content -Path $envPath -Value $lines -Encoding UTF8
Write-Host "Cle OpenAI enregistree dans $envPath"
