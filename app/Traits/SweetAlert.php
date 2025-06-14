<?php

namespace App\Traits;

trait SweetAlert
{
    public function alertSuccess($message, $modalId = null): void
    {
        $this->dispatch('alert-success', message: $message, modalId: $modalId);
        // $this->dispatch('refreshDatatable');
        // $this->dispatch('reset-form');
        $this->dispatch('refreshDatatable');
        $this->dispatch('refreshComponent');
    }


    public function alertWarning($message, $modalId = null): void
    {
        $this->dispatch('alert-warning', message: $message, modalId: $modalId);
    }


    public function alertError($message, $modalId = null): void
    {
        $this->dispatch('alert-error', message: $message, modalId: $modalId);
    }


    public function alertInfo($message, $modalId = null): void
    {
        $this->dispatch('alert-info', message: $message, modalId: $modalId);
    }

    public function alertConfirm($message, $action): void
    {
        $this->dispatch('alert-warning', message: $message, action: $action);
        $this->dispatch('refreshDatatable');
    }
}
