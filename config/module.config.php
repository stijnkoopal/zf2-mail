<?php
    namespace Mail;

    return [
        'mail' => array(
            'domains' => array(
                'default' => 'my-application.com'
            ),
            'from' => array(
                'default' => array(
                    'name' => 'My application',
                    'email' => 'app',
                    'domain' => 'default'
                ),
            ),
            'layouts' => array(
                'default' => array(
                    'txt' => 'my-application-email-txt',
                    'html' => 'my-application-email-layout'
                )
            )
        ),
    ];