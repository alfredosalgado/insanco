# Servidor web simple en PowerShell para INSANCO
param(
    [int]$Port = 8000,
    [string]$Path = "."
)

# Función para obtener el tipo MIME
function Get-MimeType {
    param([string]$Extension)
    
    switch ($Extension.ToLower()) {
        ".html" { return "text/html" }
        ".htm" { return "text/html" }
        ".css" { return "text/css" }
        ".js" { return "application/javascript" }
        ".json" { return "application/json" }
        ".png" { return "image/png" }
        ".jpg" { return "image/jpeg" }
        ".jpeg" { return "image/jpeg" }
        ".gif" { return "image/gif" }
        ".svg" { return "image/svg+xml" }
        ".ico" { return "image/x-icon" }
        ".txt" { return "text/plain" }
        default { return "application/octet-stream" }
    }
}

# Función para servir archivos
function Serve-File {
    param(
        [System.Net.HttpListenerResponse]$Response,
        [string]$FilePath
    )
    
    try {
        if (Test-Path $FilePath -PathType Leaf) {
            $content = [System.IO.File]::ReadAllBytes($FilePath)
            $extension = [System.IO.Path]::GetExtension($FilePath)
            $mimeType = Get-MimeType $extension
            
            $Response.ContentType = $mimeType
            $Response.ContentLength64 = $content.Length
            $Response.StatusCode = 200
            $Response.OutputStream.Write($content, 0, $content.Length)
        } else {
            $Response.StatusCode = 404
            $errorContent = [System.Text.Encoding]::UTF8.GetBytes("404 - Archivo no encontrado")
            $Response.OutputStream.Write($errorContent, 0, $errorContent.Length)
        }
    } catch {
        $Response.StatusCode = 500
        $errorContent = [System.Text.Encoding]::UTF8.GetBytes("500 - Error interno del servidor")
        $Response.OutputStream.Write($errorContent, 0, $errorContent.Length)
    } finally {
        $Response.OutputStream.Close()
    }
}

# Configurar el listener HTTP
$listener = New-Object System.Net.HttpListener
$listener.Prefixes.Add("http://localhost:$Port/")

try {
    $listener.Start()
    Write-Host "Servidor web iniciado en http://localhost:$Port" -ForegroundColor Green
    Write-Host "Presiona Ctrl+C para detener el servidor" -ForegroundColor Yellow
    Write-Host "Sirviendo archivos desde: $(Resolve-Path $Path)" -ForegroundColor Cyan
    
    while ($listener.IsListening) {
        $context = $listener.GetContext()
        $request = $context.Request
        $response = $context.Response
        
        $url = $request.Url.LocalPath
        Write-Host "$(Get-Date -Format 'HH:mm:ss') - $($request.HttpMethod) $url" -ForegroundColor Gray
        
        # Manejar la ruta raíz
        if ($url -eq "/" -or $url -eq "") {
            $url = "/index.html"
        }
        
        # Construir la ruta del archivo
        $filePath = Join-Path $Path $url.TrimStart('/')
        
        # Servir el archivo
        Serve-File -Response $response -FilePath $filePath
    }
} catch {
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
} finally {
    if ($listener.IsListening) {
        $listener.Stop()
    }
    Write-Host "Servidor detenido" -ForegroundColor Red
}