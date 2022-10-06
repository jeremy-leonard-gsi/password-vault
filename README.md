
# Password Vault

## Overview

This is a web based password vault. It uses LDAP for user authentication. Stores
 account information including password in a mysql/mariadb database. Password 
are stored using AES256 bit encryption. When a password is changed the history 
for that account is saved. When an account is deleted it is removed from the 
interface but remains in the database and can be restored. This system also 
features an access control system to limit what account users are able to see. 
This is based on group membersips in the LDAP directory.

## [Installation](docs/installation.md)

## [Access Control](docs/acl.md)

## To Do

- Add password generation
- Add logging
- Extend ACLs to user Owners to manage group password and members to have a more
 restricted view.