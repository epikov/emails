<?php
/**
 * Created by Alex Epikov.
 * Date: 18.11.2016
 * Time: 22:14
 */

set_time_limit(0);
ini_set('memory_limit', '8G');

class Csv
{
    const DIRECTORY_DATA = 'data/';
    const DIRECTORY_EM = 'data/all';
    const DIRECTORY_OUTPUT_FILE_NAME = 'output_file.csv';

    public function run()
    {
        $handler = fopen(self::DIRECTORY_DATA . 'all_mails.csv', 'r');
        $emails = [];
        while (false !== $content = fgetcsv($handler)) {
            $inputMail = trim($content[0]);

            $emails[$inputMail]['email'] = trim($content[0]);

            if (isset($content[3])) {
                $emails[$inputMail]['month'] = trim($content[3]);
            }
        }

        fclose($handler);

        $dataBase = $this->getData();

        $outputHandler = fopen(self::DIRECTORY_DATA . self::DIRECTORY_OUTPUT_FILE_NAME, 'w+');

        fputcsv($outputHandler, ['email', 'make', 'model', 'year', 'month']);

        foreach ($emails as $email) {
            if (isset($dataBase[$email['email']])) {
                fputcsv($outputHandler, [
                    $dataBase[$email['email']]['email'],
                    $dataBase[$email['email']]['make'],
                    $dataBase[$email['email']]['model'],
                    $dataBase[$email['email']]['year'],
                    $email['month'],
                ]);
            }
        }
        fclose($outputHandler);
        die('done');
    }

    private function getData()
    {
        $data = [];
        foreach (new DirectoryIterator(self::DIRECTORY_EM) as $item) {
            if ($item->isDot()) {
                continue;
            }

            $handler = fopen(self::DIRECTORY_EM . DIRECTORY_SEPARATOR . $item->getFilename(), 'r');

            while (false !== $content = fgetcsv($handler)) {
                $email = trim($content[2]);
                $make  = (isset($content[4])) ? trim($content[4]) : null;
                $model = (isset($content[5])) ? trim($content[5]) : null;
                $year  = (isset($content[6])) ? trim($content[6]) : null;

                if (null !== $make
                    && null !== $model
                    && null !== $year
                    && false === empty($make)
                    && false === empty($model)
                    && false === empty($year)
                ) {
                    $data[$email]['email'] = $email;
                    $data[$email]['make']  = $make;
                    $data[$email]['model'] = $model;
                    $data[$email]['year']  = $year;
                }
            }
            fclose($handler);
        }

        return $data;
    }
}

(new Csv())->run();