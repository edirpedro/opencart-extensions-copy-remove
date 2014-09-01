# OpenCart Extensions Copy & Remove

Use this script to install and remove extensions easely in OpenCart.

## How to use

1. Create a folder like `/extensions` in your OpenCart development installation;
2. Put in this folder the modules you want to manage. The module is based in a folder like `/sample-module` and their files like `/admin`, `/catalog`, `/vqmod` and others;
3. Open the Tool going to `your.local.domain/extensions/tools.php`;
4. Select an extension;
5. Choose what to do `Check`, `Install` or `Remove`.

## Actions
	
- **Check:** Tell you if the extension is already installed and alert you if the extension will overwrite OpenCart core files in your store.
- **Install:** Copy the files to your installation.
- **Remove:** Remove extension files from your installation based on the files found in the module folder you set.
