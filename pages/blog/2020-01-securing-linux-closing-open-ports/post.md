---
title: "Securing Linux: Closing Open Ports"
date: "2020-01-10"
tags: [linux]
---

One important aspect in securing a Linux system is closing any open TCP ports which don't need to be publicly accessible. This reduces the attack surface that the system exposes for malicious actors. In case a service needs to be accessed only locally, a good practice is to _bind_ it on the local loopback IP address (127.0.0.1).

In this post we are going to take a look at a standard [LAMP](https://en.wikipedia.org/wiki/LAMP_%28software_bundle%29) stack which is running the [Apache](http://httpd.apache.org/) web server and [MariaDB](https://mariadb.org/) storage engine. We assume this is a development machine and these services do not need to be publicly accessible.

Depending on your Linux distribution, you may have either the `ss` or `netstat` tool for listing open ports. Lets look at the `ss` tool first. We can list open TCP ports by using this command:

```
$ ss -lt
```

Where `l` switch stands for _listening_ and `t` stands for TCP. We should get output which is something like this:

```
State    Recv-Q   Send-Q     Local Address:Port       Peer Address:Port   Process
LISTEN   0        0                      *:mysql                 *:*
LISTEN   0        0                      *:http                  *:*
```

The `*` tells us that these services are listening for connection from any IP address. You can also add the `-n` flag to the command to show the numeric values for the ports.

We can also use the `netstat` command to get a similar output, which conveniently uses the same flags as discussed above:

```
$ netstat -lt
```

Which should give us something like this:

```
Active Internet connections (only servers)
Proto Recv-Q Send-Q Local Address           Foreign Address         State
tcp6       0      0 [::]:mysql              [::]:*                  LISTEN
tcp6       0      0 [::]:www-http           [::]:*                  LISTEN
```

Of course you may have many other services here as well, but make sure to close everything which does not need to be open. We want to stay safe, after all. Next we will look at how to edit the configuration files of these services to fix our nasty little security vulnerability.

Lets start with Apache first. Generally all system-wide configuration files are located in the `/etc` directory on Linux systems. The exact location will differ a little based in your distro. Mine is in `/etc/httpd/conf/httpd.conf`. It contains a `Listen` directive which we can edit for this purpose:

```
Listen 80
```

We will just change it to:

```
Listen 127.0.0.1:80
```

Here is a link to the relevant section in Apache [documentation](https://httpd.apache.org/docs/2.4/bind.html).

The configuration for MariaDB is located in `/etc/my.cnf.d/server.cnf` on my machine. Here we need to add (or uncomment) a `bind-address` directive:

```
bind-address=127.0.0.1
```

More information can be found in MariaDB [documentation](https://mariadb.com/kb/en/configuring-mariadb-for-remote-client-access/). Now we will need to restart the services in order for the changes to come into effect. My distro uses _systemd_ for this:

```
# systemctl restart httpd.service
# systemctl restart mariadb.service
```

Now we can run the `ss` and `netstat` commands again to confirm the change:

```
$ ss -lt
State    Recv-Q   Send-Q     Local Address:Port       Peer Address:Port   Process
LISTEN   0        0              127.0.0.1:mysql           0.0.0.0:*
LISTEN   0        0              127.0.0.1:http            0.0.0.0:*

$ netstat -lt
Active Internet connections (only servers)
Proto Recv-Q Send-Q Local Address           Foreign Address         State
tcp        0      0 localhost:mysql         0.0.0.0:*               LISTEN
tcp        0      0 localhost:www-http      0.0.0.0:*               LISTEN
```

Indeed, both services are bound only to the loopback address and do not answer to connection attempts from other IP addresses. That's it for now, hopefully this was of some use to you!
