<?php


namespace App\Doctrine;


use App\Logger;
use Doctrine\ORM\UnitOfWork;

class FunctionnalLogger
{
    const REAL_HAST = [
        'R2-D2',
        'CHEWBACCA',
        'C-3PO',
        'LUKE',
        'DARTH VADER',
        'CAMIE',
        'BIGGS',
        'LEIA',
        'BERU',
        'OWEN',
        'OBI-WAN',
        'MOTTI',
        'TARKIN',
        'HAN SOLO',
        'GREEDO',
        'JABBA',
        'DODONNA',
        'GOLD LEADER',
        'WEDGE',
        'RED LEADER',
        'RED TEN',
        'GOLD FIVE',
        'YODA',
        'BOBA FETT',
        'PALPATINE',
        'DARK MAUL',
        'REY',
        'MAZ KANATA',
        'FINN',
        'LANDO',
        'WAMPA',
        'WATTO',
    ];

    const COLOR_HAST = [
        -1 => Logger::COLOR_BLACK, // Black,
        0  => Logger::COLOR_RED, // Rouge
        1  => Logger::COLOR_GREEN, // Vert
        2  => Logger::COLOR_YELLOW, // Jaune
        3  => Logger::COLOR_BLUE, // Bleu
        4  => Logger::COLOR_VIOLET, // Violet
        5  => Logger::COLOR_BLUE_LIGHT, // Bleu clair
        6  => Logger::COLOR_WHITE, // Blanc
    ];

    const ENTITY_IDENTIFIER = 1;
    const ORIGINAL_ENTITY_DATA = 2;
    const ENTITY_CHANGESET = 3;
    const ENTITY_STATE = 4;
    const EXTRA_UPDATE = 5;
    const ENTITY_INSERT = 6;
    const ENTITY_UPDATE = 7;
    const ENTITY_DELETE = 8;
    const IDENTIY_MAP_CLASS = 9;
    const IDENTIY_MAP_OBJECT = 10;
    const ENTITY_ORPHAN_REMOVAL = 11;

    const LITERAL_STATE = [
        UnitOfWork::STATE_DETACHED => 'DETACHED',
        UnitOfWork::STATE_NEW => 'NEW',
        UnitOfWork::STATE_REMOVED => 'REMOVED',
        UnitOfWork::STATE_MANAGED => 'MANAGED',
    ];

    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var string
     */
    private $type;

    private $last;

    private static $lastClass;
    /**
     * @var ResolveSplObjectHash
     */
    private $resolveSplObjectHash;

    private static $indexHash = 0;
    private static $indexColor = 0;
    private static $mapColor = [];
    private static $mapHash = [];

    public function __construct(Logger $logger, int $type, ResolveSplObjectHash $resolveSplObjectHash)
    {
        $this->logger = $logger;
        $this->type = $type;
        $this->resolveSplObjectHash = $resolveSplObjectHash;
    }

    public static function getLiteralHash($hash)
    {
        if (isset(self::$mapHash[$hash])) {
            return self::$mapHash[$hash];
        }

        if (!isset(self::REAL_HAST[self::$indexHash])) {
            return $hash;
        }

        self::$mapHash[$hash] = self::REAL_HAST[self::$indexHash];
        self::$indexHash++;

        return self::$mapHash[$hash];
    }

    public static function getColorHash($hash)
    {
        if (isset(self::$mapColor[$hash])) {
            return self::$mapColor[$hash];
        }

        if (!isset(self::COLOR_HAST[self::$indexColor])) {
            return $hash;
        }

        self::$mapColor[$hash] = self::COLOR_HAST[self::$indexColor];
        self::$indexColor++;

        return self::$mapColor[$hash];
    }

