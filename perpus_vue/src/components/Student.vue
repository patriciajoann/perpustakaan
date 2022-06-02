<template>
    <div>
        <div class="container-fluid px-4">
            <h1 class="mt-4" align="center">Student Data</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                <li class="breadcrumb-item active">Siswa</li>
            </ol>
            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                        Daftar Siswa

                        <button @click="Add()" data-bs-toggle="modal" data-bs-target="#student_modal" class="btn btn-primary btn-sm float-end"><i class="fas fa-plus fa-fw"></i>Tambah Siswa</button>
                </div>
                
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-hover table-stripped">
                        <thead>
                            <tr>
                                <th>Id Siswa</th>
                                <!-- <th>Cover Buku</th> -->
                                <th>Nama Siswa</th>
                                <th><center>Foto Siswa</center></th>
                                <th>Tanggal Lahir</th>
                                <th>Gender</th>
                                <th>Alamat</th>
                                <th>Id Kelas</th>
                                <th><center>Image</center></th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="lb in list_student" :key="lb">
                                <td>{{ lb.id_siswa }}</td>
                                <td>{{ lb.nama_siswa }}</td>
                                <td align="center"><img :src="api_url2 + '/images/' + lb.image" width="150"></td>
                                <td>{{ lb.tanggal_lahir }}</td>
                                <td>{{ lb.gender }}</td>
                                <td>{{ lb.alamat }}</td>
                                <td>{{ lb.id_kelas }}</td>
                               <td align="center">
                                    <button class="btn btn-warning" @click="Edit(lb)" data-bs-toggle="modal" data-bs-target="#studentcover_modal" ><i class="fas fa-image fa-fw"></i></button>
                                </td>
                                <td>
                                    <button class="btn btn-info" @click="Edit(lb)" data-bs-toggle="modal" data-bs-target="#student_modal"><i class="fas fa-pencil-alt fa-fw"></i></button>
                                    <button class="btn btn-danger" @click="Delete(lb.id_siswa)" ><i class="fas fa-trash-alt fa-fw"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="student_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Siswa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_siswa">Nama Siswa</label>
                            <input type="text" class="form-control" id="nama_siswa" v-model="nama_siswa" placeholder="Nama Siswa">
                        </div>

                         <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" v-model="tanggal_lahir" placeholder="Tanggal Lahir">
                        </div>


                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control" id="gender" v-model="gender">
                                <option value="P">P</option>
                                <option value="L">L</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" v-model="alamat" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="id_kelas" class="form-label">Kelas</label>
                            <select class="form-control" id="id_class" v-model="id_kelas">
                                <option v-for="lb in list_class" :key='lb' v-bind:value="lb.id_kelas">{{lb.nama_kelas}} - {{lb.kelompok}}</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="Save()" data-bs-toggle="modal">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="studentcover_modal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Siswa Cover</h5>
                        <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="siswa_cover" class="form-label">Siswa Cover</label>
                            <input type="file" class="form-control" id="siswa_cover" @change="uploadCover($event)">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="Upload(id_siswa)" data-bs-toggle="modal" data-bs-target="#studentcover_modal">Submit</button>
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
            tanggal_lahir : "",
            gender : "",
            alamat : "",
            id_kelas : "",
            aksi : "",
            list_class : [],
            list_student : [],
        }
    },

    methods : {
        getData : function(){
            //maping header token dulu
            let token = {
                headers : { "Authorization" : "Bearer " + this.$cookies.get("Authorization")}
            }

            axios.get(api_url + "/siswa", token)
            .then(response => {
                this.list_student= response.data;
            })

            axios.get(api_url + '/kelas', token)
            .then( response => {
                this.list_class = response.data;

            })
        },
        Add: function() {
            this.id_siswa = ""
            this.nama_siswa = ""
            this.tanggal_lahir = ""
            this.gender = ""
            this.alamat = ""
            this.id_kelas = ""
            this.aksi = "insert"

            this.getData()
        },
        Edit: function(lb) {
            this.id_siswa = lb.id_siswa
            this.nama_siswa = lb.nama_siswa
            this.tanggal_lahir = lb.tanggal_lahir
            this.gender = lb.gender
            this.alamat = lb.alamat
            this.id_kelas = lb.id_kelas
            this.aksi = "update"
        },

        uploadCover: function(e){
            this.siswa_cover = e.target.files[0]
        },
        Upload: function(id){
            let token = {
                headers : { 
                    "Authorization" : "Bearer " + this.$cookies.get("Authorization"), 
                    'Content-Type' : 'multipart/form-data',
                }
            }
            let form  = new FormData()
            form.append("siswa_cover", this.siswa_cover)
            axios.post(api_url + '/siswa/UploadCover/'+ id, form, token)
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
                headers : { "Authorization" : "Bearer " + this.$cookies.get("Authorization")}
            }

            //mapping data
            let form = {
                //harus sesuai dengan backend //state
                'nama_siswa': this.nama_siswa,
                'tanggal_lahir': this.tanggal_lahir,
                'gender': this.gender,
                'alamat': this.alamat,
                'id_kelas': this.id_kelas
            }

            if(this.aksi == 'insert') { //POST

                axios.post(api_url + '/siswa', form, token)
                .then( response => {
                        // console.log(response)
                        Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    })
                })

            } else { //PUT
                    axios.put(api_url + '/siswa/' + this.id_siswa, form, token)
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
        Delete: function(id_siswa) {
            //mapping header token
            let token = {
                headers : { "Authorization" : "Bearer " + this.$cookies.get("Authorization")}
            }

            Swal.fire({
                title: 'Hapus Data Siswa',
                text: 'Apakah anda yakin menghapus data ini?',
                icon: 'warning',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Ya',
                denyButtonText: `Tidak`,
            }).then((result) => {
                if (result.isConfirmed) {
                     axios.delete(api_url + '/siswa/' + id_siswa, token)
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
