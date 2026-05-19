<?php

namespace App\Livewire\Auth;

use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SetTransactionPin extends Component
{
    public $pin = '';
    public $pin_confirmation = '';
    public $showModal = false;

    protected $userService;

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function mount()
    {
        $this->checkPinStatus();
    }

    public function checkPinStatus()
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Nếu chưa có password2 thì hiện modal
            if (empty($user->password2)) {
                $this->showModal = true;
            } else {
                $this->showModal = false;
            }
        }
    }

    public function savePin()
    {
        $this->validate(
            [
                'pin' => [
                    'required',
                    'numeric',
                    'digits:6',
                    'confirmed', // checks matching pin_confirmation
                ],
            ],
            [
                'pin.required' => 'Mã PIN không được để trống',
                'pin.numeric' => 'Mã PIN phải là số',
                'pin.digits' => 'Mã PIN phải có 6 số',
                'pin.confirmed' => 'Mã PIN không khớp',
            ]
        );

        $result = $this->userService->setTransactionPin(Auth::id(), $this->pin);

        if ($result->isError()) {
            session()->flash('error', $result->getMessage());
            return;
        }

        $this->showModal = false;

        // Dispatch event or notification if needed
        $this->dispatch('pin-updated');

        // Có thể reload trang để app nhận diện state mới
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.set-transaction-pin');
    }
}
