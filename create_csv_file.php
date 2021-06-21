<?php

$logfile = dirname(__FILE__) . '/logs/speedtest.log';
$csvfile = dirname(__FILE__) . '/csv_files/speedmon_data_' . date("Y-m-d_H_m_s") . '.csv';

@mkdir(dirname(__FILE__) . '/csv_files');
$outputfile = fopen($csvfile,"a");
fwrite($outputfile, 'Time,Ping,Download,Upload,Tries' . PHP_EOL);

$handle = fopen($logfile, "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        if (substr($line, 0, 1) === "[") {

            $data = [
                'time' => null,
                'ping' => null,
                'download' => null,
                'upload' => null,
                'tries' => 0
            ];

            $data['time'] = strtotime(trim(strstr($line, ']', true), '['));
            $line = trim(strstr($line, ']'), "] ");

            if (substr($line, 0, 11) === 'error Tries') {

                // speedtest call failed
                $data['ping'] = 0;
                $data['download'] = 0;
                $data['upload'] = 0;
                $data['tries'] = intval(trim(explode('Tries=', $line)[1], '"'));

            } else {

                $data['tries'] = intval(trim(explode('Tries=', $line)[1], '"'));
                $line = trim(explode('Tries=', $line)[0], ' ');
                $data['ping'] = floatval(trim(explode('Download=', $line)[0], 'Ping=" ms'));
                $line = explode('Download=', $line)[1];

                $theRest = explode('Upload=', $line);
                $data['download'] = floatval(trim($theRest[0], '" Mbits/'));
                $data['upload'] = floatval(trim($theRest[1], '" Mbits/'));
            }
        }

        $data['time'] = '"' . date("d/m/y g:m:s A", $data['time']) . '"';
        fwrite($outputfile, implode(',', array_values($data)) . PHP_EOL) ;
    }

    fclose($handle);
    fclose($outputfile);

} else {
    // error opening the file.
}