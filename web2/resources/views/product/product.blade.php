
@extends('layout.base')
@section('stylesheet')
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.6/dist/jquery.fancybox.min.css"/>
@endsection
@section('body-content')
	<div id="manage-product">
        <div class="row mt-5 pt-3">
            <div style="padding-left: 2rem;margin-top:3%;">
                <h4 class="tag-page-custom" style="color: blue">
                    Quản lý sản phẩm
                </h4>
            </div>
        </div>
        <div class="row">
        <div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="update" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="max-width: 800px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Chỉnh sửa</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>       
                        <div class="modal-body">
                            <form id="form_edit_info" method="POST" action="edit" enctype="multipart/form-data">
                <table border="0px" class=" table-striped w-100" style="margin-left: 0%;font-weight: bold;">
                <tr style="background: #f2f2f2">
                    <td>
                        <label for="other_note1"> Hình ảnh </label>
                        <img id="avatarcollector_edit" style="width: 150px; height: 150px;" class="d-block" :src="imageUrl" />
                    </td>
                    <td colspan="2">
                        <div class="form-group">
                            <input type="file"@change="onSelectImageHandler" class="form-control" id=""  style="width: 200px;">
                            <input type="input" hidden="true" id="id_product"  style="width: 200px;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 300px;">
                        <div class="form-group">
                            <label for="other_note1"> Tên sản phẩm </label>
                            <input type="text" class="form-control" id="ten" style="width: 200px;">
                        </div>
                    </td>
                    <td style="width: 300px;">
                        <div class="form-group">
                            <label for="other_note1"> Mã sản phẩm </label>
                            <input type="text" class="form-control" id="ma" style="width: 200px;">
                        </div>
                    </td>
                     <td>
                        <div class="form-group">
                            <label for="other_note1"> Giá gốc </label>
                            <input type="text" class="form-control" id="gia_goc" style="width: 200px;">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="other_note1"> Giá size vừa </label>
                            <input type="text" class="form-control" id="gia_size_vua" style="width: 200px;">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="other_note1"> Giá size lớn </label>
                            <input type="text" class="form-control" id="gia_size_lon" style="width: 200px;">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="other_note1"> Loại sản phẩm </label>
                            <select id="loaisp" class="form-control" style="width: 200px;">
                                <option value="">Chọn loại sản phẩm</option>
                                    @if (count($list) > 0)
                                        @foreach ($list as $item)
                                            <option value="{{ $item->ma_loai_sp }}" > {{$item->ten_loai_sp}}</option>
                                        @endforeach
                                     @endif
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="other_note1"> Ngày ra mắt </label>
                            <input type="date" class="form-control" id="ngay_ra_mat"  style="width: 200px;">
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <label for="other_note1">Mô tả </label>
                            <textarea class="form-control" id="mo_ta"  style="width: 200px;"></textarea>
                        </div>
                    </td>
                </tr>
            </table>
                </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="edit()" class="button-app">Sửa</button>
                            <button type="button" class="button-app ml-5 float-right" data-dismiss="modal">Đóng</button>
                        </div>
                        </div>
                    </div>
        </div>
        <div class="modal fade" id="ModalShowDescription" tabindex="-1" role="dialog" aria-labelledby="ModalShowDescription" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="width: 470px">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>                     
                            <div class="modal-body">
                                <table class="table table-bordered table-striped w-100" style="min-height: 150px; line-height: 1.4;">
                                    <thead style="">
                                        <tr class="text-center blue-opacity">
                                            <th class="custom-view"> Mô tả </th>
                                        </tr>
                                    </thead>
                                    <tbody v-cloak>
                                            <td class="custom-view text-left" id="description_show"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"> Huỷ bỏ </button>
                            </div>
                        </div>
                    </div>
                </div>
                <form method="POST" action="update-img" enctype="multipart/form-data">
                    @csrf
                <div class="modal fade" id="showMore" tabindex="-1" role="dialog" aria-labelledby="showMore" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="width: 470px">
                        <div class="modal-content">
                            <div class="modal-header">
                                Hình phụ
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>                     
                            <div class="modal-body">
                                <div id="showMoreImg" style="width: 100%">
                                    <div v-for="(item,index) in listImg" style="margin-left: 2%; float: left;">
                                        <input type="hidden" name="type" value="1">
                                       <img v-if="item.url != null && item.url != ''" class="img-responsive" width="100px" height="100px" :src="item.pathToResource+'/'+item.url">
                                    </div>
                                </div>
                                    <div>
                                        <label for="model" class="col-md-4 p-0 justify-content-start align-items-start font-weight-bold" style="margin-left:2% ">Hình Ảnh</label><br>
                                            <input id="_imagesInput" name="files[]" type="file" multiple style="width: 75px;margin-left:2% " title="Chọn ảnh">
                                            <input id="id_update" name="id_update" type="hidden">
                                            <div id="_displayImages">
                                                <div>
                                                    <ul id="frames" class="frames">
                                                        
                                                    </ul>
                                                </div>
                                            </div>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" class="button-app" value="Lưu">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"> Huỷ bỏ </button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
        <div id="body" class="set-row background-contact w-100">
            <input id="" type="text" class="input-app mr-4"  placeholder="Tên"  style="width: 200px;margin-bottom: 10px" v-model="name">
            <input id="" type="text" class="input-app mr-4"  placeholder="Mã sản phẩm"  style="width: 200px;margin-bottom: 10px" v-model="masp">
            <input id="" type="text" class="input-app mr-4"  placeholder="Mô tả"  style="width: 200px;margin-bottom: 10px" v-model="mo_ta">
            <button class="button-app ml-5 float-right" @click="search()">Tìm kiếm</button>
           <!--  <a :href="'excel-product?name='+exportproduct.name+'&masp='+exportproduct.masp+'&ma_loai='+exportproduct.ma_loai+'&mo_ta='+exportproduct.mo_ta" class="btn btn-primary button-app mb-4 float-right" style="color: white">Xuất File Excel</a> -->
            <table id="tb1" class="table table-bordered table-striped w-100" style="min-height: 150px; line-height: 1.4;font-weight: bold;">
                <thead>
                <tr class="text-center blue-opacity">
                    <th  class="custom-view">STT</th>
                    <th  class="custom-view">Tên sản phẩm</th>
                    <th  class="custom-view">Mã sản phẩm</th>
                    <th  class="custom-view">Loại sản phẩm</th>
                    <th  class="custom-view">Giá sản phẩm</th>
                    <th  class="custom-view">Số lượng Order</th>
                    <th  class="custom-view">Mô tả</th>
                    <th  class="custom-view">Ngày ra mắt</th>
                    <th  class="custom-view">Hình ảnh</th>
                    <th  class="custom-view">Trạng thái</th>
                    <th  class="custom-view">Hành Động</th>
                </tr>
                </thead>
                <tbody v-cloak>
                    <tr class="text-center" v-for="(item,index) in results.data">
                        <td class="custom-view td-grey"><p>@{{index + 1}}<p></td>
                        <td  class="custom-view"><p>@{{item.ten}}<p></td>
                        <td  class="custom-view"><p>@{{item.ma_chu}}<p></td>
                        <td  class="custom-view"><p>@{{item.ten_loai_sp}}<p></td>
                        <td  class="custom-view" width="150px"><div style="text-align: left; width: 100px; height: 100px; margin-left: 15%"><p>S(@{{item.gia_san_pham}}) VNĐ</p><p> M(@{{item.gia_vua}}) VNĐ</p><p>L(@{{item.gia_lon}}) VNĐ</p></td>
                        <td class="custom-view"><p>@{{item.so_lan_dat}}<p></div></td>
                         <td class="custom-view text-left" style="width: 150px;" v-if="item.mo_ta != null">
                            <span v-if="item.mo_ta.length < 30">@{{ item.mo_ta}}</span>
                            <span v-if="item.mo_ta.length > 30">@{{ item.mo_ta | contentSubstr}}<a v-if="item.mo_ta.length > 30" style="cursor: pointer; color: #55bde7;" @click="showDescription(item.mo_ta)" class="see_more_less"><b>...Xem thêm mô tả</b></a></span>  
                        </td>
                        <td class="custom-view"><p>@{{item.ngay_ra_mat1}}<p></td>
                        <td class="custom-view">
                                    <a data-fancybox="gallery" :href="item.pathToResource+'/'+item.hinh_san_pham">
                                        <img class="img-responsive" width="50px" height="50px" :src="item.pathToResource+'/'+item.hinh_san_pham">
                                    </a>
                                    <button style="cursor: pointer;border: 1px solid transparent; background: transparent;font-weight: bold;" @click="showMore(item.ma_so)">+</button>
                        </td>
                        <td  class="custom-view">
                                <span href="#" v-if="item.daxoa == 0" class="btn_edit fa fa-check" style="color: green"></span>
                                <span href="#" v-if="item.daxoa == 1" class="btn_edit fa fa-times" style="color: red"></span>
                            </td>
                        <td class="custom-view">
                            <a href="#" v-if="item.daxoa == 0" class="btn_edit fa fa-edit" @click="seeMoreDetail(item.ma_so, item.ten, item.ma_chu, item.ten_loai_sp, item.gia_san_pham, item.gia_vua, item. gia_lon, item.ngay_ra_mat, item.mo_ta, item.ma_loai_sp, item.hinh_san_pham);"></a>
                            <span v-if="item.daxoa == 0" class="btn_remove fa fa-trash" style="cursor: pointer;" @click="deleted(item.ma_so, item.daxoa)"  data-toggle="tooltip" data-placement="right" title="Xoá"></span>
                            <span v-if="item.daxoa == 1" class="btn_edit fas fa-undo" style="cursor: pointer;" @click="deleted(item.ma_so, item.daxoa)"  data-toggle="tooltip" data-placement="right" title="Phục hồi"></span></td>
                    </tr>
                </tbody>
            </table>
            <div class="col-12" style="margin-left: 80%">
                    <pagination :data="results" @pagination-change-page="search"></pagination> 
            </div>
        </div>
    </div>
    </div>
			
