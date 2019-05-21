<?php

namespace App\Services;

use App\Enums\EStatus;
use App\Enums\EDateFormat;
use App\Repositories\LoginRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Constant\SessionKey;
use Illuminate\Support\Facades\Session;


class LoginService {
	protected $loginRepository;

	public function __construct(LoginRepository $loginRepository) {
		$this->loginRepository = $loginRepository;
	}
	
	public function login($user, $pass) {
		return $this->loginRepository->login($user, $pass);
	}

	public function getInfoByEmail($email) {
		return $this->loginRepository->getInfoByEmail($email);
	}

	public function loginsdt($user) {
		return $this->loginRepository->loginsdt($user);
	}

	public function check($user) {
		return $this->loginRepository->check($user);
	}

	public function idMax() {
		return $this->loginRepository->idMax();
	}
		
	public function updateInfo($email, $name, $phone, $gender, $dob, $avatar, $id) {
		return $this->loginRepository->updateInfo($email, $name, $phone, $gender, $dob, $avatar, $id);	
	}

	public function getLikedProduct($id) {
		return $this->loginRepository->getLikedProduct($id);	
	}

	public function getLike() {
		return $this->loginRepository->getLike();	
	}

	public function updateLike($id_product, $id_user, $like) {
		return $this->loginRepository->updateLike($id_product, $id_user, $like);	
	}

	public function insertLike($id_product, $id_user, $like) {
		return $this->loginRepository->insertLike($id_product, $id_user, $like);	
	}

	public function getAllOrder($id_KH) {
		return $this->loginRepository->getAllOrder($id_KH);
	}

	public function getUser($id_KH) {
		return $this->loginRepository->getUser($id_KH);
	}

	public function getDetailOrder($id_don_hang) {
		return $this->loginRepository->getDetailOrder($id_don_hang);
	}

	public function updateUserFB($id_fb, $email , $type) {
		return $this->loginRepository->updateUserFB($id_fb, $email , $type);
	}

	public function getInfo($id_fb) {
		return $this->loginRepository->getInfo($id_fb);
	}

	public function loginfb($id_fb) {
		return $this->loginRepository->loginfb($id_fb);
	}
	
	public function create($id_fb, $email) {
		return $this->loginRepository->create($id_fb, $email);
	}

	public function news($page) {
		return $this->loginRepository->news($page);
	}
	
	public function productType($page) {
		return $this->loginRepository->productType($page);
	}

	public function insertCart($id_KH, $ma_sp, $so_luong, $size, $note) {
		return $this->loginRepository->insertCart($id_KH, $ma_sp, $so_luong, $size, $note);
	}

	public function getCart($id_KH) {
		return $this->loginRepository->getCart($id_KH);
	}

	public function deleteCart($id_GH) {
		return $this->loginRepository->deleteCart($id_GH);
	}

	public function deleteCartCustomer($id_KH) {
		return $this->loginRepository->deleteCartCustomer($id_KH);
	}

	public function getQuantity($id_GH) {
		return $this->loginRepository->getQuantity($id_GH);
	}

	public function updateQuantity($id_GH, $sl, $type) {
		return $this->loginRepository->updateQuantity($id_GH, $sl, $type);
	}

	public function getCartOfCustomer($id_KH) {
		return $this->loginRepository->getCartOfCustomer($id_KH);
	}

	public function getInfoCustomer($id_KH) {
		return $this->loginRepository->getInfoCustomer($id_KH);
	}

	public function getEvaluate() {
    	return $this->loginRepository->getEvaluate();
    }

    public function getChildEvaluate($id_SP, $id_Evaluate) {
    	return $this->loginRepository->getChildEvaluate($id_SP, $id_Evaluate);
    }

    public function getEvaluateOfProduct($id_SP) {
    	return $this->loginRepository->getEvaluateOfProduct($id_SP);
    }

    public function getBranch() {
    	return $this->loginRepository->getBranch();
    }

    public function addEvaluate($id_tk, $id_sp, $so_diem, $tieu_de, $noi_dung, $thoi_gian, $hinh_anh, $parent_id){
    	return $this->loginRepository->addEvaluate($id_tk, $id_sp, $so_diem, $tieu_de, $noi_dung, $thoi_gian, $hinh_anh, $parent_id);
    }

    public function addThanks($id_Evaluate, $id_KH) {
    	return $this->loginRepository->addThanks($id_Evaluate, $id_KH);
    }

    public function insertTopping($ma_sp, $ma_topping, $gia_san_pham, $so_luong) {
    	return $this->loginRepository->insertTopping($ma_sp, $ma_topping, $gia_san_pham, $so_luong);
    }

    public function getTopping($ma_sp) {
    	return $this->loginRepository->getTopping($ma_sp);
    }

    public function getStatusOrder($ma_don_hang) {
    	return $this->loginRepository->getStatusOrder($ma_don_hang);
    }

    public function getDetail($ma_don_hang) {
    	return $this->loginRepository->getDetail($ma_don_hang);
    }
}