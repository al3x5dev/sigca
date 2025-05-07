<?php

return [
    'host'=>env('LDAP_HOST','ldap://host'),
    'port'=>env('LDAP_PORT',389),
    'base_dn'=>env('LDAP_BASE_DN','dc=example,dc=com'),
    'username'=>env('LDAP_USERNAME','cn=usuario,dc=example,dc=com'),
    'password'=>env('LDAP_PASSWORD',''),
];