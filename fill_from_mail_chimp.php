<?php

namespace My;

class FillFromMailChimp
{
    const MAILCHIMP_DATA_DIR = 'data/fromMailChimp';

    const JEEP_DATA = 'data/jeep';
    const FORD_DATA = 'data/ford';

    const DATA_PATH = 'data';

    const DATA_NAMES = [
        2011,
        2012,
        2013,
        2014,
        2015,
        2016
    ];

    public function __construct()
    {

    }

    public function run()
    {
        $jeepData = [];
        foreach (new \DirectoryIterator(self::JEEP_DATA) as $fileJeepData) {
            if ($fileJeepData->isDot()) continue;

            $handle = fopen(self::JEEP_DATA . DIRECTORY_SEPARATOR . $fileJeepData->getBasename(), 'r');

            while (false !== ($data = fgetcsv($handle, null, ';'))) {
                if (strtolower($data[0]) === 'email') continue;

                $email     = $data[0];
                $firstname = $data[1];
                $lastname  = $data[2];
                $make      = $data[3];
                $model     = $data[4];
                $year      = $data[5];
                $orderYear = isset($data[6]) ? $data[6] : '';

                $jeepData[$email][$orderYear] = [
                    'firstname' => $firstname,
                    'lastname'  => $lastname,
                    'make'      => $make,
                    'model'     => $model,
                    'year'      => $year,
                    'orderYear' => $orderYear
                ];
            }

            fclose($handle);
        }

        $fordData = [];
        foreach (new \DirectoryIterator(self::FORD_DATA) as $fileFordData) {
            if ($fileFordData->isDot()) continue;

            $handle = fopen(self::FORD_DATA . DIRECTORY_SEPARATOR . $fileFordData->getBasename(), 'r');

            while (false !== ($data = fgetcsv($handle, null, ';'))) {
                if (strtolower($data[0]) === 'email') continue;

                $email     = $data[0];
                $firstname = $data[1];
                $lastname  = $data[2];
                $make      = $data[3];
                $model     = $data[4];
                $year      = $data[5];
                $orderYear = isset($data[6]) ? $data[6] : '';

                $fordData[$email][$orderYear] = [
                    'firstname' => $firstname,
                    'lastname'  => $lastname,
                    'make'      => $make,
                    'model'     => $model,
                    'year'      => $year,
                    'orderYear' => $orderYear
                ];
            }

            fclose($handle);
        }


        /////////////////////////////////////////////////////



        foreach (new \DirectoryIterator(self::MAILCHIMP_DATA_DIR) as $fileMailChimpData) {
            if ($fileMailChimpData->isDot()) continue;

            $basename = $fileMailChimpData->getBasename();
            $mailchimpFileYear = str_replace('.csv', '', $basename);

            $handle = fopen(self::MAILCHIMP_DATA_DIR . DIRECTORY_SEPARATOR . $basename, 'r');

            $outputHandler = false;
            while (false !== ($data = fgetcsv($handle))) {
                if (false === file_exists('output' . DIRECTORY_SEPARATOR . $basename)) {
                    $outputHandler = fopen('output' . DIRECTORY_SEPARATOR . $basename, 'w+');
                    fputcsv($outputHandler, ['Email','First Name', 'Last Name', 'Make', 'Model', 'Year', 'Order Year']);
                }

                $email = $data[0];

                if (isset($jeepData[$email][$mailchimpFileYear])) {
                    $existentData = $jeepData[$email][$mailchimpFileYear];

                    $fillData = [
                        $email,
                        $existentData['firstname'],
                        $existentData['lastname'],
                        $existentData['make'],
                        $existentData['model'],
                        $existentData['year'],
                        $existentData['orderYear']
                    ];

                    fputcsv($outputHandler, $fillData);
                }

                if (isset($fordData[$email][$mailchimpFileYear])) {
                    $existentData = $fordData[$email][$mailchimpFileYear];

                    $fillData = [
                        $email,
                        $existentData['firstname'],
                        $existentData['lastname'],
                        $existentData['make'],
                        $existentData['model'],
                        $existentData['year'],
                        $existentData['orderYear']
                    ];

                    fputcsv($outputHandler, $fillData);
                }
            }

            fclose($handle);
        }
    }
}

(new FillFromMailChimp())->run();
