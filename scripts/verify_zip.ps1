Add-Type -AssemblyName System.IO.Compression.FileSystem
$zip = [System.IO.Compression.ZipFile]::OpenRead('hostinger_deploy_akash_events.zip')
$entries = $zip.Entries | Select-Object -ExpandProperty FullName
Write-Host "Total entries in ZIP:" $entries.Count
Write-Host "`nSample Key Files:"
$entries | Where-Object { $_ -match '^(index\.php|\.htaccess|app/Config/config\.php|database/akash_event_management\.sql|admin/login\.php|public/index\.php|public/assets/images/brand/logo\.png)' }
$zip.Dispose()

