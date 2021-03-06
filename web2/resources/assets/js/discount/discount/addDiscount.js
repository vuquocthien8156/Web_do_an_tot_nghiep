'use strict';
import * as Pagination from 'laravel-vue-pagination';
const app = new Vue({

    el: '#manage-add-product',
    components: {Pagination},
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            ten:'',
            ma:'',
            gia_goc:'',
            gia_size_vua:'',
            gia_size_lon:'',
            ngay_ra_mat:'',
            loaisp:'',
            mo_ta:'',
            imageUrl:null,
            selectedFile: [],
            type:1,
        };
    },

	created() {
       
    },
    methods: {
        onSelectImageHandler(e) {
            this.avatar_path = null;
            let files = e.target.files;
            let done = (url) => {
                // this.$refs.fileInputEl.value = '';
                //this.$refs.bannerImgEl.src = url;
                this.imageUrl = url;
            };
            let reader;
            let file;
            let url;

            if (files && files.length > 0) {
                file = files[0];
                this.selectedFile.push(file);

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = e => {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        },
        luu() {
            if (this.selectedFile == '' || this.selectedFile == null) {
                bootbox.alert('vui lòng chọn hình');
                return false;
            }
            if (this.ten == '' || this.ten == null) {
                bootbox.alert('vui lòng nhập tên sản phẩm');
                return false;
            }
            if (this.ma == '' || this.ma == null) {
                bootbox.alert('vui lòng nhập mã chữ sản phẩm');
                return false;
            }
            if (this.gia_goc == '' || this.gia_goc == null) {
                bootbox.alert('vui lòng nhập giá gốc sản phẩm');
                return false;
            }
            if (this.gia_size_vua == '' || this.gia_size_vua == null) {
                bootbox.alert('vui lòng nhập giá size vừa sản phẩm');
                return false;
            }
            if (this.gia_size_lon == '' || this.gia_size_lon == null) {
                bootbox.alert('vui lòng nhập giá size lớn sản phẩm');
                return false;
            }
            if (this.loaisp == '' || this.loaisp == null) {
                bootbox.alert('vui lòng nhập loại sản phẩm');
                return false;
            }
            if (this.ngay_ra_mat == '' || this.ngay_ra_mat == null) {
                bootbox.alert('vui lòng ngày ra mắt');
                return false;
            }
            if (this.mo_ta == '' || this.mo_ta == null) {
                bootbox.alert('vui lòng mô tả');
                return false;
            }
        }
	}
});
