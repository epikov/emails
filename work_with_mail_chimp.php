<?php

namespace My;

use \DrewM\MailChimp\MailChimp;

set_time_limit(0);
ini_set('memory_limit', '8G');

require_once __DIR__ . '/vendor/autoload.php';

class WorkWithMailChimp
{
    const MAIL_CHIMP_API_KEY = '';

    const DIRECTORY_DATA = 'data/';
    const DIRECTORY_NOW_DAY = '22032017';

    const LIST_NAMES = [
//        'Active Subscribers 2011',
//        'Active Subscribers 2012',
//        'Active Subscribers 2013',
//        'Active Subscribers 2014',
//        'Active Subscribers 2015',
//        'Active Subscribers 2016'
    ];

    public function work()
    {
        $api =  new MailChimp(self::MAIL_CHIMP_API_KEY);
        $timeout = 0;

        $lists = $api->get('lists', ['count' => 100], $timeout);

        $handler = fopen(self::DIRECTORY_DATA . 'fromMailChimp.csv', 'w+');

        $count = 2000;
        $offset = 0;

        if (is_array($lists)) {
            $allLists = $lists['lists'];

            foreach ($allLists as $list) {
                if (in_array($list['name'], self::LIST_NAMES)) {
                    while (false === empty($members = $api->get('lists/' . $list['id'] . '/members', ['count' => $count, 'offset' => $offset], $timeout)['members'])) {
                        foreach ($members as $member) {
                            if ($member['status'] === 'subscribed') {
                                fputcsv($handler, [$member['email_address']]);
                            }
                        }
                        $offset += $count;
                    }
                }
            }
        }

        fclose($handler);
    }
}

(new WorkWithMailChimp())->work();
