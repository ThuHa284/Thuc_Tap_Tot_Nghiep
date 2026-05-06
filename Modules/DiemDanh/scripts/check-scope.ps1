param(
    [string]$BaseRef = "origin/main",
    [string[]]$AdditionalAllowedPaths = @("Modules/DiemDanh/scripts/check-scope.ps1")
)

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

function Get-Lines([string[]]$RawLines) {
    if (-not $RawLines) {
        return @()
    }

    return $RawLines |
        Where-Object { $_ -and $_.Trim().Length -gt 0 } |
        ForEach-Object { $_.Trim() }
}

function Get-ChangedFilesFromRange([string]$FromRef, [string]$ToRef) {
    $raw = git diff --name-only --diff-filter=ACMR "$FromRef..$ToRef"
    return Get-Lines $raw
}

function Get-WorkingTreeFiles() {
    $unstaged = Get-Lines (git diff --name-only --diff-filter=ACMR)
    $staged = Get-Lines (git diff --name-only --cached --diff-filter=ACMR)
    $untracked = Get-Lines (git ls-files --others --exclude-standard)

    return @($unstaged + $staged + $untracked) | Select-Object -Unique
}

function IsAllowedPath([string]$Path) {
    $normalized = $Path.Replace("\", "/")
    if ($normalized.StartsWith("Modules/DiemDanh/")) {
        return $true
    }

    foreach ($allowed in $AdditionalAllowedPaths) {
        $candidate = $allowed.Replace("\", "/")
        if ($normalized -eq $candidate) {
            return $true
        }
    }

    return $false
}

try {
    git rev-parse --is-inside-work-tree *> $null
} catch {
    Write-Host "[FAIL] Day khong phai git repository." -ForegroundColor Red
    exit 2
}

try {
    $mergeBase = (git merge-base HEAD $BaseRef).Trim()
    if (-not $mergeBase) {
        throw "Khong tim thay merge-base."
    }
} catch {
    Write-Host "[FAIL] Khong the tinh merge-base voi '$BaseRef'." -ForegroundColor Red
    Write-Host "Goi y: chay 'git fetch origin' hoac doi BaseRef."
    exit 2
}

$branchChanges = Get-ChangedFilesFromRange $mergeBase "HEAD"
$workingTreeChanges = Get-WorkingTreeFiles

$allChanges = @($branchChanges + $workingTreeChanges) | Select-Object -Unique

if (-not $allChanges -or $allChanges.Count -eq 0) {
    Write-Host "[PASS] Khong co thay doi nao de kiem tra."
    exit 0
}

$outsideScope = $allChanges | Where-Object { -not (IsAllowedPath $_) }

if ($outsideScope.Count -gt 0) {
    Write-Host "[FAIL] Phat hien file ngoai pham vi Modules/DiemDanh/: " -ForegroundColor Red
    $outsideScope | Sort-Object | ForEach-Object { Write-Host " - $_" }
    Write-Host ""
    Write-Host "File hop le hien tai van co trong module:"
    $allChanges |
        Where-Object { IsAllowedPath $_ } |
        Sort-Object |
        ForEach-Object { Write-Host " + $_" }
    exit 1
}

Write-Host "[PASS] Tat ca thay doi deu nam trong Modules/DiemDanh/." -ForegroundColor Green
exit 0
