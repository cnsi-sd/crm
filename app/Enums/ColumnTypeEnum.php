<?php


namespace App\Enums;


abstract class ColumnTypeEnum extends AbstractEnum
{
    const TEXT = 'TEXT';
    const SELECT = 'SELECT';
    const BOOLEAN = 'BOOLEAN';
    const ACTIONS = 'ACTIONS';
    const NUMBER = 'NUMBER';
    const DATE = 'DATE';
    const PRICE = 'PRICE';
    const PERCENTAGE = 'PERCENTAGE';
    const FULLTEXT = 'FULLTEXT';
}
