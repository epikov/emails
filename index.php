<?php
error_reporting(-1);
ini_set('display_errors', 1);
set_time_limit(0);
ini_set('memory_limit', '5G');

class ParseFile
{
    const FILE_NAME = 'April.csv';
    const CSV_DELIMITER = "\t";

    public function run()
    {
        $handler       = fopen(self::FILE_NAME, 'r');
        $outputHandler = fopen('output_' . self::FILE_NAME, 'w+');

        fputcsv($outputHandler, [
            'Email',
            'First name',
            'Last name',
            'Quantity of wishlists',
            'Quantity of vehicles in garage',
            'First order date',
            'First Make',
            'First Model',
            'First Year',
            'Last order date',
            'Last Make',
            'Last Model',
            'Last Year'
        ]);

        $outputData = [];
        $outputAttributes = [];
        while (false === empty($data = fgetcsv($handler, null, self::CSV_DELIMITER))) {
            $email        = $data[0];
            $firstName    = $data[1];
            $lastName     = $data[2];
            $qtyOfWhish   = $data[3];
            $qtyOfVehicle = $data[4];

            if (strtolower($email) === 'email') continue;

            foreach (range(7, count($data) - 1) as $key) {
                if (false === isset($data[$key]) || empty($data[$key])) continue;

                $someCarInfo = $data[$key];

                $explodedCarInfo = explode(',', $someCarInfo);

                $mmy       = $explodedCarInfo[0] ?? '';
                $orderDate = $explodedCarInfo[1] ?? '';

                $outputData[$email][] = [
                    'date' => $orderDate,
                    'mmy'  => $mmy
                ];

                $outputAttributes[$email] = [
                    'name'         => $firstName,
                    'lastname'     => $lastName,
                    'qtyOfWhish'   => $qtyOfWhish,
                    'qtyOfVehicle' => $qtyOfVehicle
                ];
            }
        }

        $sortFunction = function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        };

        foreach ($outputData as $email => $info) {
            usort($info, $sortFunction);

            reset($info);

            $first = current($info);
            $last  = end($info);

            $firstMMY = explode(' ', $first['mmy']);
            $lastMMY = explode(' ', $last['mmy']);

            $attributes = $outputAttributes[$email];

            fputcsv($outputHandler, [
                $email,
                $attributes['name'],
                $attributes['lastname'],
                $attributes['qtyOfWhish'],
                $attributes['qtyOfVehicle'],
                $first['date'] ?? '',
                $firstMMY[1] ?? '', //Make
                $firstMMY[2] ?? '', //Model
                $firstMMY[0], //Year
                $last['date'] ?? '',
                $lastMMY[1] ?? '', //Make
                $lastMMY[2] ?? '', //Model
                $lastMMY[0] ?? '' //Year
            ]);
        }

        fclose($handler);
        fclose($outputHandler);
    }
}

(new ParseFile())->run();
