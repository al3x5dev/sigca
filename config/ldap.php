<?php

return [
    'host' => env('LDAP_HOST', 'ldap://host'),
    'port' => env('LDAP_PORT', 389),
    'uri' => env('LDAP_HOST') . ':' . env('LDAP_PORT'),
    'base_dn' => env('LDAP_BASE_DN', 'dc=example,dc=com'),
    'domain' => env('LDAP_DOMAIN', 'ctecmc.une.cu'),
];