@endsection
@section('scripts')
<script type="text/javascript">
        $(document).ready(function() {
            $('#_uploadImages').click(function() {
                $('#_imagesInput').click();
            });

            $('#_imagesInput').on('change', function() {
                $("#frames").text('');
                $("#showMoreImg").text('');
                handleFileSelect();
            });

            function handleFileSelect() {
                if (window.File && window.FileList && window.FileReader) {
                    var files = event.target.files;
                    if (files.length > 3) {
                        bootbox.alert("Chỉ được chọn 3 hình");
                        files = [];
                        return false;
                    }
                    var output = document.getElementById("frames");
                    var arrFilesCount = [];
                    for (var i = 0; i < files.length; i++) {
                        arrFilesCount.push(i);
                        var file = files[i];
                        var picReader = new FileReader();
                        picReader.addEventListener("load", function (event) {
                            var picFile = event.target;
                                output.innerHTML = output.innerHTML +"<div style=\"float:left;width:100px;margin-left:2%;\" class=\"carousel-item carousel-item-avatar active\">"+"<img width='100px;' height='100px;' style='margin-left:%;margin-top:2%' src='" + picFile.result + "'" + "title=''/>"
                                                +  "</div>";
                                                $(".btn_remove_image").click(function() {
                                $(this).parent(".carousel-item").remove();
                                $("#frames").val('');
                            });         
                        });

                        picReader.readAsDataURL(file);
                    }
                 } else {
                    console.log("Your browser does not support File API");
                 }
        }
        });     
    </script>
           <script type="text/javascript">
				@php
					include public_path('/js/product/product/product.js');
                    include public_path('/js/product/product/jquery.fancybox.min.js');
				@endphp
			</script>
@endsection