<template>
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4" align="center">Borrowing Data</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><router-link to="/home">Home</router-link></li>
                <li class="breadcrumb-item active">Peminjaman</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Borrowing List

                    <button @click="Add()" data-bs-toggle="modal" data-bs-target="#bookborrow_modal" class="btn btn-primary btn-sm float-end"><i class="fas fa-plus fa-fw"></i> Add Borrowing</button>
                </div>
                <div class="card-body">
                    <table id="siswa_table" class="table table-responsive table-striped table-hover">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAMA SISWA</th>
                                <!-- <th>CLASS</th> -->
                                <th>TANGGAL PEMINJAMAN</th>
                                <th>TANGGAL PENGEMBALIAN</th>
                                <!-- <th>STATUS</th> -->
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <!-- <tr>
                               <th>NO</th>
                                <th>NAMA SISWA</th>
                                <th>CLASS</th>
                                <th>TANGGAL PEMINJAMAN</th>
                                <th>TANGGAL PENGEMBALIAN</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr> -->
                        </tfoot>
                        <tbody>
                            <tr v-for="(ls, index) in list_transaksi" :key="ls">
                                <td>{{ index+1 }}</td>
                                <td>{{ ls.nama_siswa }}</td>
                                <!-- <td><span class="badge bg-dark" >{{ ls.student.class.class_name + ' '+ ls.student.class.group }}</span></td> -->
                                <td>{{ ls.tanggal_pinjam }}</td>
                                <td>{{ ls.tanggal_kembali }}</td>
                                <!-- <td>
                                    <span class="badge bg-success" v-if="statusCheck(ls.date_of_returning)">On Progress</span>
                                    <span class="badge bg-danger" v-else>Late</span>
                                </td> -->
                                <td>
                                    <button class="btn btn-sm btn-info" @click="Detail(ls.id_peminjaman_buku)" data-bs-toggle="modal" data-bs-target="#borrowdetail_modal" ><i class="fas fa-list fa-fw"></i></button>&nbsp;
                                    <button class="btn btn-sm btn-success" @click="Return(ls.id_siswa)" data-bs-toggle="modal" data-bs-target="#bookborrow_modal"><i class="fas fa-check fa-fw"></i></button>
                                    <!-- <button class="btn btn-danger" @click="Delete(ls.id_peminjaman_buku)" ><i class="fas fa-trash-alt fa-fw"></i></button> -->

                                </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="borrowdetail_modal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg bg-primary">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Borrowing Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-responsive table-stripped">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>COVER</th>
                                    <th>NAMA BUKU</th>
                                    <th>PENGARANG</th>
                                    <th>QTY</th>
                                </tr>
                            </thead>
 
                            <tbody>
                                <tr v-for="(detail, index) in list_detail_transaksi" :key="detail">
                                    <td>{{index+1}}</td>
                                    <td>
                                        <img v-if="detail.image !== null" :src="api_url2 + '/images/' + detail.image" width="150">
                                        <span v-else>No Image</span>
                                    </td>
                                    <td>{{ detail.nama_buku }}</td>
                                    <td>{{ detail.pengarang }}</td>
                                    <td>{{ detail.qty }}</td>
                                </tr>
                            </tbody> 
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="bookborrow_modal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl bg-primary">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Book Borrow</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                         <div class="mb-3">
                                            <div class="form-group">
                                                <label for="id_siswa" class="form-label">Nama Siswa</label>
                                                <select class="form-control" v-model="id_siswa">
                                                    <option value="" disabled>-- Pilih Siswa --</option>
                                                    <option v-for="ls in list_student" :key="ls.id_siswa" :value="ls.id_siswa">{{ ls.nama_siswa }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal_pinjam" class="form-label">Tanggal Peminjaman</label>
                                            <input type="date" class="form-control" id="tanggal_pinjam" v-model="tanggal_pinjam" placeholder="Tanggal Peminjaman">
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal_kembali" class="form-label">Tanggal Pengembalian</label>
                                            <input type="date" class="form-control" id="tanggal_kembali" v-model="tanggal_kembali" placeholder="Tanggal Pengembalian">
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-body">
                                        <button @click="addItem" class="btn btn-sm btn-primary text-white"><i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Item</button>
                                        <br><br>
                                        <div class="row" v-for="(detail, counter) in transaksi_detail" :key="counter">
                                            <br><br>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <select class="form-control" v-model="detail.id_buku">
                                                        <option value="" disabled>-- Pilih Buku --</option>
                                                        <option v-for="lb in list_book" :key="lb.id_buku" :value="lb.id_buku">{{ lb.nama_buku }} - {{ lb.pengarang }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Qty" type="number" v-model="detail.qty"> 
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-danger btn-sm" @click="deleteItem(counter)"> - </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button @click="Save()" class="btn btn-block btn-lg btn-success">SUBMIT</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal return -->
        <div class="modal fade" id="return_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Return Book</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-responsive table-stripped">
                            <thead>
                                <tr>
                                    <th>Late for (days)</th>
                                    <th>Punishmnet</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="rl in return_list" :key="rl">
                                    <td> {{ rl.tanggal_pengembalian }} </td>
                                    <td> {{ rl.denda }} </td>
                                    <td>
                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                        <button type="button" v-on:click="returnBook(rl.id_peminjaman_buku)" class="btn btn-primary btn-sm" data-dismiss="modal">Return Book</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button @click="Return" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Return Book</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="return_modal" tabindex="-1" role="dialog" aria-labelledby="return_modal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Return Book </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Late for (days)</th>
                                    <th>Fine</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="rl in return_list" :key="rl">
                                    <td> {{ rl.tanggal_pengembalian }} </td>
                                    <td> {{ rl.denda }} </td>
                                    <td>
                                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                        <button type="button" v-on:click="returnBook(rl.id_peminjaman_buku)" class="btn btn-primary btn-sm" data-dismiss="modal">Return Book</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
module.exports = {
    data : function(){
        return {
            id_siswa: "",
            tanggal_pinjam: "",
            tanggal_kembali: "",
            list_transaksi: [],
            list_detail_transaksi: [],

            //for transaction form
            list_book: [],
            list_student: [],
            return_list : [],
            transaksi_detail: [],

        }
    },
    methods: {
        addItem(){
            this.transaksi_detail.push({ 
                id_buku: '',
                qty: '',
            })
        },
        deleteItem(counter){
            this.transaksi_detail.splice(counter,1);
        },
        getBook: function(){
            //ambil data buku untuk dropdown
            let token = {
                headers : { 
                    "Authorization" : "Bearer " + this.$cookies.get("Authorization")
                }
            }
            axios.get(api_url + "/buku", token)
            .then(response => {
                this.list_book = response.data;
            })
        },
        getStudent: function(){
            //ambil data student untuk dropdown
            let token = {
                headers : { 
                    "Authorization" : "Bearer " + this.$cookies.get("Authorization")
                }
            }
            axios.get(api_url + "/siswa", token)
            .then(response => {
                this.list_student = response.data;
            })
        },
        getData: function(){
            let token = {
                headers : { 
                    "Authorization" : "Bearer " + this.$cookies.get("Authorization")
                }
            }
            axios.get(api_url + "/peminjaman_buku", token)
            .then( response => {
                // console.log(response.data)
                this.list_transaksi = response.data;
            })
        },
        // statusCheck: function(date_of_returning){
        //     const status = moment().isBefore(moment(date_of_returning))
        //     if(status){
        //         return true
        //     } else {
        //         return false
        //     }
        // },
        Add: function() {
            this.id_siswa = ""
            this.tanggal_pinjam = ""
            this.tanggal_kembali = ""

            this.getBook()
            this.getStudent()
        },

        Detail(id_peminjaman_buku) {
            //get detail
            let token = {
                headers : { "Authorization" : "Bearer " + this.$cookies.get("Authorization")}
            }
            axios.get(api_url + "/detail_peminjaman_buku/" + id_peminjaman_buku, token)
            .then( response => {
                // console.log(response.data)
                this.list_detail_transaksi = response.data
            })
        },

        returnData(id_peminjaman_buku){
            let token = {
                headers : {
                    'Authorization' : 'Bearer ' + this.$cookies.get('Authorization')
                }
            }
            axios.get(api_url + '/pengembalian_buku/' + id_peminjaman_buku, token)
            .then(response => {
                // console.log(response.data);
                this.return_list = response.data
                console.log(this.return_list);
                
            })
        },

        Return(id){
            let token = {
                headers : {
                    'Authorization' : 'Bearer ' + this.$cookies.get('Authorization')
                }
            }

            let form = {
                'id_peminjaman_buku' : id
            }

            axios.post(api_url + '/pengembalian_buku/', form, token)
            .then(response => {
                console.log(response.data)
                Swal.fire({
                    title: 'Success!',
                    text: 'Success return book',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                })
                this.getData()
            })
        },

        Save: function() {
            let token = {
                headers : { 
                    "Authorization" : "Bearer " + this.$cookies.get("Authorization")
                }
            }
            
            let form = {
                "id_siswa": this.id_siswa,
                "tanggal_pinjam": this.tanggal_pinjam,
                "tanggal_kembali": this.tanggal_kembali,
                "detail_peminjaman_buku": this.transaksi_detail
            }

            console.log(form);
            axios.post(api_url + '/peminjaman_buku', form, token)
            .then( response => {
                if(response.data.status === 1) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    })
                }else{
                    Swal.fire({
                        title: 'Failed!',
                        text: response.data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                }
            })
            this.getData()
        },
    //     Delete: function(id_peminjaman_buku) {
    //         //mapping header token
    //         let token = {
    //             headers : { "Authorization" : "Bearer " + this.$cookies.get("Authorization")}
    //         }

    //         Swal.fire({
    //             title: 'Hapus Data Buku',
    //             text: 'Apakah anda yakin menghapus data ini?',
    //             icon: 'warning',
    //             showDenyButton: true,
    //             showCancelButton: false,
    //             confirmButtonText: 'Ya',
    //             denyButtonText: `Tidak`,
    //         }).then((result) => {
    //             if (result.isConfirmed) {
    //                  axios.delete(api_url + '/peminjaman_buku' + id_peminjaman_buku, token)
    //                 .then( response => {
    //                     Swal.fire({
    //                         title: 'Success!',
    //                         text: response.data.message,
    //                         icon: 'success',
    //                         confirmButtonText: 'OK'
    //                     })
    //                     this.getData()
    //                 })

    //         } else if (result.isDenied) {
    //                 Swal.fire({
    //                     title: 'Batal!',
    //                     text: 'Data tidak jadi dihapus',
    //                     icon: 'error',
    //                     confirmButtonText: 'OK'
    //                 })
    //             }
    //         })
    //     }
    },
    
    mounted(){
        this.getData()
    },
}
</script>