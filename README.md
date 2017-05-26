# songbook-api

Restful API for sample react application "songbook".

## Description

Api supports following models:

* Song
* Author
* Artist
* Genre
* Lyrics
* Audio
* Video
* User

## Access

All the models except from `User` are available in `readonly` mode for unauthirzed users.
All the mutations of the models are available only for authenticatet and authorized users.

## User specific data

After the authentication user has an access to the `dashboard` and `profile`.

These actions are unavailable for unauthorized users.

`Dashboard` provides all the dynamic data for authenticated `user` like messages and the other iformations
like usage statistics etc.

`Profile` allowes changing user account data, like `firstName`, `lastName`, `description`, `info`
and all the account settings like profile visibility etc.

## Authentication

Authentication is implemented with JWT (JASON Web Tokens).


