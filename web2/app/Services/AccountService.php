<?php

namespace App\Services;

use App\Enums\EStatus;
use App\Enums\EDateFormat;
use App\Repositories\AccountRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Constant\SessionKey;
use Illuminate\Support\Facades\Session;


class AccountService {
	protected $loginRepository;

	public function __construct(AccountRepository $accountRepository) {
		$this->accountRepository = $accountRepository;
	}

	public function loaiTaiKhoan() {
		return $this->accountRepository->loaiTaiKhoan();
	}

	public function search($name, $phone, $page, $gender, $loai_tai_khoan) {
		return $this->accountRepository->search($name, $phone, $page, $gender, $loai_tai_khoan);
	}

	public function delete($id, $status) {
		return $this->accountRepository->delete($id, $status);
	}

	public function editUser($avatar_path, $ten, $id, $SDT, $NS, $gender, $diemtich, $diachi, $email, $now) {
		return $this->accountRepository->editUser($avatar_path, $ten, $id, $SDT, $NS, $gender, $diemtich, $diachi, $email, $now);
	}
}