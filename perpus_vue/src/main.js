var api_url = "http://127.0.0.1:8000/api";
var api_url2 = "http://127.0.0.1:8000";

//konfigurasi route
var router = [
    {   
        path: "/", 
        name: "Home", 
        component: httpVueLoader("./src/components/Home.vue")
    },
    {   
        path: "/book", 
        name: "Book", 
        component: httpVueLoader("./src/components/Book.vue")
    },
    {
        path: "/student", 
        name: "Student", 
        component: httpVueLoader("./src/components/Student.vue")
    },
    {
        path: "/kelas", 
        name: "Kelas", 
        component: httpVueLoader("./src/components/Kelas.vue")
    },
    {
        path: "/bookborrow", 
        name: "BookBorrow", 
        component: httpVueLoader("./src/components/BookBorrow.vue")
    },
    // {
    //     path: "/bookborrowdetail", 
    //     name: "BookBorrowDetail", 
    //     component: httpVueLoader("./src/components/BookBorrowDetail.vue")
    // },
    // {
    //     path: "/bookreturn", 
    //     name: "BookReturn", 
    //     component: httpVueLoader("./src/components/BookReturn.vue")
    // },
  
];

var routers = new VueRouter({ routes : router, base: "/"});


//INSTANCE VUE

var app = new Vue({
    el: "#app",
    components: {
      'apps' : httpVueLoader("./src/components/App.vue"),
      'login' : httpVueLoader("./src/components/Login.vue"),
      'book' : httpVueLoader("./src/components/Book.vue"),
      'student' : httpVueLoader("./src/components/Student.vue"),
      'kelas' : httpVueLoader("./src/components/Kelas.vue"),
      'bookborrow' : httpVueLoader("./src/components/BookBorrow.vue"),
      // 'bookborrowdetail' : httpVueLoader("./src/components/BookBorrowDetail.vue"),
      // 'bookreturn' : httpVueLoader("./src/components/BookReturn.vue"),
    },
    data: { //hak akses, username, email(sifatnya global)
      user : {
        id_user: "",
        role: "",
        name: "",
        username: "",
      },
      mycomponent: "",
    },
    router: routers,
    methods: {
      CekToken : function(){
        // punya token di cookies apa tidak?
        if(this.$cookies.isKey('Authorization')){
          //kalau punya
          //maping header token dulu
          let token = {
            headers : { "Authorization" : "Bearer " + this.$cookies.get("Authorization")}
          }
          //cek kevalidan token di backedn
          axios.get(api_url + '/login_check' , token)
          .then(response => {
            if(response.data.status === 1){

              this.mycomponent = 'apps';
              this.id_user = response.data.data.id;
              this.role = response.data.data.role;
              this.name = response.data.data.name;
              this.username = response.data.data.username;

            } else {
              this.mycomponent = 'login'
            }
          })


        } else {
          //kalau tidak punya
          this.mycomponent = 'login'
        }
        

      },
    },
    mounted(){
      this.CekToken();
    }
  });