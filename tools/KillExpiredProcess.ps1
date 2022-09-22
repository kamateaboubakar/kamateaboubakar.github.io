# Variables
$delaiMax = 3600
$processOwner = "GSERPAM2T\rpa_agent"
$processNames = @("chrome", "chromedriver", "cmd", "robot", "python", "php")

# GO
$currentDate = Get-Date
foreach ($processName in $processNames) {
	$processList = (Get-Process $processName -IncludeUserName | select id, name, Username, starttime) 2>$null
	foreach($process in $processList) {
		$delaiExec = NEW-TIMESPAN -Start $process.StartTime -End $currentDate
		if($process.Username -eq $processOwner -and $delaiExec.TotalSeconds -gt $delaiMax)
		{
			$process.Name + " expired : " + $process.StartTime + " (" + $delaiExec.TotalSeconds + " seconds)"
			taskkill /PID $process.Id /F
		}
		else
		{
			$process.Name + "@" + $process.Username + " running (" + $delaiExec.TotalSeconds + " seconds)"
		}
	}
}