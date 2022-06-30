<?php
/**
 * @package azure-sdk-for-php
 * @author Simon Karlen <simi.albi@outlook.com>
 */

namespace Azure\Core;

class ConnectionString
{
    private $_pairs;
    private $_pairSeparator;
    private $_keywordValueSeparator;

    /**
     * @param array $pairs
     * @param string $pairSeparator
     * @param string $keywordValueSeparator
     */
    private function __construct(array $pairs, string $pairSeparator, string $keywordValueSeparator)
    {
        $this->_pairs = $pairs;
        $this->_pairSeparator = $pairSeparator;
        $this->_keywordValueSeparator = $keywordValueSeparator;
    }

    /**
     * Get the value for a keyword. Throws an exception if the value does not exists.
     * @param string $keyword The keyword to get the value for.
     * @return string
     * @throws \Exception
     */
    public function getRequired(string $keyword): string
    {
        if (!isset($this->_pairs[$keyword])) {
            throw new \Exception("Required keyword '$keyword' is missing in connection string.");
        }

        return $this->_pairs[$keyword];
    }

    /**
     * Get the value for a keyword. If the keyword does not exits returns null.
     * @param string $keyword The keyword to get the value for.
     * @return string|null
     */
    public function getNonRequired(string $keyword): ?string
    {
        return $this->_pairs[$keyword] ?? null;
    }

    /**
     * Get the value for a keyword by reference. Returns bool if the value exists.
     * @param string $keyword The keyword to get the value for.
     * @param string|null $value The var where the value gets assigned.
     * @return bool
     */
    public function tryGetSegmentValues(string $keyword, ?string &$value): bool
    {
        $value = $this->getNonRequired($keyword);

        return isset($this->_pairs[$keyword]);
    }

    /**
     * Get the value for a keyword. If the keyword does not exists returns passed default value.
     * @param string $keyword The keyword to get the value for.
     * @param string $defaultValue The default value if keyword does not exists.
     * @return string
     */
    public function getSegmentValueOrDefault(string $keyword, string $defaultValue): string
    {
        return $this->_pairs[$keyword] ?? $defaultValue;
    }

    /**
     * Check if a keyword exists.
     * @param string $keyword The keyword to check.
     * @return bool
     */
    public function containsSegmentKey(string $keyword): bool
    {
        return array_key_exists($keyword, $this->_pairs);
    }

    /**
     * Replace a value for given keyword if keyword exists.
     * @param string $keyword The keyword to replace the value for.
     * @param string $value The new value.
     * @return void
     */
    public function replace(string $keyword, string $value)
    {
        if ($this->containsSegmentKey($keyword)) {
            $this->_pairs[$keyword] = $value;
        }
    }

    /**
     * Add a keyword value pair if keyword not exists.
     * @param string $keyword The keyword to add.
     * @param string $value The value for the keyword.
     * @return void
     */
    public function add(string $keyword, string $value)
    {
        if (!$this->containsSegmentKey($keyword)) {
            $this->_pairs[$keyword] = $value;
        }
    }

    /**
     * To string magic method.
     * @return string
     */
    public function __toString(): string
    {
        if (empty($this->_pairs)) {
            return '';
        }

        $stringBuilder = '';
        $isFirst = true;
        foreach ($this->_pairs as $key => $value) {
            if ($isFirst) {
                $isFirst = false;
            } else {
                $stringBuilder .= $this->_pairSeparator;
            }

            $stringBuilder .= $key;
            if ($value) {
                $stringBuilder .= $this->_keywordValueSeparator . $value;
            }
        }

        return $stringBuilder;
    }

    /**
     * Initialize a new ConnectionString by parsing a string.
     * @param string $connectionString The connection string to parse.
     * @param string $segmentSeparator The segment separator. Defaults to ";".
     * @param string $keywordValueSeparator The keyword separator. Defaults to "=".
     * @param bool $allowEmptyValues Whether or not to allow empty values.
     * @return static
     * @throws \Exception
     */
    public static function parse(string $connectionString, string $segmentSeparator = ';', string $keywordValueSeparator = '=', bool $allowEmptyValues = false): self
    {
        self::validate($connectionString, $segmentSeparator, $keywordValueSeparator, $allowEmptyValues);

        return new self(self::parseSegments($connectionString, $segmentSeparator, $keywordValueSeparator), $segmentSeparator, $keywordValueSeparator);
    }

