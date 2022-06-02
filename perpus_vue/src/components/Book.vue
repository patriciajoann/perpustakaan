<template>
    <div>
       <div class="container-fluid px-4">
            <h1 class="mt-4" align="center">Book Data</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item active">Buku</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    List Book

                    <button @click="Add()" data-bs-toggle="modal" data-bs-target="#book_modal" class="btn btn-primary btn-sm float-end"><i class="fas fa-plus fa-fw"></i> Add Book</button>
                </div>
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-hover table-striped">
                        <thead> 
                            <tr>
                                <th><center>BOOK ID</center></th>
                                <th><center>COVER</center></th>
                                <th><center>BOOK NAME</center></th>
                                <th><center>AUTHOR</center></th>
                                <th><center>DESC</center></th>
                                <th><center>IMAGE</center></th>
                                <th><center>ACTION</center></th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr v-for="lb in list_book" :key="lb">
                                <td align="center">{{ lb.id_buku }}</td>
                                <td align="center"><img :src="api_url2 + '/images/' + lb.image" width="150"></td>
                                <td align="center">{{ lb.nama_buku }}</td>
                                <td align="center">{{ lb.pengarang }}</td>
                                <td align="center">{{ lb.deskripsi }}</td>
                                <td align="center">
                                    <button class="btn btn-warning" @click="Edit(lb)" data-bs-toggle="modal" data-bs-target="#book_cover_modal" ><i class="fas fa-image fa-fw"></i></button>
                                </td>
                                <td align="center">
                                    <button class="btn btn-info" @click="Edit(lb)" data-bs-toggle="modal" data-bs-target="#book_modal" ><i class="fas fa-pencil-alt fa-fw"></i></button>
                                    <button class="btn btn-danger" @click="Delete(lb.id_buku)"><i class="fas fa-trash-alt fa-fw"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="book_modal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Book Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_buku" class="form-label">Book Name</label>
                            <input type="text" class="form-control" id="nama_buku" v-model="nama_buku" placeholder="Book Name">
                        </div>

                        <div class="mb-3">
                            <label for="pengarang" class="form-label">Author</label>
                            <input type="text" class="form-control" id="pengarang" v-model="pengarang" placeholder="Author">
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Description</label>
                            <textarea class="form-control" id="deskripsi" v-model="deskripsi" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="Save()" data-bs-dismiss="modal">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="book_cover_modal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Book Cover</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="buku_cover" class="form-label">Book Cover</label>
                            <input type="file" class="form-control" id="buku_cover" @change="uploadCover($event)">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="Upload(id_buku)" data-bs-dismiss="modal">Submit</button>
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
            id_buku: "",
            nama_buku: "",
            pengarang: "",
            deskripsi: "",
            buku_cover: "",
            aksi: "",
            list_book: [],
        }
    },
    methods: {
        getData: function(){
            //token
            let token = {
                headers : { "Authorization" : "Bearer " + this.$cookies.get("Authorization")}
            }
            axios.get(api_url + "/buku", token)
            .then( response => {
                this.list_book = response.data;
            })
        },
        Add: function() {
            this.id_buku = ""
            this.nama_buku = ""
            this.pengarang = ""
            this.deskripsi = ""
            this.aksi = "insert"
        },
        Edit: function(lb){
            this.id_buku = lb.id_buku
            this.nama_buku = lb.nama_buku
            this.pengarang = lb.pengarang
            this.deskripsi = lb.deskripsi
            this.aksi = "update"
        },
        uploadCover: function(e){ 
            this.buku_cover = e.target.files[0]
        },
        Upload: function(id){
            let token = {
                headers : { 
                    "Authorization" : "Bearer " + this.$cookies.get("Authorization"), 
                    'Content-Type' : 'multipart/form-data',
                }
            }

            let form  = new FormData()
            form.append("buku_cover", this.buku_cover)

            axios.post(api_url + '/buku/UploadCover/'+ id, form, token) 
            .then( response => {
                Swal.fire({
                    title: 'Success!',
                    text: response.data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                })

                this.getData()
            })

         }, 
        Save: function() {
            //mapping header token
            let token = {
                headers : { 
                    "Authorization" : "Bearer " + this.$cookies.get("Authorization")
                }
            }

            //mapping data
            let form  = {
                //backend       //state
                'nama_buku': this.nama_buku,
                'pengarang': this.pengarang,
                'deskripsi': this.deskripsi,
                'buku_cover' : this.buku_cover
            }

            if(this.aksi === 'insert'){ //POST

                axios.post(api_url + '/buku', form, token)
                .then( response => {
                    Swal.fire({
                        title: 'Success',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    })
                })

            } else { //PUT
                axios.put(api_url + '/buku/' + this.id_buku, form, token)
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
        Delete: function(id_buku){
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
                     axios.delete(api_url + '/buku/' + id_buku, token)
                    .then( response => {

                        Swal.fire({
                            title: 'Success!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        })

                        this.getData()
                    })

                    //Swal.fire('Saved!', '', 'success')
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
