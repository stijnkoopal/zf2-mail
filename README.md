ZF-2 Mail
=======

Introduction
------------

This module provides a highly configurable service for sending emails. It allows you to send emails with both plain as
html content. Furthermore, it provides the ability to specify layouts for both content types.

Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)

Installation
------------

### Main Setup

#### By cloning project (not recommended)

1. Clone this project into your `./vendor/` directory.

#### With composer

1. Add this project in your composer.json:

    ```json
    "require": {
        "stijnkoopal/zf2-mail": "dev-master"
    }
    ```

2. Now tell composer to download Zf2 mail by running the command:

    ```bash
    $ php composer.phar update
    ```

#### Post installation

1. Enabling it in your `application.config.php`file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'Mailing',
        ),
        // ...
    );
    ```

2. Copy config/mail.global.php.dist and config/mail.local.php.dist to your config directory
3. Remove the .dist extension from these files and fill in the blanks


Options
-------

Thus module has some options to allow you to quickly customize the basic
functionality.

The following options are available:

- **domains** - A array of key value pairs. The default key is used for email addresses that did not specify any domain
- **transport** - The specification for the email transport. A `type` and `options` key can be specified
- **from** - An array of arrays where each inner array should specify the `name` and `email` keys. Optionally an `domain`
key can be specified that is available in the `domains` option. If no `domain` is specified, `default` is used.
- **layouts** - An array of array where each inner array can specify a `plain` and/or `html` key. The value for these
keys should point to view file that you have defined in the `view_manager` configuration. Hence, the view manager
from the application is used to locate the view script.
- **mails** - An array of arrays where each inner array can specify the following options: `from`, a value that points to
a key in the `from` options array. `layout`: a value that points to a key in the `layouts` array. If not specified, no
layout is used. `subject`: the subject of the email. If not specified the `subject` in `template` will be used.
`template`: an array containing the keys `html`, `plain` and `subject`. These should point to a view
script specified by the `view_manager`.

Send email
-------
Let `$emailService` be an instanceof `Mailing\Service`. (Can be obtained with the `MailingServiceProviderTrait`). An
email is then send with `$email->sendMail($message, 'alias', $variables)`. The `$message` variable is an instance of
`Zend\Mail\Message` and you should specify addresses in this object. The second parameter is a key in the `mails` array
specified in the configuration. Variables can be passed as last parameter. These will be available in your view script.