    public function set($index, $value)
    {
        if (self::ENTITY_INSERT === $this->type) {
            $this->logger->log('INFO.schedule', 'one object ({class}) has scheduled for insert', [
                '{class}' => $this->dumpObject($value)
            ]);
        }
        if (self::ENTITY_UPDATE === $this->type) {
            $this->logger->log('INFO.schedule', 'one object ({class}) has scheduled for update', [
                '{class}' => $this->dumpObject($value)
            ]);
        }
        if (self::ENTITY_DELETE === $this->type) {
            $this->logger->log('INFO.schedule', 'one object ({class}) has scheduled for delete', [
                '{class}' => $this->dumpObject($value)
            ]);
        }
        if (self::ORIGINAL_ENTITY_DATA === $this->type) {
            // Entity by hash
            if (strrpos($index, '000000') !== false) {
                $this->logger->log('INFO.original', 'set original data of ({class}) with {body}', [
                    '{class}' => $this->dumpObject($this->resolveSplObjectHash->resolve($index), $index),
                    '{body}' => json_encode($value),
                ], self::getColorHash($index));
            } else { // Specified field in entity
                $this->logger->log('INFO.original', 'add in original data of ({class}) field {field} with value {value}', [
                    '{class}' => $this->dumpObject($this->last),
                    '{field}' => $index,
                    '{value}' => $this->dumpScalar($value)
                ]);
            }
        }
        if (self::IDENTIY_MAP_CLASS === $this->type) {
            $this->logger->log('INFO.identity', 'add to identitymap ({class}) with identifier {identifier}', [
                '{class}' => $this->dumpObject($value),
                '{identifier}' => $index
            ]);
        }
        if (self::ENTITY_CHANGESET === $this->type) {
            $this->logger->log('INFO.changeset', 'update changeset of ({class}) with {body}', [
                '{class}' => $this->dumpObject($this->resolveSplObjectHash->resolve($index)),
                '{body}' => json_encode($value)
            ]);
        }
        if (self::ENTITY_ORPHAN_REMOVAL === $this->type) {
            $this->logger->log('INFO.orphan', 'one object ({class}) has scheduled for orphan removal', [
                '{class}' => $this->dumpObject($value)
            ]);
        }
        if (self::ENTITY_STATE === $this->type) {
            $this->logger->log('INFO.state', '{class} is {state}', [
                '{class}' => $this->dumpObject($this->resolveSplObjectHash->resolve($index), $index),
                '{state}' => self::LITERAL_STATE[$value]
            ]);
        }
    }

    public function dumpScalar($scalar)
    {
        if (is_array($scalar)) {
            if ($this->dumpScalar) {
                return 'array ('.json_encode($scalar).')';
            }

            return 'array';
        }

        if (is_object($scalar)) {
            return 'object=' . get_class($scalar) . ' (id=' . $scalar->getId() . ')';
        }

        if (is_null($scalar)) {
            return 'NULL';
        }

        return gettype($scalar) . '=' . $scalar;
    }

    public function dumpObject($scalar, $hash = null)
    {
        if (is_null($scalar) && $hash !== null) {
            return 'null (hash='.self::getLiteralHash($hash).')';
        }

        if (is_object($scalar)) {
            return get_class($scalar) . ' (id=' . ($scalar->getId() ?: 'null') . ', hash='.self::getLiteralHash(spl_object_hash($scalar)).')';
        }

        return 'null';
    }

    public function get($index)
    {
        if (self::ORIGINAL_ENTITY_DATA === $this->type) {
            // Entity by hash
            if (strrpos($index, '000000') !== false) {
                $this->last = $this->resolveSplObjectHash->resolve($index);
            }
        }

        if (self::IDENTIY_MAP_CLASS === $this->type) {
            $this->logger->log('INFO.identity', 'search idendited {class} with identifier {identifier} (return same instance)', [
                '{class}' => $index[0],
                '{identifier}' => $index[1]
            ]);
        }
    }

    public static function onClear()
    {
        self::$mapHash = [];
        self::$indexHash = 1;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return ResolveSplObjectHash
     */
    public function getResolveSplObjectHash()
    {
        return $this->resolveSplObjectHash;
    }
}