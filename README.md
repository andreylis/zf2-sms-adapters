zf2-sms-adapters
================

ZF2 module for sending SMSes with queue storage via Doctrine and zf2 console controller for processing them via adapters

### Installation via Composer

    {
        "require": {
            "andreylis/zf2-sms-adapters": "1.*"
        }
    }

### Code sample to add SMS to queue:

    $SMSSender->sendSMS("375297357355", "Hello, Brother");

### Console command for adding to Cron
    php public/index.php send messages
