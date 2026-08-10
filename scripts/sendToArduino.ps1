# sendToArduino.ps1
$port = new-Object System.IO.Ports.SerialPort COM7,9600,None,8,one
$port.Open()
Start-Sleep -Seconds 2
$port.WriteLine("OPEN")
Start-Sleep -Milliseconds 200
$port.Close()