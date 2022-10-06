
# ACL Documentation

## Overview

Account passwords can be assigned to groups so only users whom are members of 
the specified groups can access the password.
Users that are membes of the ***Global Admin*** Group can always access all 
account password.

## Configuration

Administration can be managed by members of the ***Global Admin*** group. This is
accessible from the drop down menu by clicking on your displayed name and
selecting ***Admin***.

To switch back to the list of password select ***Password Vault***.

ACL configureation is managed by two config values, ***globalAdminGroupDN*** and 
***groupDNs***.

>### globalAdminGroupDN
>
>This configuration setting contains the full DN of the group that always has 
>access to all the accounts. Members of this group can also access the admin 
>page and change settings.
>
>`globalAdminGroupDN = CN=Domain Admins, CN=Users, DC=domain, DC=TLD`
>

>### groupDNs
>
>This configuration setting contains all the groups that are allowed to access 
>the password database system. The groups are listed with their full DNs 
> seperated by semi-colons.
>
> - Users whom are members of any of these groups will be able to log in. 
> - Users will see all the accounts assigned to any 
> of the groups they are a member of. 
> - Users may also add new accounts to the database.
> - Users may delete any accounts they have access to.
> - Users may update/edit any account they have access to.
> - Users may add accounts to any group they are a mamber of.
>
>`groupDNs =  CN=Domain Admins, CN=Users, DC=domain, DC=TLD;CN=HVAC Users, 
>CN=Users, DC=domain, DC=TLD;CN=Developers, CN=Users, DC=domain, DC=TLD;
>CN=Helpdesk Users, CN=Users, DC=domain, DC=TLD`
>

## Assigning accounts to groups

When adding a new account or editing an edisting account:

1. Select the ***Access Control*** tab.
2. Check the box on any group you wish its members to have access to the account
 you are adding or editing.

If no groups are checked only the global admin group members will have access.