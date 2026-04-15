param(
    [string]$ProjectRoot = (Resolve-Path (Join-Path $PSScriptRoot "..")).Path,
    [switch]$IncludeEnv
)

$buildDir = Join-Path $ProjectRoot "build"
$packageRoot = Join-Path $buildDir "legalhelp-deploy"
$zipPath = Join-Path $buildDir "legalhelp-deploy.zip"

$resolvedProject = (Resolve-Path $ProjectRoot).Path
$resolvedBuild = if (Test-Path $buildDir) { (Resolve-Path $buildDir).Path } else { $buildDir }

if (-not (Test-Path $buildDir)) {
    New-Item -ItemType Directory -Path $buildDir | Out-Null
}

if (Test-Path $packageRoot) {
    $resolvedPackage = (Resolve-Path $packageRoot).Path
    if (-not $resolvedPackage.StartsWith((Resolve-Path $buildDir).Path)) {
        throw "Securite: refus de supprimer un dossier hors de build/"
    }
    Remove-Item -LiteralPath $packageRoot -Recurse -Force
}

if (Test-Path $zipPath) {
    Remove-Item -LiteralPath $zipPath -Force
}

New-Item -ItemType Directory -Path $packageRoot | Out-Null

$itemsToCopy = @(
    "legalhelp",
    "storage",
    "index.php",
    ".htaccess",
    ".env.production.example",
    "README_DEPLOIEMENT.md"
)

if ($IncludeEnv) {
    $itemsToCopy += ".env"
}

foreach ($item in $itemsToCopy) {
    $source = Join-Path $resolvedProject $item
    if (-not (Test-Path $source)) {
        continue
    }

    $destination = Join-Path $packageRoot $item

    if ((Get-Item $source).PSIsContainer) {
        Copy-Item -Path $source -Destination $destination -Recurse
    }
    else {
        Copy-Item -Path $source -Destination $destination
    }
}

$cleanupPatterns = @(
    "legalhelp\.git",
    "legalhelp\base de donne importantt!!",
    "storage\chat_history\*.json",
    "storage\contact\*.log",
    "storage\rate_limits\*.json"
)

foreach ($pattern in $cleanupPatterns) {
    $target = Join-Path $packageRoot $pattern
    Get-ChildItem -Path $target -Force -ErrorAction SilentlyContinue | ForEach-Object {
        Remove-Item -LiteralPath $_.FullName -Recurse -Force
    }
}

Compress-Archive -Path (Join-Path $packageRoot "*") -DestinationPath $zipPath -Force

Write-Host "Paquet cree: $zipPath"
if (-not $IncludeEnv) {
    Write-Host "Note: .env n'est pas inclus. Utilise -IncludeEnv uniquement pour un paquet prive destine au serveur."
}
