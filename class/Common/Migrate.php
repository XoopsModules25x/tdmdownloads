<?php declare(strict_types=1);

namespace XoopsModules\Tdmdownloads\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Class Migrate synchronize existing tables with target schema
 *
 * @category  Migrate
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class Migrate extends \Xmf\Database\Migrate
{
    private $renameTables;
    private $renameColumns;

    /**
     * Migrate constructor.
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $class = __NAMESPACE__ . '\\' . 'Configurator';

        if (!\class_exists($class)) {
            throw new \RuntimeException("Class '$class' not found");
        }

        $configurator = new $class();

        $this->renameTables = $configurator->renameTables;

        $moduleDirName = \basename(dirname(__DIR__, 2));

        parent::__construct($moduleDirName);
    }


    /**
     * rename table if needed
     */
    private function renameTable()
    {
        foreach ($this->renameTables as $oldName => $newName) {
            if ($this->tableHandler->useTable($oldName) && !$this->tableHandler->useTable($newName)) {
                $this->tableHandler->renameTable($oldName, $newName);
            }
        }
    }

    /**
     * rename columns if needed
     */
    private function renameColumns()
    {
        foreach ($this->renameColumns as $tableName) {
            if ($this->tableHandler->useTable($tableName)) {
                $oldName = $tableName['from'];
                $newName = $tableName['to'];
                $attributes = $this->tableHandler->getColumnAttributes($tableName, $oldName);
                if (false !== \strpos($attributes, ' int(')) {
                    $this->tableHandler->alterColumn($tableName, $oldName, $attributes, $newName);
                }
            }
        }
    }

    /**
     * Perform any upfront actions before synchronizing the schema
     *
     * Some typical uses include
     *   table and column renames
     *   data conversions
     */
    protected function preSyncActions()
    {
        // rename table
        if ($this->renameTables && \is_array($this->renameTables)) {
            $this->renameTable();
        }
        // rename column
        if ($this->renameColumns && \is_array($this->renameColumns)) {
            $this->renameColumns();
        }
    }
}
