$ProgressPreference = 'SilentlyContinue'
$dest = "hostinger_deploy_akash_events.zip"
if (Test-Path $dest) { Remove-Item $dest -Force }

Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

$zipStream = [System.IO.File]::Open($dest, [System.IO.FileMode]::Create)
$archive = New-Object System.IO.Compression.ZipArchive($zipStream, [System.IO.Compression.ZipArchiveMode]::Create)

$root = (Get-Item .).FullName
$exclude = @('.git', 'tests', 'scratch', 'storage\backups', '.system_generated', 'hostinger_deploy_akash_events.zip', 'vercel.json')

Get-ChildItem -Path . -Recurse -Force | ForEach-Object {
    $fullPath = $_.FullName
    $relPath = $fullPath.Substring($root.Length).TrimStart('\', '/')
    
    # Check exclusions
    $skip = $false
    foreach ($ex in $exclude) {
        if ($relPath -eq $ex -or $relPath.StartsWith($ex + '\') -or $relPath.StartsWith($ex + '/')) {
            $skip = $true
            break
        }
    }
    
    if ($relPath.StartsWith('scratch\') -or $relPath.StartsWith('tests\') -or $relPath.StartsWith('.git\') -or $relPath.StartsWith('.system_generated\')) {
        $skip = $true
    }

    if (-not $skip -and -not $_.PSIsContainer) {
        # Normalize to Linux-standard forward slash
        $entryName = $relPath.Replace('\', '/')
        [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($archive, $fullPath, $entryName, [System.IO.Compression.CompressionLevel]::Optimal) | Out-Null
    }
}

# Add empty directories for storage
@('storage/cache', 'storage/logs', 'storage/uploads', 'storage/backups') | ForEach-Object {
    $archive.CreateEntry($_ + '/') | Out-Null
}

$archive.Dispose()
$zipStream.Dispose()

$info = Get-Item $dest
Write-Host "Linux-compatible Hostinger ZIP created successfully:" $info.Name "(" ([math]::Round($info.Length / 1MB, 2)) "MB)"
