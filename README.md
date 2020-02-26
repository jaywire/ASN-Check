# ASN Checker

This PHP script was an internal project to run lookups on IP addresses accessing a platform and store the relevant ASN and routes advertised by said ASN. I made it into a CLI tool for myself and our internal support staff to easily use and understand for diagnosing low-level customer facing issues. 

If you are unsure what an ASN is, this tool likely won't be useful to you. 

Before any checks are done, this script will look for Bogon IP's. This will prevent you from running a query if the IP is identified on the bogon query. This is useful for mass queries so you don't waste whois lookups on a bogon. 

Note that RADb's whois service doesn't like mass queries. If you're going to run a lot of queries, invoke a telnet session or download a local copy of the database and parse the data. This script could easily be modified to do either of those. (Although managing a telnet session in PHP isn't exactly elegant - I'd do it in a shell script and pass the data back and forth using std_in and std_out)

For more information, see here: https://www.radb.net/query/help

There is no concern with team-cymru as we're using DNS queries. DNS queries are cached and don't build/tear down a session for each call since, you know, UDP is stateless and whatnot.
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
