SAMBAadmin
=======

It's a fork of <https://github.com/lbrayner/htadmin>, whose upstream is
[HTAdmin](https://github.com/soster/htadmin).

**SAMBAadmin** is a simple ~~.htaccess and .htpasswd~~ **Samba** password manager
in PHP with a nice frontend (based on bootstrap). It's intended to ~~secure a
folder of plain html files with multiple users~~ run `smbpasswd`, `pdbedit`, etc.
with `sudo`. The admin has to create a user (not before creating a UNIX user),
but every user can change his password by himself using a self service area. It
is also possible to send a password reset mail.

For **apache** to be able to perform the required tasks the `www-data` user
needs to run these commands with `sudo` passwordless:

- `pdbedit -L` (as `root`)
- `smbpasswd -s -a <USERNAME>` (as `root`)
- `smbpasswd -s <USERNAME>` (as `root`)
- `smbpasswd -x <USERNAME>` (as `root`)
- `smbpasswd -s` (as any Samba user)
- `net sam set pwdmustchangenow <USERNAME> yes` (as `root`)

Consider a solution similar to <https://github.com/lbrayner/safe_smbpasswd>.

It comes with a preconfigured Vagrant / Puppet VM, so you don't have to install a LAMP stack locally for testing.

You find the application in `sites/html/htadmin`.

![Screenshot](screenshot.png "Screenshot")

Just install vagrant and virtual box and type

`vagrant up`
 
to start the vm. After startup point your browser to:

<http://localhost/htadmin/>

Standard access: admin / admin, make sure to change that in your `...config/config.ini`. You have to enter a hashed password, there is a tool for its generation included in the webapp:

<http://localhost/htadmin/adminpwd.php>

the .htaccess and .htpasswd files are configured for this folder:

<http://localhost/test/>

Uses the following libraries:

<https://github.com/PHPMailer/PHPMailer>


Enjoy!
