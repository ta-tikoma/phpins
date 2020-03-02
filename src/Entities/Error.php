<?php

namespace Phpins\Entities;

class Error
{
    const TYPE_ERROR   = 'ERROR';
    const TYPE_WARNING = 'WARNING';
    const TYPE_NOTIFY  = 'NOTIFY';

    /**
     * Отступ от начала файла
     * @var int
     */
    public $offset;

    /**
     * Номер строки
     * @var int
     */
    public $line;

    /**
     * Отступ от начала строки
     * @var int
     */
    public $col;

    /**
     * Тип ошибки
     * @var string TYPE_ERROR|TYPE_WARNING|TYPE_NOTIFY
     */
    public $type;

    /**
     * Описание ошибки
     * @var string
     */
    public $description;

    public function __construct($offset, $type, $description)
    {
        $this->offset = $offset;
        $this->type = $type;
        $this->description = $description;
    }

    /**
     * Вывод ошибки
     * @param  File $file
     */
    public function print(File $file)
    {
        $this->line = substr_count($file->content, "\n", 0, $this->offset) + 1;
        $this->col = $this->offset - strrpos(substr($file->content, 0, $this->offset), "\n");

        echo "{$this->type}:{$file->path}:{$this->line}:{$this->col}:{$this->description}\n";
    }
}
