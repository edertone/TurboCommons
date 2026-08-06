[CmdletBinding()]
param(
    [Parameter(Position = 0)]
    [ValidateSet('build', 'test', 'lint', 'package', 'ci', 'shell')]
    [string]$Task = 'ci',

    [Parameter(ValueFromRemainingArguments = $true)]
    [string[]]$Arguments = @()
)

$ErrorActionPreference = 'Stop'
Set-Location (Join-Path $PSScriptRoot '..')

$dockerCommand = @('compose', '-f', 'docker-compose.yaml', 'run', '--rm', '--build', 'toolbox')

switch ($Task) {
    'build' { $command = @('build') }
    'test' { $command = @('test') }
    'lint' { $command = @('lint') }
    'package' { $command = @('package') }
    'ci' { $command = @('ci') }
    'shell' { $command = @('bash') }
}

& docker @dockerCommand ($command + $Arguments)
exit $LASTEXITCODE
