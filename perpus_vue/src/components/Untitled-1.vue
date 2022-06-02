<template>
    <div>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Tabel</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item active">Peminjaman Buku</li>
            </ol>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                        Daftar Peminjaman Buku

                        <button @click="Add()" data-bs-toggle="modal" data-bs-target="#bookborrow_modal" class="btn btn-primary btn-sm float-end"><i class="fas fa-plus fa-fw"></i>Tambah Peminjaman Buku</button>
                </div>
                
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-hover table-stripped">
                        <thead> 
                            <tr>
                                <th>Id Siswa</th>
                                <th>Nama Siswa</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="lb in list_bookborrow" :key="lb">
                                <td>{{ lb.id_siswa }}</td>
                                <td>{{ lb.nama_siswa }}</td>
                                <td>{{ lb.tanggal_pinjam }}</td>
                                <td>{{ lb.tanggal_kembali }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" @click="Detail(ls)" data-bs-toggle="modal" data-bs-target="#borrowdetail_modal" ><i class="fas fa-list fa-fw"></i></button>&nbsp;
                                    <button class="btn btn-sm btn-success" @click="Return(ls.id_siswa)" data-bs-toggle="modal" data-bs-target="#bookborrow_modal"><i class="fas fa-check fa-fw"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="bookborrow_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Peminjaman Buku</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="id_siswa">Id Siswa</label>
                            <input type="text" class="form-control" id="id_siswa" v-model="id_siswa" placeholder="Id Siswa">
                        </div>

                         <div class="form-group">
                            <label for="nama_siswa">Nama Siswa</label>
                            <input type="text" class="form-control" id="nama_siswa" v-model="nama_siswa" placeholder="Nama Siswa">
                        </div>

                        <div class="form-group">
                            <label for="tanggal_pinjam">Tanggal Pinjam</label>
                            <input type="date" class="form-control" id="tanggal_pinjam" v-model="tanggal_pinjam" placeholder="Tanggal Pinjam">
                        </div>

                        <div class="form-group">
                            <label for="tanggal_kembali">Tanggal Kembali</label>
                            <input type="date" class="form-control" id="tanggal_kembali" v-model="tanggal_kembali" placeholder="Tanggal Kembali">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="Save()" data-bs-toggle="modal">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
module.exports = {
    //state
    data : function(){
        return {
            id_siswa : "",
            nama_siswa : "",
            tanggal_pinjam : "",
            tanggal_kembali : "",
            // buku_cover: "",
            aksi : "",
            list_bookborrow : [],
            list_student : [],
        }
    },

    methods : {
        getData : function(){
            //maping header token dulu
            let token = {
                headers : { "Authorization" : "Bearer " + this.$cookies.get("Authorization")}
            }

            axios.get(api_url + "/peminjaman_buku", token)
            .then(response => {
                this.list_bookborrow = response.data;
                console.log(response)
                
            }),

            axios.get(api_url + "/siswa", token)
            .then(response => {
                this.list_student = response.data;
                
            })
        },
        Add: function() {
            this.id_siswa = ""
            this.nama_siswa = ""
            this.tanggal_pinjam = ""
            this.tanggal_kembali = ""
            this.aksi = "insert"
        },
        // Edit: function(lb) {
        //     this.id_siswa = lb.id_siswa
        //     this.tanggal_pinjam = lb.tanggal_pinjam
        //     this.tanggal_kembali = lb.tanggal_kembali
        //     this.aksi = "update"
        // },

        Save: function() {
            //mapping header token
            let token = {
                headers : { "Authorization" : "Bearer " + this.$cookies.get("Authorization")}
            }

            //mapping data
            let form = {
                //harus sesuai dengan backend //state
                'id_siswa': this.id_siswa,
                'nama_siswa': this.nama_siswa,
                'tangggal_pinjam': this.tangggal_pinjam,
                'tanggal_kembali': this.tanggal_kembali,
                // 'buku_cover': this.buku_cover
            }

            if(this.aksi == 'insert') { //POST

                axios.post(api_url + '/peminjaman_buku', form, token)
                .then( response => {
                        Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    })
                })

            } else { //PUT
                    axios.put(api_url + '/peminjaman_buku/' + this.id_peminjaman_buku, form, token)
                .then( response => {
                        Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    })
                })
            }

            this.getData()
        
        },
        Delete: function(id_peminjaman_buku) {
            //mapping header token
            let token = {
                headers : { "Authorization" : "Bearer " + this.$cookies.get("Authorization")}
            }

            Swal.fire({
                title: 'Hapus Data Buku',
                text: 'Apakah anda yakin menghapus data ini?',
                icon: 'warning',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Ya',
                denyButtonText: `Tidak`,
            }).then((result) => {
                if (result.isConfirmed) {
                     axios.delete(api_url + '/peminjaman_buku' + id_peminjaman_buku, token)
                    .then( response => {
                        Swal.fire({
                            title: 'Success!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        })
                        this.getData()
                    })

            } else if (result.isDenied) {
                    Swal.fire({
                        title: 'Batal!',
                        text: 'Data tidak jadi dihapus',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                }
            })
        }
    },
    mounted() {
        this.getData()
    },
}
</script>
