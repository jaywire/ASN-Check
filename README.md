# RBL Checker

This PHP script was an internal project to run lookups on IP addresses accessing a platform and store the relevant ASN and routes advertised by said ASN. I made it into a CLI tool for our internal support staff to easily use and understand for diagnosing low-level customer facing issues. 

If you are unsure what an ASN is, this tool likely won't be useful to you. 
<br><br>

## To Use:

This script utilized CLI args. Make sure the script is executable and you have the PHP interpreter installed. 

1. sudo chmod +x asncheck.php

And then, run the file:

2. ./asncheck.php 

This script will also watch for subnets and adjust it down to the first IP in the range, as the CYMRU DNS method doesn't support subnets.
<br>

**Arguments** 

This is fairly straightforward. 

Supply the script an IPv4 address: --ip=1.1.1.1

This will display all relevant ASN information for that IP. 

If you'd like to see the routes that this ASN advertises, add --routes to the end. 

Example: 

```
./asncheck.php --ip=1.1.1.1 --routes
```
