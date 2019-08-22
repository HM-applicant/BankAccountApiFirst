<?php

namespace App\Transaction\Factory;

use App\Transaction\Transaction;
use App\Transaction\User;
use DateTime;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use League\Flysystem\UnreadableFileException;
use Symfony\Component\Process\Exception\LogicException;

class ZipFileUserTransaction implements UserTransaction
{
    private $apiUrl;
    private $apiKey;

    public function __construct(string $url, string $apiKey)
    {
        $this->apiUrl = $url;
        $this->apiKey = $apiKey;
    }

    /**
     * Fetches all transactions of a user for a specific date from the given url
     *
     * @param User     $user
     * @param DateTime $date
     *
     * @return array
     *
     * @throws \League\Flysystem\UnreadableFileException
     */
    public function getTransactions(User $user, DateTime $date)
    {
        // get file from banking server
        $filePath = $this->getFile($user, $date);
        $extractionFolder = $this->extractZioFile($filePath);
        $transactions = array();

        foreach (Storage::files($extractionFolder) as $file) {
            $transactions = array_merge($transactions, $this->extractTransactionsFromFile($file, $user));
        }

        return $transactions;
    }

    /**
     * fetches a zipfile for a specific user and date from the banking server
     *
     * @param User     $user
     * @param DateTime $date
     *
     * @return string
     */
    private function getFile(User $user, DateTime $date) : string
    {
        /*
    $handler = curl_init();
    curl_setopt($handler, CURLOPT_URL, sprintf($this->apiUrl, $user->getId(), $date->format('Y-m-d')));
    curl_setopt($handler, CURLOPT_VERBOSE, 0);
    curl_setopt($handler, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($handler, CURLOPT_AUTOREFERER, false);
    curl_setopt($handler, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($handler, CURLOPT_HEADER, 0);
    $result = curl_exec($handler);
    curl_close($handler);

    // permissions have to be checked
    $fp = fopen($output_filename, 'w');
    fwrite($fp, $result);
    fclose($fp);
         */

        return 'app/1_2019-04-04.zip';
    }

    /**
     * extracts given zipfile
     *
     * @param $file
     * @return string
     */
    private function extractZioFile($file) : string
    {
        $fileFullPath = storage_path($file);
        $extractionFolderAbsolute = pathinfo($fileFullPath, PATHINFO_DIRNAME)
            . '/'
            . pathinfo($file, PATHINFO_FILENAME);
        $extractionFolder = pathinfo($file, PATHINFO_FILENAME);
        Storage::disk('local')->makeDirectory($extractionFolder);
        \Zipper::make($fileFullPath)->extractTo($extractionFolderAbsolute);

        return $extractionFolder;
    }

    /**
     * Functions extracts transactions for user from a csv file
     *
     * @param $file
     * @param $user
     *
     * @return array
     *
     * @throws \League\Flysystem\UnreadableFileException
     * @throws InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\LogicException
     */
    private function extractTransactionsFromFile($file, $user) : array
    {
        $transactions = array();
        $fileContent = Storage::get($file);
        // split file content into lines
        //TODO check for every eol
        $fileContent = str_getcsv($fileContent, "\n");

        if (!is_array($fileContent)) {
            throw new UnreadableFileException('File ' . $file . ' could not be devided into lines');
        }

        $header     = str_getcsv($fileContent[0], ';');
        $ibanKey    = array_search('IBAN', $header);
        $subjectKey = array_search('SUBJECT', $header);
        $amountKey  = array_search('AMOUNT', $header);
        $dateKey    = array_search('DATE', $header);

        if ($ibanKey === false
            || $subjectKey === false
            || $amountKey === false
            || $dateKey === false
        ) {
            throw new InvalidArgumentException("Fileheader line does not contain correct entries.");
        }

        // remove header line
        unset($fileContent[0]);

        foreach ($fileContent as $line) {
            $parsedLine = str_getcsv($line, ';');

            if (count($parsedLine) != 4) {
                throw new LogicException('Line does not contain proper csv formatted string: ' . $line);
            }

            $transactions[] = new Transaction(
                $user,
                $parsedLine[$ibanKey],
                $parsedLine[$subjectKey],
                $parsedLine[$amountKey],
                (new DateTime($parsedLine[$dateKey]))
            );
        }

        return $transactions;
    }
}
