<template>
    <div>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Tabel</h1>

            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item active">Pengembalian Buku</li>
            </ol>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                        Daftar Pengembalian Buku

                        <button @click="Add()" data-bs-toggle="modal" data-bs-target="#bookreturn_modal" class="btn btn-primary btn-sm float-end"><i class="fas fa-plus fa-fw"></i>Tambah Pengembalian Buku</button>
                </div>
                
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-hover table-stripped">
                        <thead> 
                            <tr>
                                <th>Id Pengembalian Buku</th>
                                <th>Id Peminjaman Buku</th>
                                <th>Tanggal Pengembalian</th>
                                <th>Denda</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="lb in list_bookreturn" :key="lb">
                                <td>{{ lb.id_pengembalian_buku }}</td>
                                <td>{{ lb.id_peminjaman_buku }}</td>
                                <td>{{ lb.tanggal_pengembalian }}</td>
                                <td>{{ lb.denda }}</td>
                                <td>
                                    <!-- <button class="btn btn-default" @click="Edit(lb)" data-bs-toggle="modal" data-bs-target="#bookcover_modal" ><i class="fas fa-image fa-fw"></i></button> -->
                                    <button class="btn btn-info" @click="Edit(lb)" data-bs-toggle="modal" data-bs-target="#bookreturn_modal"><i class="fas fa-pencil-alt fa-fw"></i></button>
                                    <button class="btn btn-danger" @click="Delete(lb.id_pengembalian_buku)" ><i class="fas fa-trash-alt fa-fw"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="bookreturn_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Pengembalian Buku</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="id_pengembalian_buku">Id Pengembalian Buku</label>
                            <input type="number" class="form-control" id="id_pengembalian_buku" v-model="id_pengembalian_buku" placeholder="Id Pengembalian Buku">
                        </div>

                        <div class="form-group">
                            <label for="id_peminjaman_buku">Id Peminjaman Buku</label>
                            <input type="text" class="form-control" id="id_peminjaman_buku" v-model="id_peminjaman_buku" placeholder="Id Peminjaman Buku">
                        </div>

                        <div class="form-group">
                            <label for="tanggal_pengembalian">Tanggal Pengembalian</label>
                            <input type="date" class="form-control" id="tanggal_pengembalian" v-model="tanggal_pengembalian" placeholder="Tanggal Pengembalian">
                        </div>

                         <div class="form-group">
                            <label for="denda">Denda</label>
                            <input type="number" class="form-control" id="denda" v-model="denda" placeholder="Denda">
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
            id_pengembalian_buku : "",
            id_peminjaman_buku : "",
            tanggal_pengembalian : "",
            denda : "",
            // buku_cover: "",
            aksi : "",
            list_bookreturn : [],
        }
    },

    methods : {
        getData : function(){
            //maping header token dulu
            let token = {
                headers : { "Authorization" : "Bearer " + this.$cookies.get("Authorization")}
            }

            axios.get(api_url + "/pengembalian_buku", token)
            .then(response => {
                this.list_bookreturn = response.data;
            })
        },
        Add: function() {
            this.id_pengembalian_buku = ""
            this.id_peminjaman_buku = ""
            this.tanggal_pengembalian = ""
            this.denda = ""
            this.aksi = "insert"
        },
        Edit: function(lb) {
            this.id_pengembalian_buku = lb.id_pengembalian_buku
            this.id_peminjaman_buku = lb.id_peminjaman_buku
            this.tanggal_pengembalian = lb.tanggal_pengembalian
            this.denda = lb.denda
            this.aksi = "update"
        },

        Save: function() {
            //mapping header token
            let token = {
                headers : { "Authorization" : "Bearer " + this.$cookies.get("Authorization")}
            }

            //mapping data
            let form = {
                //harus sesuai dengan backend //state
                'id_pengembalian_buku': this.id_pengembalian_buku,
                'id_peminjaman_buku': this.id_peminjaman_buku,
                'tanggal_pengembalian': this.tanggal_pengembalian,
                'denda' : this.denda,
                // 'buku_cover': this.buku_cover
            }

            if(this.aksi == 'insert') { //POST

                axios.post(api_url + '/pengembalian_buku', form, token)
                .then( response => {
                        Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    })
                })

            } else { //PUT
                    axios.put(api_url + '/pengembalian_buku/' + this.id_pengembalian_buku, form, token)
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
        Delete: function(id_pengembalian_buku) {
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
                     axios.delete(api_url + '/pengembalian_buku' + id_pengembalian_buku, token)
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
