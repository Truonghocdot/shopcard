<?php

namespace App\Livewire\User;

use App\Services\UserService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProfileInfo extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    protected $userService;

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->name  = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
        }
    }

    public function updateProfile()
    {
        $rules = [
            'email' => 'required|email|max:150|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ];

        $messages = [
            'email.required' => __('profile.email_required'),
            'email.email'    => __('profile.email_invalid'),
            'email.unique'   => __('profile.email_taken'),
        ];

        if (!empty($this->new_password)) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['new_password']      = ['required', 'min:8', 'confirmed'];

            $messages['current_password.required']         = __('profile.current_password_required');
            $messages['current_password.current_password'] = __('profile.current_password_wrong');
            $messages['new_password.required']             = __('profile.new_password_required');
            $messages['new_password.min']                  = __('profile.new_password_min');
            $messages['new_password.confirmed']            = __('profile.new_password_confirmed');
        }

        $this->validate($rules, $messages);

        $data = [
            'email' => $this->email,
            'phone' => $this->phone,
        ];

        if ($this->new_password) {
            $data['new_password'] = $this->new_password;
        }

        $result = $this->userService->updateProfile(Auth::id(), $data);

        if ($result->isError()) {
            session()->flash('error', $result->getMessage());
            return;
        }

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('success', $result->getMessage());
    }

    public function getAffiliateUrlProperty(): string
    {
        return route('register') . '?ref=' . Auth::id();
    }

    public function render()
    {
        return view('livewire.user.profile-info');
    }
}
