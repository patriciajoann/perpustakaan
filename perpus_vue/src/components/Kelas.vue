<template>
    <div>
       <div class="container-fluid px-4">
            <h1 class="mt-4" align="center">Class Data</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="/perpus_vue/#/home">Dashboard</a></li>
                <li class="breadcrumb-item active">Kelas</li>
            </ol>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    List Kelas

                    <button @click="Add()" data-bs-toggle="modal" data-bs-target="#class_modal" class="btn btn-primary btn-sm float-end"><i class="fas fa-plus fa-fw"></i> Add Class</button>
                </div>
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Id Kelas</th>
                                <th>Nama Kelas</th>
                                <th>Kelompok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="lb in list_class" :key="lb">
                                <td>{{ lb.id_kelas }}</td>
                                <td>{{ lb.nama_kelas }}</td>
                                <td>{{ lb.kelompok }}</td>
                                <td>
                                    <button class="btn btn-info" @click="Edit(lb)" data-bs-toggle="modal" data-bs-target="#class_modal" ><i class="fas fa-pencil-alt fa-fw"></i></button>
                                    <button class="btn btn-danger" @click="Delete(lb.id_kelas)"><i class="fas fa-trash-alt fa-fw"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="class_modal" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Data Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kelas" class="form-label">Nama Kelas</label>
                            <input type="text" class="form-control" id="nama_kelas" v-model="nama_kelas" placeholder="Nama Kelas">
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kelompok" class="form-label">Kelompok</label>
                            <input type="number" class="form-control" id="kelompok" v-model="kelompok" placeholder="Kelompok">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="Save()" data-bs-dismiss="modal">Submit</button>
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
            id_kelas:"",
            nama_kelas:"",
            kelompok:"",
            aksi:"",
            list_class: [],
         }
     },
    methods: {
        getData: function(){
            //mapping header token
            let config = {
            headers : {
              "Authorization" : "Bearer " + this.$cookies.get('Authorization')}
            }
            axios.get(api_url + "/kelas" , config)
            .then( response => {
                this.list_class = response.data;
            })
        },
        Add: function() {
           this.id_kelas = ""
           this.nama_kelas = ""
           this.kelompok = ""
           this.aksi = "insert" 
        },
        Edit: function(lb){
            this.id_kelas=lb.id_kelas
            this.nama_kelas=lb.nama_kelas
            this.kelompok=lb.kelompok
            this.aksi="update"
        },
    Save: function() {
            //mapping header token
            let config = {
                headers : { 
                    "Authorization" : "Bearer " + this.$cookies.get("Authorization")
                }
            }

            //mapping data
            let form  = {
                //backend       //state
                'nama_kelas': this.nama_kelas,
                'kelompok': this.kelompok,
            }

           
            if(this.aksi === 'insert'){ //POST

            axios.post(api_url + '/kelas', form, config)
                .then( response => {
                   Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'ok'
                    })
                })

            } else { //PUT
                axios.put(api_url + '/kelas/' + this.id_kelas, form, config)
                .then( response => {
                    Swal.fire({
                        title: 'Success!',
                        text: response.data.message,
                        icon: 'success',
                        confirmButtonText: 'ok'
                    })
                })
            }

            this.getData()
        },
    Delete: function(id_kelas){
            //mapping header token
            let config = {
                headers : { "Authorization" : "Bearer " + this.$cookies.get("Authorization")}
            }

            Swal.fire({
                title: 'Delete Class Data',
                text: 'Are you sure you delete this data ?',
                icon: 'warning',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Yes',
                denyButtonText: `Cancel`,
            }).then((result) => {
                if (result.isConfirmed) {
                     axios.delete(api_url + '/kelas/' + id_kelas, config)
                    .then( response => {

                        Swal.fire({
                            title: 'Success !',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'ok'
                        })

                        this.getData()
                    })

                    //Swal.fire('Saved!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire({
                        title: 'Cancel !',
                        text: 'Data is not deleted',
                        icon: 'error',
                        confirmButtonText: 'ok'
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