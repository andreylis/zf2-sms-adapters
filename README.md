zf2-sms-adapters
================

ZF2 module for sending SMSes with queue storage via Doctrine and zf2 console controller for processing them via adapters

### Code sample to add SMS to queue:

    $SMSSender->sendSMS("375297357355", "Hello, Brother");

### Console command for adding to Cron
    php public/index.php send messages