    private static function parseSegments(string $connectionString, string $separator, string $keywordValueSeparator): array
    {
        $pairs = [];

        $segmentStart = -1;
        $segmentEnd = 0;

        while (self::tryGetNextSegment($connectionString, $separator, $segmentStart, $segmentStart)) {
            $kvSeparatorIndex = strpos($connectionString, $keywordValueSeparator, $segmentEnd - $segmentStart);
            $keywordStart = self::getStart($connectionString, $segmentStart);
            $keyLength = self::getLength($connectionString, $segmentStart, $kvSeparatorIndex);

            $keyword = substr($connectionString, $keywordStart, $keyLength);
            if (array_key_exists($keyword, $pairs)) {
                throw new \Exception("Duplicated keyword '$keyword'");
            }

            $valueStart = self::getStart($connectionString, $kvSeparatorIndex + strlen($keywordValueSeparator));
            $valueLength = self::getLength($connectionString, $valueStart, $segmentEnd);
            $pairs[$keyword] = substr($connectionString, $valueStart, $valueLength);
        }

        return $pairs;
    }

    private static function getStart(string $str, int $start): int
    {
        while ($start < strlen($str) && \IntlChar::isWhitespace($str[$start])) {
            $start++;
        }

        return $start;
    }

    private static function getLength(string $str, int $start, int $end): int
    {
        while ($end > $start && \IntlChar::isWhitespace($str[$end - 1])) {
            $end--;
        }

        return $end - $start;
    }

    /**
     * Validate a connection string.
     * @param string $connectionString
     * @param string $segmentSeparator
     * @param string $keywordValueSeparator
     * @param bool $allowEmptyValues
     * @return void
     * @throws \Exception
     */
    private static function validate(string $connectionString, string $segmentSeparator = ';', string $keywordValueSeparator = '=', bool $allowEmptyValues = false)
    {
        $segmentStart = -1;
        $segmentEnd = 0;

        while (self::tryGetNextSegment($connectionString, $segmentSeparator, $segmentStart, $segmentEnd)) {
            if ($segmentStart === $segmentEnd) {
                if ($segmentStart === 0) {
                    throw new \Exception("Connection string starts with separator '$segmentSeparator'.");
                }

                throw new \Exception("Connection string contains to following separators '$segmentSeparator'.");
            }

            $kvSeparatorIndex = strpos($connectionString, $keywordValueSeparator, $segmentEnd - $segmentStart);
            if ($kvSeparatorIndex === false) {
                throw new \Exception("Connection string doesn't have value for keyword '" . substr($connectionString, $segmentStart, $segmentEnd - $segmentStart) . "'.");
            }

            if ($segmentStart === $kvSeparatorIndex) {
                throw new \Exception("Connection string has value '" . substr($connectionString, $segmentStart, $kvSeparatorIndex - $segmentStart) . "' with no keyword.");
            }

            if (!$allowEmptyValues && $kvSeparatorIndex + 1 === $segmentEnd) {
                throw new \Exception("Connection string has keyword '" . substr($connectionString, $segmentStart, $kvSeparatorIndex - $segmentStart) . "' with empty value.");
            }
        }
    }

    /**
     * @param string $str
     * @param string $separator
     * @param int $start
     * @param int $end
     * @return bool
     */
    private static function tryGetNextSegment(string $str, string $separator, int &$start, int &$end): bool
    {
        if ($start === -1) {
            $start = 0;
        } else {
            $start = $end + strlen($separator);
            if ($start >= strlen($str)) {
                return false;
            }
        }

        $end = strpos($str, $separator, $start);
        if ($end === false) {
            $end = strlen($str);
        }

        return true;
    }
}
