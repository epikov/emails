<?php
/**
 * Created by Alex Epikov.
 * Date: 18.11.2016
 * Time: 22:14
 */

set_time_limit(0);
ini_set('memory_limit', '8G');

class CsvAll
{
    const DIRECTORY_DATA = 'data/';
    const DIRECTORY_ALL = 'data/old/allYears';
    const DIRECTORY_OUTPUT_FILE_NAME = 'output_2005-2010.csv';
    const DIRECTORY_OUTPUT_DIR = 'output';

    const MIN_YEAR = '2005';
    const MAX_YEAR = '2010';

    const FILE = '%d-%s.csv';

    public function run()
    {
        $this->getData();
    }

    private function getData()
    {
        $data = [];

        $regex = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';
//        $outputHandler = fopen(self::DIRECTORY_DATA . DIRECTORY_SEPARATOR . 'wrong_emails.csv', 'w+');

        $allYears = $this->getYears();
        $allMonths = $this->getMonths();


//        $outputHandler = fopen(self::DIRECTORY_DATA . DIRECTORY_SEPARATOR . self::DIRECTORY_OUTPUT_FILE_NAME, 'w+');
//        fputcsv($outputHandler, $allMonths);
//
//        fclose($outputHandler);die;

//        for($i = 0; $i < 12; $i++)
//        {
//            if (0 == ($i % 2)) print_r($i);
//        }die;


        $months = [];
        $counterMonth = 0;
        foreach ($allYears as $year) {
            $chunkedMonth = array_chunk($allMonths, 2, true);
            foreach ($chunkedMonth as  $chunkMonth) {
                $fileName = $chunkMonth;
                array_unshift($fileName, $year);
                $fileName = self::DIRECTORY_DATA
                    . self::DIRECTORY_OUTPUT_DIR
                    . '/'
                    . implode('-', $fileName) . '.csv';

//                $outputFileHandler = fopen($fileName, 'w+', true);

//                fputcsv($outputFileHandler, array_values($chunkMonth) + ['result']);

                $firstMonth = current($chunkMonth);
                $secondMonth = end($chunkMonth);

                $pathToFirst = self::DIRECTORY_ALL . '/' . sprintf(self::FILE, $year, $firstMonth);
                $pathToSecond = self::DIRECTORY_ALL . '/' . sprintf(self::FILE, $year, $secondMonth);

                $firstData = $secondData = [];
                if (is_file($pathToFirst) && is_file($pathToSecond)) {
                    $handler = fopen($pathToFirst, 'r');
                    while (false !== $content = fgetcsv($handler)) {
                        $buyerEmail = trim($content[2]);

                        if (strtolower($buyerEmail) === 'email') {
                            continue;
                        }

                        if ((bool) preg_match($regex, $buyerEmail)) {
                            $firstData[$buyerEmail] = $buyerEmail;
                        }
                    }
                    fclose($handler);
                    unset($buyerEmail, $handler, $content);

                    $handler = fopen($pathToSecond, 'r');
                    while (false !== $content = fgetcsv($handler)) {
                        $buyerEmail = trim($content[2]);

                        if (strtolower($buyerEmail) === 'email') {
                            continue;
                        }

                        if ((bool) preg_match($regex, $buyerEmail)) {
                            $secondData[$buyerEmail] = $buyerEmail;
                        }
                    }
                    fclose($handler);
                    unset($buyerEmail, $handler, $content);

                    foreach ($firstData as $value) {

                    }
                }

//                foreach ($chunkMonth as $keyOfMonth => $month) {
//                    $counterMonth++;
//                    $path = self::DIRECTORY_ALL . '/' . sprintf(self::FILE, $year, $month);
//
//                    if (is_file($path)) {
//                        $handler = fopen(self::DIRECTORY_ALL . '/' . sprintf(self::FILE, $year, $month), 'r');
//                        while (false !== $content = fgetcsv($handler)) {
//                            $buyerEmail = trim($content[2]);
//
//                            if (strtolower($buyerEmail) === 'email') {
//                                continue;
//                            }
//
//                            if ((bool) preg_match($regex, $buyerEmail)) {
//                                $data[$month][$buyerEmail] = true;
//                            }
//
//                            if (0 === ($counterMonth % 2)) {
//                                $result = 0;
//                                if (isset($data[$this->getPreviousMonth($keyOfMonth)][$buyerEmail])) {
//                                    $result = 1;
//                                }
//                            }
//
//
//                            fputcsv($outputFileHandler, array_values($chunkMonth) + ['result']);
//                        }
//
//                        fclose($handler);
//                        die;
//                    }
//                }

                $data = [];
            }
        }

//        print_r($data);
//        $outputHandler = fopen(self::DIRECTORY_DATA . DIRECTORY_SEPARATOR . self::DIRECTORY_OUTPUT_FILE_NAME, 'w+');
//        fputcsv($outputHandler, ['Email', 'Year', 'Month', 'Count']);

//        foreach ($data as $email => $userData) {
//            foreach ($userData as $year => $monthData) {
//                foreach ($monthData as $month => $count) {
//                    fputcsv($outputHandler, [$email, $year, $month, $count]);
//                }
//            }
//        }
//        fclose($outputHandler);
        die('Done');
    }

    private function getMonths()
    {
        return [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
    }

    private function getYears()
    {
        return range(self::MIN_YEAR, self::MAX_YEAR);
    }

    private function getPreviousMonth($nowMonth)
    {
        if ($nowMonth == 1) {
            $nowMonth = 12;
        } else {
            $nowMonth -= 1;
        }

        return $this->getMonths()[$nowMonth];
    }

    private function getPreviousYear($nowYear)
    {
        $nowYear -= 1;

        return $nowYear;
    }
}

(new CsvAll())->run();