'use strict';

import * as Pagination from 'laravel-vue-pagination';

const app = new Vue({
    el: '#permission',
    components: { Pagination},
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            results: {},
            name_user: '',
            phone_user: '',
            email_user: '',
            password_user: '',
            user_id_update: '',
            name_user_update: '',
            phone_user_update: '',
            email_user_update: '',
            password_user_update: '',
            list_access_id :''
        };
    },
    created() { 
        this.listAuthorizationUserWeb();
    },
    mounted() {
       
    },
    methods: {
        listAuthorizationUserWeb(page) {
            var data = {
                _token: this.csrf,
            };
            if (page) {
                data.page = page;
            }
            common.loading.show('body');
            $.get('/permission/listPermission', data)
                .done(response => {
                    this.results = response.list;
                }).fail(error => {
                    bootbox.alert('Error!!!');
                }).always(() => {
                    common.loading.hide('body');
                });
        },
        formatDate(date) {
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var ampm = hours >= 12 ? 'pm' : 'am';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0'+minutes : minutes;
            var strTime = hours + ':' + minutes + ' ' + ampm;
            return  date.getFullYear() + "-" + (date.getMonth()+1) + "-" +  date.getDate() + " " + strTime;
        },
        saveUserWeb() {
            if(this.name_user == null || this.name_user == '') {
                $('#name_user').focus();
                return false;
            } else if (this.email_user == null || this.email_user == '') {
                $('#email_user').focus();
                return false;
            } else if (this.password_user == null || this.password_user == '') {
                $('#password_user').focus();
                return false;
            }
            for (var i = 0; i < this.results.data.length; i++) {
                if (this.email_user == this.results.data[0].email) {
                    bootbox.alert('Email đã tồn tại');
                    return false;
                }
                if (this.phone_user == this.results.data[0].sdt) {
                    bootbox.alert('Số điện thoại đã tồn tại');
                    return false;
                }
            }
            var arrayCheck = [];
            $('input[name="chk_permission_group[]"]:checked').each(function() {
                arrayCheck.push(this.value);
            });
            if (arrayCheck === undefined || arrayCheck.length == 0) {
                bootbox.alert("Sửa User cần quyền truy cập.");
                return false;
            }
            var data = {
                _token: this.csrf,
                name: this.name_user,
                phone: this.phone_user,
                email: this.email_user,
                password: this.password_user,
                permission_group: arrayCheck,
            };
            common.loading.show('body');
            $.post('/permission/create', data)
                .done(response => {
                    if (response.error === 0) {
                        common.loading.hide('body');
                        bootbox.alert("Lưu thành công !!", function() {
                            window.location = '/permission/manage';
                        })
                    } else {
                        // bootbox.alert('Error!!!');
                        bootbox.alert(response.msg || 'Error!!!');
                    }
                }).fail(error => {
                    bootbox.alert('Error!!!');
                }).always(() => {
                    common.loading.hide('body');
                });
        },
        deleteUserWeb(id, status) {
            var data = {
                _token: this.csrf,
                user_id: id,
                status:status
            };
            if (status == 1) {
                bootbox.confirm({
                title: 'Thông báo',
                message: 'Bạn có muốn phục hồi tài khoản này không?',
                buttons: {
                    confirm: {
                        label: 'Xác nhận',
                        className: 'btn-primary',
                    },
                    cancel: {
                        label: 'Bỏ qua',
                        className: 'btn-default'
                    }
                },
                callback: (result) => {
                    if (result) {
                        common.loading.show('body');
                        $.post('/permission/delete', data)
                        .done(response => {
                            if (response.error === 0) {
                                common.loading.hide('body');
                                bootbox.alert("Phục hồi thành công !!", function() {
                                    window.location = '/permission/manage';
                                })
                            } else {
                                bootbox.alert('Error!!!');
                            }
                        }).fail(error => {
                            bootbox.alert('Error!!!');
                        }).always(() => {
                            common.loading.hide('body');
                        });
                    }
                }
            });
            }else {
                bootbox.confirm({
                title: 'Thông báo',
                message: 'Bạn có chắc chắn muốn xoá tài khoản này không?',
                buttons: {
                    confirm: {
                        label: 'Xác nhận',
                        className: 'btn-primary',
                    },
                    cancel: {
                        label: 'Bỏ qua',
                        className: 'btn-default'
                    }
                },
                callback: (result) => {
                    if (result) {
                        common.loading.show('body');
                        $.post('/permission/delete', data)
                        .done(response => {
                            if (response.error === 0) {
                                common.loading.hide('body');
                                bootbox.alert("Xoá thành công !!", function() {
                                    window.location = '/permission/manage';
                                })
                            } else {
                                bootbox.alert('Error!!!');
                            }
                        }).fail(error => {
                            bootbox.alert('Error!!!');
                        }).always(() => {
                            common.loading.hide('body');
                        });
                    }
                }
            });
            }
        },

        getInfo(tai_khoan, ten, sdt, email, ten_vai_tro) {
            this.user_id_update = tai_khoan;
            this.name_user_update = ten;
            this.email_user_update = email;
            this.phone_user_update = sdt;
            $('#ModalUpdateUserAuthorization').modal('show');
        },

        updateUserWeb() {
            if(this.name_user_update == null || this.name_user_update == '') {
                $('#name_user').focus();
                return false;
            } else if (this.email_user_update == null || this.email_user_update == '') {
                $('#email_user').focus();
                return false;
            }
            var arrayCheck = [];
            $('input[name="chk_permission_group_update[]"]:checked').each(function() {
                arrayCheck.push(this.value);
            });
            if (arrayCheck === undefined || arrayCheck.length == 0) {
                bootbox.alert("Sửa User cần quyền truy cập.");
                return false;
            }
            var data = {
                _token: this.csrf,
                user_id: this.user_id_update,
                name: this.name_user_update,
                phone: this.phone_user_update,
                email: this.email_user_update,
                permission_group: arrayCheck,
            };
            common.loading.show('body');
            $.post('/permission/update', data)
                .done(response => {

                    if (response.error === 2) {
                        common.loading.hide('body');
                        bootbox.alert("sửa thành công !!", function() {
                            window.location = '/logout';
                        })
                    } else {
                        if (response.error === 0) {
                            common.loading.hide('body');
                            bootbox.alert("Sửa thành công !!", function() {
                                window.location = '/permission/manage';
                            })
                        }else {
                            if (response.error === 2) {
                            common.loading.hide('body');
                            bootbox.alert("Sửa thành công !!", function() {
                                window.location = '/logout';
                            })
                        }else {
                            bootbox.alert('Error!!!');
                        }
                        }
                    }
                }).fail(error => {
                    bootbox.alert('Error!!!');
                }).always(() => {
                    common.loading.hide('body');
                });
        }
    }
});
