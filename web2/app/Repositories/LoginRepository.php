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
		$result = DB::table('users as us')->select('us.ten', 'us.id as user_id', 'email', 'gioi_tinh' ,'sdt', 'diem_tich' , 'ngay_sinh' , 'dia_chi' , 'fb_id' , 'avatar' , 'password','loai_tai_khoan')
        ->where(['email' => $user, 
        		'password' => $pass, 
        		'us.da_xoa' => 0])
        ->where('loai_tai_khoan', '!=', 1)->get();
		return $result;
	}

	public function loginAPI($user, $pass) {
		$result = DB::table('users as us')->select('us.ten', 'us.id as user_id', 'email', 'gioi_tinh' ,'sdt', 'diem_tich' , 'ngay_sinh' , 'dia_chi' , 'fb_id' , 'avatar' , 'password')
        ->where(['email' => $user, 
        		'password' => $pass, 
        		'us.da_xoa' => 0])->get();
		return $result;
	}

	public function getInfoByEmail($email) {
		$result = DB::table('users')->select('id as user_id','ten', 'email', 'sdt' , 'gioi_tinh', 'fb_id', 'diem_tich' , 'ngay_sinh' , 'avatar' , 'password')->where('email', '=', $email)->get();
		return $result;
	}

	public function loginsdt($user) {
		$result = DB::table('users as us')->select('us.ten', 'us.id as user_id', 'email', 'gioi_tinh' ,'sdt', 'diem_tich' , 'ngay_sinh' , 'dia_chi' , 'fb_id' , 'avatar' , 'password' )
        ->where('sdt','=', $user)
        ->where('us.da_xoa','=', 0)->get();
		return $result;
	}

	public function check($user) {
		$result = DB::table('users as us')->select('us.id as user_id', 'email', 'password', 'sdt', 'fb_id');
        if ($user != '' && $user != null) {
            $result->where(function($where) use ($user) {
                $where->where('us.email', '=' ,$user)
                    ->orWhere('us.sdt' , '=' ,$user)
                    ->orWhere('us.fb_id' , '=' , $user);
                });
        } 
		return $result->get();
	}
	
	public function updateInfo($email, $name, $phone, $gender, $dob, $avatar, $id) {
		$result = DB::table('users')->where('id', '=', $id)
		->update([
			'email' => $email,
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
			'ma_so' ,'ma_chu', 'SanPham.ten', 'gia_san_pham', 'ngay_ra_mat', 'hinh_san_pham', 'gia_vua' , 'gia_lon' , 'mo_ta' , 'lsp.loai_chinh' )
		->leftjoin('SanPham', 'ma_so', '=', 'ma_san_pham')
        ->leftjoin('LoaiSanPham as lsp', 'lsp.ma_loai_sp', '=', 'SanPham.loai_sp')
		->leftjoin('users', 'id', '=', 'ma_khach_hang')
		->where(['users.id' => $id, 'thich' => 1])->get();
		return $result;
	}

	public function getAddressOrderUser($idAccount , $main) {
		$condition = ['ma_tai_khoan' => $idAccount ,'da_xoa' => 0];
		if($main == 1)
			$condition['chinh'] = 1;	

		$result = DB::table('SoDiaChi')->select('id', 'ten_nguoi_nhan' , 'dia_chi' , 'so_dien_thoai' ,'chinh')->where($condition)->get();
		

		return $result;	
	}

	public function insertAddressOrderUser($ma_tai_khoan , $ten_nguoi_nhan , $dia_chi , $so_dien_thoai , $chinh) {
		
		if($chinh == 1){
			DB::table('SoDiaChi')->update(['chinh' => 0]);
		}
		$result = DB::table('SoDiaChi')->insert([
           'ma_tai_khoan' => $ma_tai_khoan,
           'ten_nguoi_nhan' => $ten_nguoi_nhan,
           'dia_chi' => $dia_chi,
           'chinh' => $chinh,
           'so_dien_thoai' => $so_dien_thoai,
           'da_xoa' => 0,
        ]) ;
		
		
        return $result;	
	}

	public function updateAddressOrderUser($id , $ten_nguoi_nhan , $dia_chi ,  $so_dien_thoai  , $chinh , $da_xoa) {
		
		$result = DB::table('SoDiaChi')->where(['id' => $id]);

		if($chinh != -1){
			if($chinh == 1){
				DB::table('SoDiaChi')->update(['chinh' => 0]);
			}
			$result = $result->update(['ten_nguoi_nhan' => $ten_nguoi_nhan,
					  'dia_chi' => $dia_chi,
					  'chinh' => $chinh,
					  'so_dien_thoai' => $so_dien_thoai,
					  'da_xoa' =>  $da_xoa]);
		}
		else{
			$result = $result->update(['ten_nguoi_nhan' => $ten_nguoi_nhan,
					  'dia_chi' => $dia_chi,
					  'so_dien_thoai' =>  $so_dien_thoai,
					  'da_xoa' =>  $da_xoa]);
		}
		return $result;	
	}

	public function updateNumberPhone($idUser , $numberPhone){
		$result = DB::table('users')->where(['id' => $idUser])
			->update(['sdt' => $numberPhone]);

		return $result;	
	}

	public function updatePassword($idUser , $password){
		$result = DB::table('users')->where(['id' => $idUser])
			->update(['password' => $password]);

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
           'trang_thai' => 1,
        ]);
        return $result;	
	}

	public function getAllOrder($id_KH) {
		$result = DB::table('DonHang as dh')->select('dh.ma_don_hang', 'ma_khach_hang', 'thong_tin_giao_hang' , 'khuyen_mai', 'ten_khuyen_mai' ,'ngay_lap', 'phi_ship', 'tong_tien', 'ghi_chu' , 'phuong_thuc_thanh_toan' , 'so_diem' , 'ma_chu', 'tong_tien_khuyen_mai')
		->leftjoin('LichSuDiem as lsd', 'lsd.ma_don_hang', '=', 'dh.ma_don_hang')
		->leftjoin('KhuyenMai as km', 'km.ma_khuyen_mai', '=', 'dh.khuyen_mai');
			// ->leftjoin('ChiTietTrangThaiDonHang as ctttdh', 'ctttdh.ma_don_hang', '=', 'dh.ma_don_hang')
			// ->leftjoin('TrangThaiDonHang as ttdh', 'ttdh.ma_trang_thai', '=', 'ctttdh.trang_thai');
		if ($id_KH != null && $id_KH != '') {
			// $result->where('ma_khach_hang', '=', $id_KH);
			$result->where(['ma_khach_hang' => $id_KH, 'dh.da_xoa' => 0]);
		}
		return $result->orderBy('ngay_lap', 'desc')->get();
	}

	public function getDetail($ma_don_hang) {
		$result = DB::table('ChiTietDonHang as ctdh')->select('ma_chi_tiet', 'ma_san_pham', 'ten' ,'so_luong', 'don_gia', 'kich_co', 'gia_khuyen_mai', 'thanh_tien', 'ghi_chu')->join('SanPham', 'ma_so', '=', 'ma_san_pham')
			->where('ctdh.ma_don_hang', '=', $ma_don_hang);
		return $result->get();
	}

	public function getStatusOrder($ma_don_hang) {
		$result = DB::table('ChiTietTrangThaiDonHang as ctttdh')->select('ttdh.ma_trang_thai', 'ten_trang_thai', 'thoi_gian')
			->leftjoin('TrangThaiDonHang as ttdh', 'ttdh.ma_trang_thai', '=', 'ctttdh.trang_thai')
			->where('ctttdh.ma_don_hang', '=', $ma_don_hang);
		return $result->get();
	}

	public function getUser($id_KH) {
		$result = DB::table('users')->select('ten', 'id as user_id', 'email', 'gioi_tinh' ,'sdt', 'diem_tich' , 'ngay_sinh' , 'dia_chi' , 'fb_id' , 'avatar' , 'password' );
		if ($id_KH != null && $id_KH != '') {
			$result->where('id', '=', $id_KH);
		}
		return $result->get();
	}

	public function getUser2($id_KH) {
		$result = DB::table('users')->select('ten', 'sdt', 'dia_chi');
		if ($id_KH != null && $id_KH != '') {
			$result->where('id', '=', $id_KH);
		}
		return $result->get();
	}

	public function updateUserFB($id_fb, $email , $type) {
		if ($type == 4) {
			$result = DB::table('users')->where('email', '=', $email)
			->update([
				'fb_id' => $id_fb,
			]);
		}
		else
		{
			$result = DB::table('users')->where('fb_id', '=', $id_fb)
			->update([
				'email' => $email,
			]);
		}
		return $result;
	}

	public function getInfo($id_fb) {
		$result = DB::table('users')->select('ten', 'id as user_id', 'email', 'gioi_tinh' ,'sdt', 'diem_tich' , 'ngay_sinh' , 'dia_chi' , 'fb_id' , 'avatar' , 'password')->where('fb_id', '=', $id_fb)->get();
		return $result;
	}

	public function loginfb($id_fb) {
			$result = DB::table('users as us')->select('us.ten', 'us.id as user_id', 'email', 'gioi_tinh' ,'sdt', 'diem_tich' , 'ngay_sinh' , 'dia_chi' , 'fb_id' , 'avatar' , 'password')
	        ->where([
	        		'fb_id' => $id_fb, 
	        		'us.da_xoa' => 0])->get();
			return $result;
	}

	public function create($id_fb, $email , $name , $avatar) {
		if ($email != null && $email != '') {
			$result = DB::table('users')->insert([
						   'email' => $email,
				           'fb_id' => $id_fb,
				           'ten' => $name,
				           'avatar' => $avatar,
				           'da_xoa' => 0,
       			 ]);
		}else {
			$result = DB::table('users')->insert([
				           'fb_id' => $id_fb,
				           'ten' => $name,
				           'avatar' => $avatar,
				           'da_xoa' => 0,
       			 ]);
		}
        return $result;	
	}

	public function news($page) {
		if ($page == null) {
            $result = DB::table('TinTuc')->select('ma_tin_tuc', 'ten_tin_tuc', 'noi_dung', 'ngay_dang', 'hinh_tin_tuc', 'ngay_tao')
	        ->where([
	            'da_xoa' => 0,
	        ]);
        	return $result->limit(4)->orderBy('ngay_dang', 'desc')->get();
        }else {
            $result = DB::table('TinTuc')->select('ma_tin_tuc', 'ten_tin_tuc', 'noi_dung', 'ngay_dang', 'hinh_tin_tuc', 'ngay_tao')
	        ->where([
	            'da_xoa' => 0,
	        ]);
        	return $result->limit(4)->orderBy('ngay_dang', 'desc')->paginate(4);
        }
	}

	public function idMax() {
		$result = DB::table('users')->max('id');
		return $result;
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

	public function insertCart($idCustomer, $ma_sp, $so_luong, $size, $note) {
		$result = DB::table('GioHang')->insert([
           'ma_khach_hang' => $idCustomer,
           'ma_san_pham' => $ma_sp,
           'kich_co' => $size,
           'so_luong' => $so_luong,
           'ghi_chu' => $note
        ]);
        return $result;	
	}

	public function deleteCart($id_GH) {
		$result = DB::table('GioHang')->where('ma_gio_hang' ,'=', $id_GH)->delete();
        return $result;	
	}

	public function deleteCartCustomer($id_KH) {
		$result = DB::table('GioHang')->where('ma_khach_hang' , '=', $id_KH)->delete();
        return $result;	
	}

	public function getQuantity($id_GH) {
		$result = DB::table('GioHang')->select('so_luong')->where('ma_gio_hang' , '=', $id_GH)->get();
        return $result;	
	}
	
	public function updateQuantity($id_GH, $sl, $type) {
		if ($type == 1) {
			$sl = $sl+1;
			$result = DB::table('GioHang')->where('ma_gio_hang' , '=', $id_GH)->update(['so_luong' => $sl]);
        	return $result;	
		}
		if ($type == 2) {
			$sl = $sl-1;
			$result = DB::table('GioHang')->where('ma_gio_hang' , '=', $id_GH)->update(['so_luong' => $sl]);
        	return $result;		
		}
	}		
	public function updateCart($ma_gio_hang, $ma_topping, $ten_topping, $so_luong_topping) {
		$result1 = DB::table('ChiTietDonHang')->where('ma_gio_hang', '=', $ma_gio_hang)->delete();
		$result = DB::table('ChiTietDonHang')->insert([
           'ma_san_pham' => $ma_topping,
           'ma_gio_hang' => $ChiTietDonHang,
           'so_luong' => $so_luong_topping,
        ]);
        return $result;	
	}
	
	public function getCartOfCustomer($id_KH) {
		$result = DB::table('GioHang')->select('ma_gio_hang', 'ten' ,'ma_san_pham', 'gia_san_pham', 'gia_vua', 'gia_lon', 'loai_chinh', 'kich_co', 'hinh_san_pham' ,'so_luong', 'ghi_chu')->join('SanPham', 'ma_so', '=', 'ma_san_pham')->join('LoaiSanPham', 'ma_loai_sp', '=', 'loai_sp')->where(['ma_khach_hang' => $id_KH])->get();
        return $result;
	}

	public function getInfoCustomer($id_KH) {
		$result = DB::table('users')->select('id', 'ten', 'sdt', 'dia_chi')->where('id' , '=', $id_KH)->get();
        return $result;
	}

	public function getEvaluate($ma_san_pham) {
		$result = DB::table('DanhGia')->select('ma_danh_gia')->where(['da_xoa' => 0, 'ma_sp' => $ma_san_pham])->count();
        return $result;
	}

	public function getEvaluate5($ma_san_pham) {
		$result = DB::table('DanhGia')->select('ma_danh_gia')->where(['da_xoa' => 0, 'so_diem' => 5, 'ma_sp' => $ma_san_pham])->count();
        return $result;
	}

	public function getEvaluate4($ma_san_pham) {
		$result = DB::table('DanhGia')->select('ma_danh_gia')->where(['da_xoa' => 0, 'so_diem' => 4, 'ma_sp' => $ma_san_pham])->count();
        return $result;
	}

	public function getEvaluate3($ma_san_pham) {
		$result = DB::table('DanhGia')->select('ma_danh_gia')->where(['da_xoa' => 0, 'so_diem' => 3, 'ma_sp' => $ma_san_pham])->count();
        return $result;
	}

	public function getEvaluate2($ma_san_pham) {
		$result = DB::table('DanhGia')->select('ma_danh_gia')->where(['da_xoa' => 0, 'so_diem' => 2, 'ma_sp' => $ma_san_pham])->count();
        return $result;
	}

	public function getEvaluate1($ma_san_pham) {
		$result = DB::table('DanhGia')->select('ma_danh_gia')->where(['da_xoa' => 0, 'so_diem' => 1, 'ma_sp' => $ma_san_pham])->count();
        return $result;
	}

	public function getChildEvaluate($ma_danh_gia, $page) {
		$result = DB::table('DanhGiaCon')->select('ma_danh_gia_con', 'ma_danh_gia', 'ma_tk' ,  'ten', 'noi_dung', 'duyet' , 'thoi_gian')
		->leftjoin('users', 'ma_tk', '=', 'id')
		->where(['ma_danh_gia' => $ma_danh_gia , 'DanhGiaCon.da_xoa' => 0 , 'duyet' => 1])
		->paginate(10);
        return $result;
	}

	public function getEvaluateOfProduct($id_SP) {
		$result = DB::table('DanhGia')->select('ma_danh_gia', 'ma_tk','so_diem', 'tieu_de', 'noi_dung', 'thoi_gian', 'hinh_anh')->where(['da_xoa' => 0, 'ma_sp' => $id_SP])->get();
        return $result;
	}

	public function getPlace() {
		$result = DB::table('KhuVuc')->select('ma_khu_vuc', 'ten_khu_vuc')->where(['da_xoa' => 0])->get();
        return $result;
	}

	public function getBranch($id_place) {
		$result = DB::table('ChiNhanh')->select('ma_chi_nhanh', 'ten', 'dia_chi', 'latitude', 'longitude', 'ngay_khai_truong', 'gio_mo_cua', 'gio_dong_cua' , 'so_dien_thoai')->where(['da_xoa' => 0, 'ma_khu_vuc' => $id_place])->get();
        return $result;
	}

	public function addEvaluate($id_tk, $id_sp, $so_diem, $tieu_de, $noi_dung, $thoi_gian , $duyet) {
		$result = DB::table('DanhGia')->insert([
           'ma_tk' => $id_tk,
           'ma_sp' => $id_sp,
           'so_diem' => $so_diem,
           'tieu_de' => $tieu_de,
           'noi_dung' => $noi_dung,
           'thoi_gian' => $thoi_gian,
           'duyet' => $duyet,
           'da_xoa' => 0,
        ]);
        return $result;	
	}

	public function addChildEvaluate($id_Evaluate, $id_tk, $noi_dung, $thoi_gian , $duyet) {
		$result = DB::table('DanhGiaCon')->insert([
           'ma_danh_gia' => $id_Evaluate,
           'ma_tk' => $id_tk,
           'noi_dung' => $noi_dung,
           'thoi_gian' => $thoi_gian,
           'duyet' => $duyet,
           'da_xoa' => 0,
        ]);
        return $result;	
	}

	public function addThanks($id_Evaluate, $id_KH) {
		$result = DB::table('CamOnDanhGia')->insert([
           'ma_danh_gia' => $id_Evaluate,
           'ma_kh' => $id_KH,
        ]);
        return $result;	
	}

	public function insertTopping($ma_gio_hang, $topping) {
		for ($i=0; $i < count($topping); $i++) { 
			$result = DB::table('ChiTietGiohang')->insert([
				'ma_gio_hang' => $ma_gio_hang,
           		'ma_san_pham' => $topping[$i]['ma_san_pham'],
           		'so_luong' => $topping[$i]['so_luong']
        	]);	
		}
        return $result;	
	}

	public function getToppingCart($ma_gio_hang) {
		$result = DB::table('ChiTietGiohang')->select('ma_gio_hang', 'ma_san_pham', 'ten', 'gia_san_pham' ,'so_luong')->join('SanPham', 'ma_so', '=', 'ma_san_pham')->where(['ma_gio_hang' => $ma_gio_hang])->get();

        return $result;
	}

	public function getTopping($ma_chi_tiet) {
		$result = DB::table('ChiTietThucUong')->select('ma_san_pham', 'ten' ,'don_gia', 'so_luong')->join('SanPham', 'ma_so', '=', 'ma_san_pham')->where(['ma_chi_tiet' => $ma_chi_tiet])->get();
        return $result;
	}

	public function selectMaxId() {
		$result = DB::table('GioHang')->max('ma_gio_hang');
		return $result;
	}

	public function getCart() {
		$result = DB::table('GioHang')->select('ma_gio_hang', 'ma_khach_hang', 'ma_san_pham', 'kich_co', 'ghi_chu')->get();
        return $result;
	}

	public function getDetailCart($ma_gio_hang) {
		$result = DB::table('ChiTietGiohang')->select('ma_san_pham', 'so_luong')->where('ma_gio_hang', '=', $ma_gio_hang)->get();
        return $result;
	}

	public function getOrderDetail($ma_don_hang) {
		$result = DB::table('ChiTietDonHang')->select('ma_chi_tiet')->where('ma_don_hang', '=', $ma_don_hang)->orderBy('ngay_lap', 'desc')->get();
        return $result;
	}

	public function getInfoProduct($id_SP) {
		 $result = DB::table('SanPham as sp')->select('sp.ma_so', 'lsp.ma_loai_sp', 'sp.ma_chu', 'sp.ten','sp.gia_san_pham' , 'sp.gia_vua', 'sp.gia_lon', 'sp.ngay_ra_mat', 'lsp.ten_loai_sp', 'sp.daxoa', 'sp.hinh_san_pham as hinh_chinh', 'sp.mo_ta',  'lsp.loai_chinh')
        ->leftjoin('LoaiSanPham as lsp', 'lsp.ma_loai_sp', '=', 'sp.loai_sp')
        ->where([
            'lsp.da_xoa' => 0,
            'sp.ma_so' => $id_SP
        ])->get();
        return $result;
	}

	public function getImg($ma_sp) {
		 $result = DB::table('HinhAnh')->select('url')->where(['object_id' => $ma_sp, 'da_xoa' => 0, 'kieu' => 1])->get();
		return $result;
	}

	public function getDanhGia($ma_so) {
		 $result = DB::table('DanhGia')->select('ma_danh_gia','so_diem', 'tieu_de', 'noi_dung', 'thoi_gian', 'parent_id')->where(['ma_sp' => $ma_so])->get();
		return $result;
	}

	public function getCamOn($ma_DG) {
		$result = DB::table('CamOnDanhGia')->select('ma_khach_hang')->where(['ma_danh_gia' => $ma_DG])->count();
		return $result;
	}

	public function getSoLuong($ma_san_pham) {
		$result = DB::table('ChiTietGiohang')->select('so_luong')->where(['ma_san_pham' => $ma_san_pham])->get();
		return $result;
	}

	public function updateTopping($ma_san_pham, $sl) {
		$result = DB::table('ChiTietGiohang')->where('ma_san_pham', '=', $ma_san_pham)
		->update([
			'so_luong' => $sl,
		]);
		return $result;
	}

	public function updateCartOfCustomer($idCart, $so_luong, $size, $note) {
		$result = DB::table('GioHang')->where(['ma_gio_hang' => $idCart])
		->update([
			'so_luong' => $so_luong,
			'kich_co' => $size,
			'ghi_chu' => $note,
		]);
		return $result;
	}

	public function deleteToppingOfCart($idCart) {
		$result = DB::table('ChiTietGiohang')->where('ma_gio_hang', '=', $idCart)->delete();
		return $result;
	}

	public function getIdImg() {
		$result = DB::table('HinhAnh')->max('ma_hinh');
		return $result;	
	}

	public function getSL($ma_gio_hang) {
		$result = DB::table('GioHang')->select('so_luong')->where(['ma_gio_hang' => $ma_gio_hang])->get();
		return $result;
	}

	public function updateQuantityCart($ma_gio_hang, $sl) {
		$result = DB::table('GioHang')->where(['ma_gio_hang' => $ma_gio_hang])
		->update(['so_luong' => $sl]);
		return $result;
	}

	public function sumOrderByIdProductAndCustomer($id_KH , $ma_san_pham){
		$result = DB::table('DonHang')->select()
		->leftjoin('ChiTietDonHang', 'ChiTietDonHang.ma_don_hang', '=', 'DonHang.ma_don_hang')
		->where(['ma_san_pham' => $ma_san_pham , 
				 'DonHang.da_xoa' => 0 ,
				 'ma_khach_hang' => $id_KH])->get();
		return $result;
	}

	public function getlistEvaluate($ma_san_pham, $page , $so_diem , $thoi_gian) {
		if ($page != null && $page != '') {
			$result = DB::table('DanhGia')->select('ma_danh_gia', 'ma_tk' ,'ten', 'ma_sp', 'so_diem', 'tieu_de', 'noi_dung', 'thoi_gian', 'duyet')
			->leftjoin('users', 'ma_tk', '=', 'id')
			->where(['ma_sp' => $ma_san_pham , 
					 'DanhGia.da_xoa' => 0 ,
					 'duyet' => 1]);

			if($so_diem != null)
				$result = $result->where(['so_diem' => $so_diem]);

			if($thoi_gian != null)

				$result = $result->orderBy('thoi_gian', 'desc')->paginate(5);
			else
				$result = $result->orderBy('thoi_gian', 'desc')->paginate(5);
		}else {
			$result = DB::table('DanhGia')->select('ma_danh_gia', 'ma_tk' , 'ten', 'ma_sp', 'so_diem', 'tieu_de', 'noi_dung', 'thoi_gian', 'duyet')
			->leftjoin('users', 'ma_tk', '=', 'id')
			->where(['ma_sp' => $ma_san_pham , 'DanhGia.da_xoa' => 0 , 'duyet' => 1])
			->orderBy('thoi_gian', 'desc')->limit(2)->get();
		}
		return $result;	
	}

	public function getThanhks($ma_danh_gia) {
		$result = DB::table('CamOnDanhGia')->select('ma_danh_gia')
		->where(['ma_danh_gia' => $ma_danh_gia])->count();
		return $result;
	}

	public function getImgEV($ma_danh_gia) {
		$result = DB::table('HinhAnh')->select('url')->where(['object_id' => $ma_danh_gia, 'da_xoa' => 0, 'kieu' => 3])->get();
		return $result;
	}

	public function listChild($ma_danh_gia) {
		$result = DB::table('DanhGiaCon')->select('ma_danh_gia_con', 'ma_danh_gia', 'ma_tk', 'ten', 'noi_dung', 'duyet' , 'thoi_gian')
		->leftjoin('users', 'ma_tk', '=', 'id')
		->where(['ma_danh_gia' => $ma_danh_gia , 'DanhGiaCon.da_xoa' => 0  , 'duyet' => 1])
		->orderBy('thoi_gian', 'desc')->limit(2)->get();
		return $result;
	}

	public function getThankYouOfCustomer($ma_kh) {
		$result = DB::table('CamOnDanhGia')->select('ma_danh_gia')->where(['ma_kh' => $ma_kh])->get();
		return $result;
	}

	public function getQuantityAndPrice($ma_kh) {
		$result = DB::table('GioHang')->where(['ma_khach_hang' => $ma_kh])->sum('so_luong');
		return $result;
	}

	public function getSp($ma_kh) {
		$result = DB::table('GioHang')->select('ma_gio_hang', 'ma_san_pham', 'so_luong', 'kich_co')->where(['ma_khach_hang' => $ma_kh])->get();
		return $result;
	}

	public function getSLTP($ma_gio_hang) {
		$result = DB::table('ChiTietGiohang')->select('ma_gio_hang', 'ma_san_pham', 'so_luong', 'gia_san_pham')->leftjoin('SanPham', 'ma_so', '=', 'ma_san_pham')->where(['ma_gio_hang' => $ma_gio_hang])->get();
		return $result;
	}	

	public function getSLSP($ma_gio_hang, $kich_co) {
		if ($kich_co == 'S') {
			$result = DB::table('GioHang')->select('so_luong', 'kich_co', 'gia_san_pham')->leftjoin('SanPham', 'ma_so', '=', 'ma_san_pham')->where(['ma_gio_hang' => $ma_gio_hang])->get();
			return $result;
		}
		if ($kich_co == 'M') {
			$result = DB::table('GioHang')->select('so_luong', 'kich_co', 'gia_vua')->leftjoin('SanPham', 'ma_so', '=', 'ma_san_pham')->where(['ma_gio_hang' => $ma_gio_hang])->get();
			return $result;
		}
		if ($kich_co == 'L') {
			$result = DB::table('GioHang')->select('so_luong', 'kich_co', 'gia_lon')->leftjoin('SanPham', 'ma_so', '=', 'ma_san_pham')->where(['ma_gio_hang' => $ma_gio_hang])->get();
			return $result;
		}
	}

	public function insertOrder($thong_tin_ship, $ma_kh, $khuyen_mai, $phi_ship, $tong_tien, $ghi_chu, $ngay_lap, $ma_chu, $tong_tien_khuyen_mai, $phuong_thuc) {
		$result = DB::table('DonHang')->insert([
				'thong_tin_giao_hang' => $thong_tin_ship,
           		'ma_khach_hang' => $ma_kh,
           		'ngay_lap' => $ngay_lap,
           		'khuyen_mai' => $khuyen_mai,
           		'phi_ship' => $phi_ship,
           		'tong_tien' => $tong_tien,
           		'ghi_chu' => $ghi_chu,
           		'da_xoa' => 0,
           		'ma_chu' => $ma_chu,
           		'phuong_thuc_thanh_toan' => $phuong_thuc,
           		'tong_tien_khuyen_mai' => $tong_tien_khuyen_mai,
        	]);
		return $result;
	}

	public function getMaxIdOrder() {
		$result = DB::table('DonHang')->max('ma_don_hang');
		return $result;
	}

	public function getMaxIdOrderDetail() {
		$result = DB::table('ChiTietDonHang')->max('ma_chi_tiet');
		return $result;
	}

	public function insertStatusOrder($getMaxIdOrder) {
		$now = Carbon::now();
		$result = DB::table('ChiTietTrangThaiDonHang')->insert([
				'ma_don_hang' => $getMaxIdOrder,
				'thoi_gian' => $now,
				'trang_thai' => 1,
				'da_xoa' => 0,
           		
        	]);
		return $result;;
	}

	public function insertOrderDetail($getMaxIdOrder, $ma_sp, $so_luong, $don_gia, $Gia_KM, $thanh_tien, $ghi_chu_sp, $kich_co) {
		$result = DB::table('ChiTietDonHang')->insert([
				'ma_don_hang' => $getMaxIdOrder,
           		'ma_san_pham' => $ma_sp,
           		'so_luong' => $so_luong,
           		'don_gia' => $don_gia,
           		'kich_co' => $kich_co,
           		'gia_khuyen_mai' => $Gia_KM,
           		'thanh_tien' => $thanh_tien,
           		'ghi_chu' => $ghi_chu_sp,
        	]);
		return $result;
	}

	public function insertToppingOrder($getMaxIdOrderDetail, $ma_topping, $so_luong, $don_gia) {
		$result = DB::table('ChiTietThucUong')->insert([
				'ma_chi_tiet' => $getMaxIdOrderDetail,
           		'ma_san_pham' => $ma_topping,
           		'don_gia' => $don_gia,
           		'so_luong' => $so_luong,
        	]);
		return $result;
	}

	public function getIdMaxEV() {
		$result = DB::table('DanhGia')->max('ma_danh_gia');
		return $result;
	}

	public function insertImg($getIdMaxEV, $img) {
		
		$result = DB::table('HinhAnh')->insert([
           'object_id' => $getIdMaxEV,
           'kieu' => 3,
           'url' => $img,
           'da_xoa' => 0,
        ]) ;
		
		
        return $result;
	}

	public function getSlideShow($slide) {
		$now = Carbon::now();
		$from_date = $now->subDay(5)->toDateString();
		$to_date = $now->addDay(5)->toDateString();
		$result = DB::table('KhuyenMai')->select()
		->where([
			'da_xoa' => 0,
		]);
		if ($slide != null && $slide != '') {
		 	$result = $result->where(['hien_slider' => 1]);
		}
		$result = $result->where('ngay_ket_thuc', '>=', $from_date);
        $result = $result->where('ngay_bat_dau', '<=', $to_date);
		return $result->orderBy('ngay_bat_dau', 'desc')->get();
	}

	public function totalDiscountOfOrder($id) {
        $result = DB::table('DonHang')->select()
                ->where('khuyen_mai', '=', $id)->get();
        return $result;
    }

	public function getAllLogPointUser($id){
		$result = DB::table('LichSuDiem as ls')->select('ls.ma_don_hang' , 'dh.ma_chu' , 'ls.so_diem' , 'ls.hinh_thuc' , 'ls.thoi_gian')
		->leftjoin('DonHang as dh', 'dh.ma_don_hang', '=', 'ls.ma_don_hang')
		->where([
			'ls.ma_tai_khoan' => $id,
			'ls.da_xoa' => 0,
		])->orderBy('ls.thoi_gian', 'desc')->get();
		return $result;
	}

	public function getPoint($ma_kh){
		$result = DB::table('users')->select('diem_tich')->where([
			'da_xoa' => 0,
			'id' => $ma_kh
		]);
		return $result->get();
	}

	public function addpoint($ma_kh, $totalPoint) {
		$result = DB::table('users')->where('id', '=', $ma_kh)
		->update([
			'diem_tich' => $totalPoint
		]);
		return $result;
	}

	public function addLog($ma_kh, $getMaxIdOrder, $hinh_thuc, $ngay_lap, $totalPoint) {
		$result = DB::table('LichSuDiem')
		->insert([
			'ma_tai_khoan' => $ma_kh,
			'ma_don_hang' => $getMaxIdOrder,
			'so_diem' => $totalPoint,
			'hinh_thuc' => $hinh_thuc,
			'da_xoa' => 0,
			'thoi_gian' => $ngay_lap
		]);
		return $result;
	}

	public function getQuantityDiscount() {
        $result = DB::table('DonHang')->select('khuyen_mai' , DB::raw('count(khuyen_mai) as total'))
                ->where('da_xoa', '=', 0)
                ->groupBy('khuyen_mai')
                 ->get();
        return $result;
    }

    public function submitChange($gmail, $newPass) {
    	$result = DB::table('users')->where('email', '=', $gmail)
		->update([
			'password' => $newPass,
		]);
		return $result;
    }	
}