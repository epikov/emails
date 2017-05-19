<?php

const DIRECTORY_DATA_MAIL_CHIMP = 'data/28012017/mailchimp';
const DIRECTORY_DATA_OTHER      = 'data/28012017/other';
const DIRECTORY_DATA      = 'data/28012017';

const DIRECTORY_OUTPUT_DIR = 'output';

const NOW_NAME = '2013';

$names = [
    '2009',
    '2010',
    '2011',
    '2012',
    '2013',
    '2014',
    '2015',
    '2016',
    '2016sd',
    '2016new',
];

foreach ($names as $name) {
    $mailchimpHandler = fopen(DIRECTORY_DATA_MAIL_CHIMP . '/' . $name . '_not open.csv', 'r');
    $otherHandler     = fopen(DIRECTORY_DATA_OTHER . '/' . $name . '_open wt.csv', 'r');

    $outputHandler = fopen(DIRECTORY_DATA . '/output_' . $name . '.csv', 'w+');

    if (false === $mailchimpHandler || false === $otherHandler || false === $outputHandler) {
        print_r(DIRECTORY_DATA_MAIL_CHIMP . '/' . $name . '_not open.csv');
        print_r(PHP_EOL);
        print_r(DIRECTORY_DATA_OTHER . '/' . $name . '_open wt.csv');
        print_r(PHP_EOL);
        print_r(DIRECTORY_DATA . '/output_' . $name . '.csv');

        die;
    }
    fputcsv($outputHandler, ['Email']);

    $mailChimpEmails = [];
    $otherEmails = [];
    while (false !== $content = fgetcsv($mailchimpHandler)) {
        if ($content[0] === 'Email Address') continue;

        $mailcimpMail = strtolower(trim($content[0]));

        $mailChimpEmails[$mailcimpMail] = $mailcimpMail;
    }

    while (false !== $content = fgetcsv($otherHandler)) {
//        $exploded = explode(';', $content[0]);
        if ($content[0] === 'Email Address') continue;

        $otherEmail = strtolower(trim($content[0]));

        if (false === isset($mailChimpEmails[$otherEmail])) {
            fputcsv($outputHandler, [$otherEmail]);
        }
    }

    fclose($mailchimpHandler);
    fclose($otherHandler);
    fclose($outputHandler);
}
