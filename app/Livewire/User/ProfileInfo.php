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

    // Password2 change fields
    public $new_password2 = '';
    public $new_password2_confirmation = '';
    public $showPassword2Form = false;

    protected $userService;

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            $this->name = $user->name;
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
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được sử dụng.',
        ];

        // If new password is provided, require current password and confirmation
        if (!empty($this->new_password)) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['new_password'] = ['required', 'min:8', 'confirmed'];

            $messages['current_password.required'] = 'Vui lòng nhập mật khẩu hiện tại để đổi mật khẩu.';
            $messages['current_password.current_password'] = 'Mật khẩu hiện tại không đúng.';
            $messages['new_password.required'] = 'Vui lòng nhập mật khẩu mới.';
            $messages['new_password.min'] = 'Mật khẩu mới phải có ít nhất 8 ký tự.';
            $messages['new_password.confirmed'] = 'Xác nhận mật khẩu mới không khớp.';
        }

        $this->validate($rules, $messages);

        $data = [
            'email' => $this->email,
            'phone' => $this->phone,
        ];

        // Only update password if provided
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

    public function togglePassword2Form()
    {
        $this->showPassword2Form = !$this->showPassword2Form;
        $this->reset(['new_password2', 'new_password2_confirmation']);
        $this->resetValidation(['new_password2', 'new_password2_confirmation']);
    }

    public function changePassword2()
    {
        $user = Auth::user();

        $rules = [
            'new_password2' => ['required', 'numeric', 'digits:6', 'confirmed'],
        ];

        $messages = [
            'new_password2.required' => 'Vui lòng nhập mật khẩu cấp 2 mới.',
            'new_password2.numeric' => 'Mật khẩu cấp 2 phải là số.',
            'new_password2.digits' => 'Mật khẩu cấp 2 phải có 6 số.',
            'new_password2.confirmed' => 'Xác nhận mật khẩu cấp 2 không khớp.',
        ];

        $this->validate($rules, $messages);

        $result = $this->userService->setTransactionPin(
            Auth::id(),
            $this->new_password2
        );

        if ($result->isError()) {
            $this->addError('new_password2', $result->getMessage());
            return;
        }

        $this->reset(['new_password2', 'new_password2_confirmation']);
        $this->showPassword2Form = false;

        session()->flash('success', 'Đổi mật khẩu cấp 2 thành công!');
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
