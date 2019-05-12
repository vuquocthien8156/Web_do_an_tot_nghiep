<?php

namespace App\Repositories;

use App\Enums\EStatus;
use App\Enums\EUser;
use App\Models\Users;
use App\Models\quyen;
use App\Models\phanquyen;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginRepository {

	public function login($user, $pass) {
		$result = DB::table('users as us')->select('us.ten', 'us.id as user_id', 'email', 'password', 'id_vai_tro', 'quyen_he_thong')
        ->leftjoin('PhanQuyen as per', 'per.tai_khoan', '=', 'us.id')
        ->leftjoin('quyen as pe', 'pe.ma_so', '=', 'per.quyen_cho_phep')
        ->where(['email' => $user, 
        		'password' => $pass, 
        		'us.da_xoa' => 0])->get();
		return $result;
	}

	public function loginsdt($user) {
		$result = DB::table('users as us')->select('us.ten', 'us.id as user_id', 'email', 'password', 'id_vai_tro', 'quyen_he_thong')
        ->leftjoin('PhanQuyen as per', 'per.tai_khoan', '=', 'us.id')
        ->leftjoin('quyen as pe', 'pe.ma_so', '=', 'per.quyen_cho_phep')->where('sdt','=', $user)->where('us.da_xoa','=', 0)->get();
		return $result;
	}

	public function check($user) {
		$result = DB::table('users as us')->select('us.id as user_id', 'email', 'password', 'sdt', 'fb_id');
        if ($user != '' && $user != null) {
            $result->where(function($where) use ($user) {
                $where->whereRaw('lower(us.fb_id) like ? ', ['%' . trim(mb_strtolower($user, 'UTF-8')) . '%'])
                	->orwhereRaw('lower(us.email) like ? ', ['%' . trim(mb_strtolower($user, 'UTF-8')) . '%'])
                    ->orWhereRaw('lower(us.sdt) like ? ', ['%' . trim(mb_strtolower($user, 'UTF-8')) . '%']);
                });
        } 
		return $result->get();
	}
	public function updateInfo($email, $name, $phone, $gender, $dob, $avatar, $id) {
		$result = DB::table('users')->where('user_id', '=', $id)
		->update([
			'ten' => $name,
			'sdt' => $phone,
			'gioi_tinh' => $gender,
			'ngay_sinh' =>$dob,
			'avatar' => $avatar
		]);
		return $result;
	}

	public function getLikedProduct($id) {
		$result = DB::table('SanPhamYeuThich')->select(
			'ma_so' ,'ma_chu', 'SanPham.ten', 'gia_san_pham', 'ngay_ra_mat', 'hinh_san_pham'
			 , 'so_lan_dat' , 'gia_vua' , 'gia_lon' , 'mo_ta' )
		->leftjoin('SanPham', 'ma_so', '=', 'ma_san_pham')
		->leftjoin('users', 'id', '=', 'ma_khach_hang')
		->where(['users.id' => $id, 'thich' => 1])->get();
		return $result;
	}

	public function getLike() {
		$result = DB::table('SanPhamYeuThich')->select('ma_san_pham', 'ma_khach_hang', 'thich')->get();
		return $result;	
	}

	public function updateLike($id_product, $id_user, $like) {
		$result = DB::table('SanPhamYeuThich')->where(['ma_san_pham' => $id_product, 'ma_khach_hang' => $id_user])->update(['thich' => $like]);
		return $result;	
	}

	public function insertLike($id_product, $id_user, $like) {
		$result = DB::table('SanPhamYeuThich')->insert([
           'ma_san_pham' => $id_product,
           'ma_khach_hang' => $id_user,
           'thich' => $like,
           'trang_thai' => 0,
        ]);
        return $result;	
	}

	public function getAllOrder($id_KH) {
		$result = DB::table('DonHang')->select('ma_don_hang', 'ma_khach_hang', 'ma_khuyen_mai', 'ngay_lap', 'phi_ship', 'tong_tien', 'ghi_chu');
		if ($id_KH != null && $id_KH != '') {
			$result->where('ma_khach_hang', '=', $id_KH);
		}
		return $result->get();
	}

	public function getUser($id_KH) {
		$result = DB::table('users')->select('id', 'ten', 'sdt', 'dia_chi');
		if ($id_KH != null && $id_KH != '') {
			$result->where('id', '=', $id_KH);
		}
		return $result->get();
	}

	public function updateIdFB($id_fb, $email) {
		$result = DB::table('users')->where('email', '=', $email)
		->update([
			'fb_id' => $id_fb,
		]);
		return $result;
	}

	public function getInfo($id_fb) {
		$result = DB::table('users')->select('ten', 'email', 'sdt' , 'gioi_tinh', 'fb_id','ngay_sinh', 'password')->where('fb_id', '=', $id_fb)->get();
		return $result;
	}
	public function insertPass($id_fb) {
		$pass = md5(123);
		$result = DB::table('users')->where('fb_id', '=', $id_fb)->update([
           'password' => $pass,
        ]);
        return $result;	
	}

	public function loginfb($id_fb, $email) {
		if ($email != null && $email != '') {
			$result = DB::table('users as us')->select('us.ten', 'us.id as user_id', 'email', 'password', 'id_vai_tro', 'quyen_he_thong', 'fb_id')
	        ->leftjoin('PhanQuyen as per', 'per.tai_khoan', '=', 'us.id')
	        ->leftjoin('quyen as pe', 'pe.ma_so', '=', 'per.quyen_cho_phep')
	        ->where([
	        		'email' => $email, 
	        		'fb_id' => $id_fb, 
	        		'us.da_xoa' => 0])->get();
			return $result;
		}else {
			$result = DB::table('users as us')->select('us.ten', 'us.id as user_id', 'email', 'password', 'id_vai_tro', 'quyen_he_thong', 'fb_id')
	        ->leftjoin('PhanQuyen as per', 'per.tai_khoan', '=', 'us.id')
	        ->leftjoin('quyen as pe', 'pe.ma_so', '=', 'per.quyen_cho_phep')
	        ->where([
	        		'fb_id' => $id_fb, 
	        		'us.da_xoa' => 0])->get();
			return $result;
		}

	}

	public function create($id_fb, $email) {
		if ($email != null && $email != '') {
			$result = DB::table('users')->insert([
						   'email' => $email,
				           'fb_id' => $id_fb,
				           'da_xoa' => 0,
       			 ]);
		}else {
			$result = DB::table('users')->insert([
				           'fb_id' => $id_fb,
				           'da_xoa' => 0,
       			 ]);
		}
        return $result;	
	}

	public function news($page) {
		if ($page == null) {
            $result = DB::table('TinTuc')->select('ten_tin_tuc', 'noi_dung', 'ngay_dang', 'hinh_tin_tuc', 'ngay_tao')
	        ->where([
	            'da_xoa' => 0,
	        ]);
        	return $result->limit(4)->orderBy('ngay_tao', 'desc')->get();
        }else {
            $result = DB::table('TinTuc')->select('ten_tin_tuc', 'noi_dung', 'ngay_dang', 'hinh_tin_tuc', 'ngay_tao')
	        ->where([
	            'da_xoa' => 0,
	        ]);
        	return $result->limit(4)->orderBy('ngay_tao', 'desc')->paginate(4);
        }
	}

	public function productType($page) {
		if ($page == null) {
            $result = DB::table('LoaiSanPham')->select('ma_loai_sp', 'ten_loai_sp', 'loai_chinh')
	        ->where([
	            'da_xoa' => 0,
	        ]);
        	return $result->orderBy('ma_loai_sp', 'asc')->get();
        }else {
            $result = DB::table('LoaiSanPham')->select('ma_loai_sp', 'ten_loai_sp', 'loai_chinh')
	        ->where([
	            'da_xoa' => 0,
	        ]);
        	return $result->orderBy('ma_loai_sp', 'asc')->paginate(15);
        }
	}
}