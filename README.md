# Claims [![](https://poggit.pmmp.io/shield.state/ClaimsPlugin)](https://poggit.pmmp.io/p/ClaimsPlugin)
<a href="https://poggit.pmmp.io/p/ClaimsPlugin"><img src="https://poggit.pmmp.io/shield.state/ClaimsPlugin"></a> [![](https://poggit.pmmp.io/shield.api/ClaimsPlugin)](https://poggit.pmmp.io/p/ClaimsPlugin)
<a href="https://poggit.pmmp.io/p/ClaimsPlugin"><img src="https://poggit.pmmp.io/shield.api/ClaimsPlugin"></a>

This plugin allows administrators to create, edit, list, and teleport to land claims on a PocketMine server. These claims have a variety of customizable flags to define what actions are permitted within the claim's boundaries.

## Features
- Claim creation with automatic name collision resolution.
- Session-based claim creation process.
- Claim management with claim editing and deletion.
- Player-specific permission system for claim flags.

## Usage

### Claim Creation
1. `/claim pos1`: Set the first position for the claim.
2. `/claim pos2`: Set the second position for the claim.
3. `/claim create [name]`: Create a claim with the specified name.

### Claim Management
- `/claim remove [name]`: Remove the claim with the specified name.
- `/claim list`: List all the claims.
- `/claim tp [name]`: Teleport to the claim with the specified name.

### Claim Flag Management
- `/claim editflags [name]`: Opens a GUI for editing claim flags.

## Installation
1. Copy the plugin `.phar` file to the `plugins` directory of your PocketMine server.
2. Restart the server to load the plugin.
3. Start using the plugin with the `/claim` command.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[Apache](LICENSE)
