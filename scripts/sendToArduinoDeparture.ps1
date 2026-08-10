# sendToArduinoDeparture.ps1
$port = new-Object System.IO.Ports.SerialPort COM8,9600,None,8,one
$port.Open()
$port.WriteLine("OPENDEPARTURE")
Start-Sleep -Milliseconds 200
$port.Close()