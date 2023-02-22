<?php

namespace App\Helpers;

class CSVGenerator
{
    private string $enclosure = '"';
    private string $separator = ';';
    private string $escape = '\\';
    private string $eol = PHP_EOL;
    private bool $escapeDoubleQuotes = false;

    /** @var resource */
    private $file;

    public function __construct()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function getEnclosure(): string
    {
        return $this->enclosure;
    }

    /**
     * @param string $enclosure
     * @return CSVGenerator
     */
    public function setEnclosure(string $enclosure): self
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * @return string
     */
    public function getSeparator(): string
    {
        return $this->separator;
    }

    /**
     * @param string $separator
     * @return CSVGenerator
     */
    public function setSeparator(string $separator): self
    {
        $this->separator = $separator;
        return $this;
    }

    /**
     * @return string
     */
    public function getEscape(): string
    {
        return $this->escape;
    }

    /**
     * @param string $escape
     * @return CSVGenerator
     */
    public function setEscape(string $escape): self
    {
        $this->escape = $escape;
        return $this;
    }

    /**
     * @return string
     */
    public function getEol(): string
    {
        return $this->eol;
    }

    /**
     * @param string $eol
     * @return CSVGenerator
     */
    public function setEol(string $eol): self
    {
        $this->eol = $eol;
        return $this;
    }

    /**
     * @return bool
     */
    public function getEscapeDoubleQuotes(): bool
    {
        return $this->escapeDoubleQuotes;
    }

    /**
     * @param bool $escapeDoubleQuotes
     * @return CSVGenerator
     */
    public function setEscapeDoubleQuotes(bool $escapeDoubleQuotes): self
    {
        $this->escapeDoubleQuotes = $escapeDoubleQuotes;
        return $this;
    }

    /**
     * @param string $prefix
     * @param array $csvLines
     * @param bool|array $header
     *      $header = true pour prendre les clés du tableau : array_keys($array[0])
     *      $header = array pour forcer un header personnalisé
     *      $header = false pour ne pas mettre de headers
     * @return string The CSV filePath
     */
    public function createCsvFromArray(string $prefix, array $csvLines, bool|array $header = true): string
    {
        $fileName = uniqid($prefix . '_', true) . '.csv';
        $filePath = sys_get_temp_dir() . '/' . $fileName;

        $this->file = fopen($filePath, 'w+');
        if (count($csvLines) > 0) {
            $this->addHeader($csvLines, $header);

            foreach ($csvLines as $line)
                $this->addLine(array_values($line));
        }

        fclose($this->file);
        if ($this->getEnclosure()) {
            $fileContent = file_get_contents($filePath);
            $fileContent = str_replace('"', '', $fileContent);
            file_put_contents($filePath, $fileContent);
        }

        return $filePath;
    }

    protected function addHeader(array $csvLines, bool|array $header): void
    {
        if (is_array($header)) {
            $this->addLine($header);
        } elseif ($header === true) {
            $header = array_keys($csvLines[0]);
            $this->addLine($header);
        }
    }

    public function addLine(array $values): void
    {
        fputcsv($this->file, $values, $this->separator, $this->enclosure, $this->escape, $this->eol);
    }
}
