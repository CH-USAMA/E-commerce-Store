#!/usr/bin/env bash
#
# Read-only pre-deploy check. Changes nothing, locally or on the server.
#
# Several Claude sessions and a server-side collaborator work on this project at the same
# time, so both the local tree and live can move mid-task. This prints what a deploy would
# actually ship *right now*, rather than what you assumed ten minutes ago.
#
#   ./scripts/deploy-preflight.sh
#
# Exit status: 0 = clear to deploy, 1 = something needs a human decision.
# See docs/memory/DEPLOYMENT.md §2.

set -uo pipefail

LIVE_SSH="ssh -p 65002 u175002435@82.25.96.26"
LIVE_DIR='~/domains/jabulanigroupofcompanies.co.za/public_html/store'

stop=0
note() { printf '\n\033[1m── %s\033[0m\n' "$1"; }
warn() { printf '\033[33m  ! %s\033[0m\n' "$1"; stop=1; }

note "Local — dev repo, branch $(git rev-parse --abbrev-ref HEAD)"
git log --oneline -3

dirty=$(git status --porcelain)
if [ -n "$dirty" ]; then
    printf '%s\n' "$dirty"
    warn "Working tree is not clean. Stage BY NAME: 'git add .' would ship all of the above."
fi

note "Live"
if ! live=$($LIVE_SSH "cd $LIVE_DIR && git log --oneline -3 && echo '--- status ---' && git status --porcelain" 2>&1); then
    printf '%s\n' "$live"
    warn "Could not reach live over SSH — cannot verify what you would be deploying onto."
    exit 1
fi
printf '%s\n' "$live"

# Untracked .env.backup-* files are expected and stay untracked. Anything else is
# undeclared production drift — Rule 11 before you touch git there.
if printf '%s' "$live" | sed -n '/--- status ---/,$p' \
        | grep -qvE '^(--- status ---|\?\? \.env\.backup-|[[:space:]]*$)'; then
    warn "Live has drift beyond untracked .env.backup-* — handle it per Rule 11 first."
fi

note "Payload — every commit that would go live, not just yours"
if ! git fetch --quiet live main; then
    warn "Could not fetch live/main; payload below may be stale."
fi

commits=$(git log --oneline live/main..HEAD)
if [ -z "$commits" ]; then
    echo "  (nothing to deploy — live is level with you)"
else
    printf '%s\n' "$commits"
    echo
    git diff --stat live/main..HEAD
    echo
    echo "  Anything above you did not intend? Stop and ask the owner."
fi

migrations=$(git diff --name-only live/main..HEAD -- database/migrations)
if [ -n "$migrations" ]; then
    note "Migrations in this payload"
    printf '%s\n' "$migrations"
    warn "Run 'php artisan migrate --force' on live after the merge."
fi

if [ "$stop" -eq 0 ]; then
    note "Clear to deploy."
else
    note "Read the warnings above before deploying."
fi

exit "$stop"
