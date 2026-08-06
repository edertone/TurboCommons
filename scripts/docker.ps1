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

$dockerCommand = @('compose', 'run', '--rm', 'toolbox')

switch ($Task) {
    'build' { $command = @('npm', 'run', 'build') }
    'test' { $command = @('npm', 'test') }
    'lint' { $command = @('npm', 'run', 'lint') }
    'package' { $command = @('npm', 'run', 'package') }
    'ci' { $command = @('npm', 'run', 'ci') }
    'shell' { $command = @('bash') }
}

& docker @dockerCommand ($command + $Arguments)
exit $LASTEXITCODE
