# Changelog
All notable changes to `Ganadev Notif` will be documented in this file

## 1.0.0
- Release Packages

## 1.0.1
- Add Change Email Setting Automaticaly
- Add enable/disabled API
- Add enable/disabled Change Email Setting

## 1.0.2
- Remove send whatsapp to Indonesian Code only

## 2.0.0
- First integration with GanaDev API v3
- Remove usuless Endpoint and Configuration
  - Endpoint to check device status
  - Configuration to disabled WA API
  - Configuration to disabled Email API
## 2.0.1
- Bug Fixing when send whatsapp media

## 2.0.2
- Remove auto phone region code, now when use WhatsApp Endpoint, Phone number must be send with phone region code

## 2.0.3 - 2.0.7
- Bug Fixing, Add Response to Option (Json | Array).

## 2.0.8
- Bug Fixing when internet is not available
- Bug Fixing when replace email

## 2.0.9
- Bug Fixing cant replace local email

## 3.0.1
- Change message response from function
- Refactor some code
- Add encryption in ganadevkey data
- Now email replace only give logger when failed
- [NEW] Add new concept of replace email, now we must add new mailer config name as "ganadev" and set default MAIL_MAILER is "ganadev"
- [NEW] Now the API auto send using Queue Jobs, the queue follow setting of QUEUE_CONNECTION in the project
- [NEW] We can use manual Queue method using Laravel Queue. We can be publish into Jobs folder