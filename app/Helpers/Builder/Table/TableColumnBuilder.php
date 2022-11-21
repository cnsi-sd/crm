<?php


namespace App\Helpers\Builder\Table;

use App\Enums\AlignEnum;
use App\Enums\ColumnTypeEnum;
use App\Enums\FixedWidthEnum;
use Exception;

class TableColumnBuilder
{
    protected $label;
    protected $type = ColumnTypeEnum::TEXT;
    protected $key;
    protected $where_key;
    protected $align;
    protected $fixed_width;
    protected $is_searchable = true;
    protected $options = [];
    protected $is_sortable = true;
    protected $callback;
    protected $class = '';

    /** @var bool  */
    private $isHtml = false;

    /**
     * @param mixed $label
     * @return TableColumnBuilder
     */
    public function setLabel($label): TableColumnBuilder {
        $this->label = $label;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return TableColumnBuilder
     */
    public function setType($type): TableColumnBuilder {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * @param mixed $key
     * @return TableColumnBuilder
     */
    public function setKey($key): TableColumnBuilder {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWhereKey() {
        return $this->where_key;
    }

    /**
     * @param $where_key
     * @return TableColumnBuilder
     */
    public function setWhereKey($where_key): TableColumnBuilder {
        $this->where_key = $where_key;
        return $this;
    }

    /**
     * @param mixed $align
     * @return TableColumnBuilder
     * @throws Exception
     */
    public function setAlign($align): TableColumnBuilder {
        AlignEnum::validOrThrow($align);
        $this->align = $align;
        return $this;
    }

    /**
     * @param mixed $fixed_width
     * @return TableColumnBuilder
     * @throws Exception
     */
    public function setFixedWidth($fixed_width): TableColumnBuilder {
        if(!is_null($fixed_width))
            FixedWidthEnum::validOrThrow($fixed_width);

        $this->fixed_width = $fixed_width;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSearchable(): bool {
        return $this->is_searchable;
    }

    /**
     * @param bool $is_searchable
     * @return TableColumnBuilder
     */
    public function setSearchable(bool $is_searchable): TableColumnBuilder {
        $this->is_searchable = $is_searchable;
        return $this;
    }

    /**
     * @param string $class
     * @return TableColumnBuilder
     */
    public function setClass(string $class): TableColumnBuilder {
        $this->class = $class;
        return $this;
    }

    /**
     * @param array $options
     * @return TableColumnBuilder
     */
    public function setOptions(array $options): TableColumnBuilder {
        $this->options = $options;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array {
        return $this->options;
    }

    /**
     * @param bool $bool
     * @return TableColumnBuilder
     */
    public function setSortable(bool $bool): TableColumnBuilder {
        $this->is_sortable = $bool;
        return $this;
    }


    /**
     * @return bool
     */
    public function isSortable(): bool {
        return $this->is_sortable;
    }

    /**
     * @param mixed $callback
     * @return TableColumnBuilder
     */
    public function setCallback($callback): TableColumnBuilder {
        $this->callback = $callback;
        return $this;
    }

    public function getClass() {
        $class = $this->class;

        if (!is_null($this->align))
            $class .= ' text-' . $this->align;

        if (!is_null($this->fixed_width))
            $class .= ' fixed-width-' . $this->fixed_width;

        return $class;
    }

    public function getValue($object, $use_callback = true) {
        $key = $this->key;

        if($use_callback && !is_null($this->callback) && is_callable($this->callback))
            $value = call_user_func($this->callback, $object);
        else
            $value = $object->$key;

        return $value;
    }

    public static function actions() : TableColumnBuilder
    {
        return (new TableColumnBuilder())
            ->setType(ColumnTypeEnum::ACTIONS)
            ->setFixedWidth(FixedWidthEnum::MD)
            ->setAlign(AlignEnum::CENTER)
            ->setSearchable(false)
            ->setSortable(false)
            ->setCallback(function () {
                return '';
            });
    }

    public static function boolean() : TableColumnBuilder {
        return (new TableColumnBuilder())
            ->setType(ColumnTypeEnum::BOOLEAN)
            ->setFixedWidth(FixedWidthEnum::XS)
            ->setOptions([
                0 => __('app.no'),
                1 => __('app.yes'),
            ])
            ->setAlign(AlignEnum::CENTER);
    }

    public static function id(string $key = 'id') : TableColumnBuilder {
        return (new TableColumnBuilder())
            ->setLabel('#')
            ->setType(ColumnTypeEnum::NUMBER)
            ->setAlign(AlignEnum::CENTER)
            ->setFixedWidth(FixedWidthEnum::SM)
            ->setKey($key);
    }

    public function setIsHtml(bool $bool = false) {
        $this->isHtml = $bool;
        return $this;
    }

    public function isHtml() {
        return $this->isHtml;
    }
}
