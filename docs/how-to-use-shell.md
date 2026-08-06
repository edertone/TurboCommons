# How to use TurboCommons shell scripts

The shell package provides utilities for Ubuntu and other Bash-compatible
environments. Import the utilities from a fixed Git commit:

```bash
# Load common tools
source <(curl -fsSL "https://raw.githubusercontent.com/edertone/turbocommons/<sha>/packages/turbocommons-shell/ubuntu/script-common-tools.sh")

sct_user_must_exist "username" "username must exist!"
```

The package also includes server-monitoring and SSH utilities. See the
[shell package README](../packages/turbocommons-shell/README.md) and the
[`ubuntu`](../packages/turbocommons-shell/ubuntu/) directory for the available
 scripts.
