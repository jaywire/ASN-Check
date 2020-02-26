#!/usr/bin/php
<?php
/* 
There is a really odd line break on the first item of the array "$routeArrPre". From these results the only way to get rid of it was to do a str_replace 
on PHP_EOL and then shift it to the bottom of the array and pop it off.

Due to the unknown variable of returned routes, i'm not using array_shift since it re-indexes the entire array every time. the complexity of array_pop() is O(1).
the complexity of array_shift() is O(n). due to the possibility this could be an automated process, i want it to be as fast as possible.
*/

$routeArr = [];
$ipRange = [];
$ipArg = getopt(null, ["ip:"]);
$routeArg = getopt(null, ["routes"]);
$cmdDig = '/usr/bin/dig';

if (file_exists($cmdDig)) {
    $digCheck = true;
} else {
    echo "\n*** ERROR *** ||| $cmdDig not found, please install dig\n" . PHP_EOL;
    exit();
}

foreach ($ipArg as $ip) {
    if (strpos($ip, '/') == true) {
        $split = explode('/', $ip);
        $ipRangeStart = ip2long($split[0]) & ((-1 << (32 - (int)$split[1])));
        $ipCleaned = long2ip($ipRangeStart);
        echo "\nRemoving CIDR notation and trying again. Query server does not accept subnets. (Using ".$ipCleaned.")\n\n";
        $ipRange[] = trim($ipCleaned);
        //echo "/ == true\n";
        //var_dump($ipRange);
        } elseif (strpos($ip, '/') == false) {
            $ipRange[] = trim($ip);
    } bogonData($ipRange);
            list($bogonArr) = bogonData($ipRange);
            $bogonArr = str_replace('"', '', trim($bogonArr));
            if ($bogonArr == '127.0.0.2') {
                $bogonIP = true;
                echo "\n\033[1mBOGON DETECTED!\033[0m This IP is reserved for private use, not advertised, or not allocated!\n\n";
                exit();
                //var_dump($bogonArr);
            } elseif ($bogonArr != '127.0.0.2') {
                $ipRange[] = $ipRange;
                //var_dump($bogonArr);
        }
    }


if ($routeArg == null && $bogonIP = false) {
    asnData($ipRange);
    list($originArr, $asnArr) = asnData($ipRange);
    list($routeArr) = routeData($originArr[0]);
    echo "\n\033[1m##ASN Information##\033[0m"."\n\n\033[1mAS Number:\033[0m AS".$originArr[0]."\n\033[1mBGP Prefix:\033[0m ".$originArr[1]."\n\033[1mAS Name:\033[0m ".$asnArr[4]."\n\033[1mCountry of Origin:\033[0m ".$originArr[2]."\n\033[1mRegistry:\033[0m ".$originArr[3]."\n\033[1mASN Allocation Date:\033[0m ".$asnArr[3]."\n\033[1mIPv4 Allocation Date:\033[0m ".$originArr[4]."\n\n";
} if ($routeArg["routes"] === false) {
        asnData($ipRange);
        list($originArr, $asnArr) = asnData($ipRange);
        echo "\n\033[1m##ASN Information##\033[0m"."\n\n\033[1mAS Number:\033[0m AS".$originArr[0]."\n\033[1mBGP Prefix:\033[0m ".$originArr[1]."\n\033[1mAS Name:\033[0m ".$asnArr[4]."\n\033[1mCountry of Origin:\033[0m ".$originArr[2]."\n\033[1mRegistry:\033[0m ".$originArr[3]."\n\033[1mASN Allocation Date:\033[0m ".$asnArr[3]."\n\033[1mIPv4 Allocation Date:\033[0m ".$originArr[4]."\n\n";
        routeData($originArr[0]);
        list($routeArr) = routeData($originArr[0]);
            foreach ($routeArr as $route) {
                echo "\033[1mRoute: \033[0m".$route."\n";
            } echo "\n";
    }

function bogonData($ipRangeArr) {
    $bogonTarget = 'bogons.cymru.com';
    foreach ($ipRangeArr as $ipAddressBogon) {
        $rev = join('.', array_reverse(explode('.', trim($ipAddressBogon))));
        $bogonCombine = sprintf('%s.%s', $rev, $bogonTarget);
        $bogonLookup = "dig +short ".$bogonCombine;
        $bogonQuery = `$bogonLookup`;
        $bogonArr = $bogonQuery;
        return[$bogonArr];
    }
}

function asnData($ipRangeArr) {
    $originTarget = 'origin.asn.cymru.com';
    $asnTarget = 'asn.cymru.com';
    foreach ($ipRangeArr as $ipAddress) {
        $rev = join('.', array_reverse(explode('.', trim($ipAddress))));
        $originCombine = sprintf('%s.%s', $rev, $originTarget);
        $originLookup = "dig +short ".$originCombine." TXT";
            $originQuery = `$originLookup`;
            $originQuery = trim($originQuery);
            $originArr = array_map('trim', explode('|', str_replace('"', '', $originQuery)));
                $asArrComb = "AS".$originArr[0];
                $asnCombine = sprintf('%s.%s', $asArrComb, $asnTarget);
                $asnLookup = "dig +short ".$asnCombine." TXT";
                    $asnQuery = `$asnLookup`;
                    $asnQuery = trim($asnQuery);
                    $asnArr = array_map('trim', explode('|', str_replace('"', '', $asnQuery)));
        return [$originArr, $asnArr];
    }
}

function routeData($originASN) {
    $routeLookup = "whois -h whois.radb.net '!gas".$originASN[0]."'";
        $routeQuery = `$routeLookup`;
        $routeQuery = trim(str_replace('C', '', $routeQuery));
        $routeArrPre = array_map('trim', explode(' ', str_replace(PHP_EOL, ' ', $routeQuery)));
            $routeArrRev = array_reverse($routeArrPre, false);
            $routeArrPop = array_pop($routeArrRev);
            $routeArr = $routeArrRev;
            return [$routeArr];
}