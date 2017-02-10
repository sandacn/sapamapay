<?php

namespace Edwinmugendi\Poller;

/**
 * S# FilePoller() class
 *
 * @author Edwin Mugendi <edwinmugendi@gmail.com>
 * 
 * File Poller class
 * 
 */
class FilePoller {

    private $configs;
    private $response;
    private $mysql_conn;

    /**
     * S# __construct() function
     * 
     * @author Edwin Mugendi <edwinmugendi@gmail.com>
     * 
     * Constructor
     * 
     * Gets configs and connects to mysql
     * 
     */
    public function __construct() {
        //Set configs
        $this->configs = json_decode(file_get_contents("../configs.json"), true);

        //Connect to mysql //Single-ton
        $this->mysql_conn = mysqli_connect($this->configs['mysql']['db_host'], $this->configs['mysql']['db_user'], $this->configs['mysql']['db_pass'], $this->configs['mysql']['db_name']);
    }

//E# __construct() function

    /**
     * S# poll() function
     * 
     * Poll the directory
     * 
     */
    public function poll() {

        $path = $this->configs['upload_path'];
        $files = scandir($this->configs['upload_path']);

        foreach ($files as $file_name) {
            if ($file_name != '.' && $file_name != '..') {
                $this->pollFile($file_name);
            }//E# if statement
        }//E# foreach statement
        //Close mysql connection
        mysqli_close($this->mysql_conn);
        die();
    }

//E# poll() function

    /**
     * S# pollFile() function
     * 
     * Polle a single file
     * 
     * @param str $file_name File name
     * 
     */
    private function pollFile($file_name) {

        //Read this file
        $reading_path = $this->configs['upload_path'] . '/' . $file_name;

        //Write this file
        $writing_path = $this->configs['upload_path'] . '/temp-' . $file_name;

        $reading = fopen($reading_path, 'r');
        $writing = fopen($writing_path, 'w');

        $replaced = false;

        $records = $end_time = $records_processed = 0;
        $start_time = microtime(true);

        while (!feof($reading)) {
            $line = fgets($reading);
            if (trim($line)) {
                $line_parts = explode(' ', $line, 3);

                if ($line_parts[0] != 'PROCESSED') {
                    $this->recipient = $line_parts[1];
                    $this->message = $line_parts[2];
                    if ($line_parts[1] && $line_parts[2]) {
                        $replaced = true;
                        //Call UCM
                        $ucm_response = $this->callUcm($line_parts[1], $line_parts[2]);

                        if (!(int) $ucm_response['status']) {
                            $line = 'PROCESSED ' . $line;
                            $records_processed++;
                        }//E# if statement
                        $records++;
                    }//E# if statement
                }//E# if statement
            } else {
                //For empty lines till increment these
                $records_processed++;
                $records++;
            }//E# if statement
            fputs($writing, $line);
        }//E# while statement
        fclose($reading);
        fclose($writing);

        if ($replaced) {
            rename($writing_path, $reading_path);
        } else {
            unlink($writing_path);
        }//E# if else statement

        echo 'Records ' . $records . '<p>';
        echo 'Records processed ' . $records_processed . '<p>';

        if ($records && ($records == $records_processed)) {//Before backing up ensure the records and records processed are the same
            //Build name
            $backup_name = 'Records-' . $records . '-RecordsProcessed-' . $records_processed . '-Date-' . date('Y-m-d-H:i:s') . '-OriginalName-' . $file_name;

            $backed_up = $this->backupFile($reading_path, $backup_name, $records, $records_processed);

            $end_time = microtime(true);

            $seconds = $end_time - $start_time;

            //Save file meta
            $this->saveFileMetaData($file_name, $records, $records_processed, $backed_up, $backup_name, $seconds);
        }//E# if statement

        return;
    }

//E# pollFile() function

    /**
     * S# backupFile() function
     * 
     * Backup file
     * 
     * @param str $original_file_path Original file path
     * @param str $backup_name Backup name
     * 
     * @return boolean
     */
    private function backupFile($original_file_path, $backup_name) {

        $copy_to_path = $this->configs['backup_path'] . '/' . $backup_name;

        $copied = rename($original_file_path, $copy_to_path);

        return (int) $copied;
    }

//E# backupFile() function

    /**
     * S# saveFileMetaData() function
     * 
     * @author Edwin Mugendi <edwinmugendi@gmail.com>
     * 
     * Save file meta data
     * 
     * @param str $name name
     * @param int $records Number of records in the file
     * @param int $records_processed Number of records processed
     * @param int $backed_up Was the file backed up
     * @param str $backup_name Backup name
     * @param int $seconds Seconds
     * 
     */
    private function saveFileMetaData($name, $records, $records_processed, $backed_up, $backup_name, $seconds) {
        $query = "INSERT INTO " . $this->configs['mysql']['db_table'] . " (name,records,records_processed,backed_up,backup_name,created_on,last_updated_by,last_updated_on,seconds) VALUES('$name','$records','$records_processed','$backed_up','$backup_name',NOW(),1,NOW(),'$seconds')";
        $result = mysqli_query($this->mysql_conn, $query);
    }

//E# saveFileMetaData() functino

    /**
     * S# callUcm() function 
     * 
     *  @author Edwin Mugendi <edwinmugendi@gmail.com>
     * 
     * Call UCM 
     * 
     * @param str $recipient Recipients mobile phone number
     * @param str $message SMS Message
     * 
     * @link URL Called http://172.16.102.233/ucm_api/sendmsg.php?action=sendmessage&username=CRM&password=9D0k2SNfL51C&recipient=254723095439&messagetype=SMS:TEXT&messagedata=my+message
     * 
     * @return array has a status integer field, 0 if sent, otherwise not sent
     * 
     */
    private function callUcm($recipient, $message) {

        //Query string
        $query_string = array(
            'action' => 'sendmessage',
            'username' => $this->configs['user_configs']['TranzTWCMS']['username'],
            'password' => $this->configs['user_configs']['TranzTWCMS']['password'],
            'recipient' => $recipient,
            'messagetype' => 'SMS:TEXT',
            'messagedata' => $message
        );

        //Build link 
        $link = $this->configs['end_point'] . '?' . http_build_query($query_string);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $link);
        try {
            //This is it: Call UCM
            $xml_response = curl_exec($ch);
            $xml = simplexml_load_string($xml_response);
            $this->response = array(
                'status' => $xml->data->acceptreport->statuscode
            );
        } catch (Exception $ex) {
            $this->response = array(
                'status' => 1
            );
        }//E# try catch statement

        return $this->response;
    }

//E# callUcm() function
}

//E# FilePoller() class
