<?php

namespace App\Http\Livewire\Extensions\GloverWebsite\Livewire;

use App\Models\UserToken;
use App\Models\Vendor;
use App\Models\VendorManager;
use App\Models\User;
use App\Traits\AutocompleteTrait;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Exception;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use GeoSot\EnvEditor\Facades\EnvEditor;

class BaseLivewireComponent extends Component
{
    use WithPagination, WithFileUploads;
    use AutocompleteTrait;
    use LivewireAlert;

    public $perPage = 12;
    public $model;
    public $selectedModel;

    protected $listeners = [
        'showCreateModal' => 'showCreateModal',
        'showEditModal' => 'showEditModal',
        'showDetailsModal' => 'showDetailsModal',
        'showAssignModal' => 'showAssignModal',
        'initiateEdit' => 'initiateEdit',
        'initiateDelete' => 'initiateDelete',
        'removeModel' => 'removeModel',
        'dismissModal' => 'dismissModal',
        'refreshView' => '$refresh',
        'changeFCMToken' => 'changeFCMToken',
        'refreshDataTable' => 'refreshDataTable',
        'openNewTab' => 'openNewTab',
    ];


    public function refreshDataTable()
    {
        $this->emit('refreshTable');
    }

    //Alert
    public function showSuccessAlert($message = "", $time = 3000)
    {
        $this->alert('success', "", [
            'position'  =>  'center',
            'text' => $message,
            'toast'  =>  false,
            "timer" => $time,
            'cancelButtonText' => __('Cancel'),
        ]);
    }

    public function showWarningAlert($message = "", $time = 3000)
    {
        $this->alert('warning', "", [
            'position'  =>  'center',
            'text' => $message,
            'toast'  =>  false,
            "timer" => $time,
            'cancelButtonText' => __('Cancel'),
        ]);
    }

    public function showErrorAlert($message = "", $time = 3000)
    {
        $this->alert('error', "", [
            'position'  =>  'center',
            'text' => $message,
            'toast'  =>  false,
            "timer" => $time,
            'cancelButtonText' => __('Cancel'),
        ]);
    }

    // toasts
    public function toast($message, $type = 'success', $title = "", $options = [])
    {
        $this->alert($type, $title, [
            'position'  =>  'top-end',
            'text' => $message,
            'toast'  =>  true,
            "timer" => 3000,
            //hide the cancel button
            'showCancelButton' => false,
            //remove the actions space
            'showConfirmButton' => false,
        ]);
    }

    public function toastSuccess($message, $title = "", $options = [])
    {
        $this->toast($message, 'success', $title, $options);
    }

    public function toastError($message, $title = "", $options = [])
    {
        $this->toast($message, 'error', $title, $options);
    }

    public function toastWarning($message, $title = "", $options = [])
    {
        $this->toast($message, 'warning', $title, $options);
    }
    //END toasts

    public function openNewTab($link)
    {
        return $this->emitUp($link);
    }


    // Modal management
    public $showCreate = false;
    public $showEdit = false;
    public $showDetails = false;
    public $showAssign = false;
    public $stopRefresh = false;
    public function showCreateModal()
    {
        $this->resetErrorBag();
        $this->showCreate = true;
        $this->stopRefresh = true;
    }

    public function showEditModal()
    {
        $this->resetErrorBag();
        $this->showEdit = true;
        $this->stopRefresh = true;
    }

    public function showDetailsModal($id)
    {
        $this->selectedModel = $this->model::find($id);
        $this->showDetails = true;
        $this->stopRefresh = true;
    }



    public function dismissModal()
    {
        $this->showCreate = false;
        $this->showEdit = false;
        $this->stopRefresh = false;
        $this->reset();
    }

    public function closeModal()
    {
        $this->showCreate = false;
        $this->showEdit = false;
        $this->stopRefresh = false;
    }


    //
    public function isDemo()
    {
        if (!App::environment('production')) {
            throw new Exception(__("App is in demo version. Some changes can't be made"));
        };
    }


    public function setEnvKey($key, $value)
    {
        if (EnvEditor::keyExists($key)) {
            EnvEditor::editKey($key, $value);
        } else {
            EnvEditor::addKey($key, $value);
        }
    }




    //FCM
    public $fcmToken;
    public function changeFCMToken($token)
    {
        $this->fcmToken = $token;
        if (Auth::check() && !empty($this->fcmToken)) {
            //
            UserToken::updateOrCreate(
                ['token' => $this->fcmToken],
                ['user_id' => Auth::id()]
            );
        }
    }


    public function genColor()
    {
        return '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
    }


    //current location
    public function getSavedLocation()
    {
        $currentLocation = \Session::get('location');
        return $currentLocation;
    }
}
