
# Password Vault

## Overview

This is a web based password vault. It uses LDAP for user authentication. Stores
 account information including password in a mysql/mariadb database. Password 
are stored using AES256 bit encryption. When a password is changed the history 
for that account is saved. When an account is deleted it is removed from the 
interface but remains in the database and can be restored.

## [Installation](docs/installation.md)

## [Access Control](docs/acl.md)

## To Do

- Add password generation
- Add logging