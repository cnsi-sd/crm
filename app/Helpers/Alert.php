<?php

namespace App\Helpers;

abstract class Alert
{
    public static function modalSuccess($message, $title = 'Succès !', bool $persistentAlert = false): void
    {
        self::modal('success', $title, $message, $persistentAlert);
    }

    public static function modalConfirm($message, $title = 'Confirmation')
    {
        return alert()->question($title, $message)->showCancelButton(__('app.cancel'), '#aaa')->showConfirmButton('Confirm', '#3085d6');
    }

    public static function modalInfo($message, $title = 'Information', bool $persistentAlert = false): void
    {
        self::modal('info', $title, $message, $persistentAlert);
    }

    public static function modalWarning($message, $title = 'Attention !', bool $persistentAlert = false): void
    {
        self::modal('warning', $title, $message, $persistentAlert);
    }

    public static function modalError($message, $title = 'Erreur !', bool $persistentAlert = true): void
    {
        self::modal('error', $title, $message, $persistentAlert);
    }

    protected static function modal(string $type, string $title, string $message, bool $persistentAlert = true): void
    {
        if ($persistentAlert)
            alert()->$type($title, $message)->persistent(false, false);
        else
            alert()->$type($title, $message);
    }

    public static function toastSuccess($title)
    {
        toast($title, 'success');
    }

    public static function toastInfo($title)
    {
        toast($title, 'info');
    }

    public static function toastWarning($title)
    {
        toast($title, 'warning');
    }

    public static function toastError($title)
    {
        toast($title, 'error');
    }

    public static function htmlSuccess($message, $title = 'Succès !')
    {
        alert()->html($title, $message, 'success');
    }

    public static function htmlInfo($message, $title = 'Information')
    {
        alert()->html($title, $message, 'info');
    }

    public static function htmlWarning($message, $title = 'Attention !')
    {
        alert()->html($title, $message, 'warning');
    }

    public static function htmlError($message, $title = 'Erreur !')
    {
        alert()->html($title, $message, 'error');
    }
}
