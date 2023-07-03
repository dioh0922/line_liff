(window.onload = () => {
  const { createApp } = Vue;
  const vuetify = Vuetify.createVuetify({
    theme: {
 
    }
  });

  const app = createApp({
    data() {
      return {
        text:"",
        lists: [],
        loading: false,
        isInit: false
      }
    },
    methods:{
      fetchTodo(){
        axios.get("./get.php").then(res => {
          if(res.data.result == 1){
            this.loading = false;
            this.lists = res.data.lists;
          }
        }).catch(err => {
          this.loading = false;
        });
      },
      submit(){
        this.loading = true;
        let post = new FormData();
        post.append("destination", this.text);
        axios.post("./add.php", post).then(res => {
          this.loading = false;
          this.fetchTodo();
        }).catch(err => {
          this.loading = false;
        });
      },
      submitDone(e){
        this.loading = true;
        let post = new FormData();
        post.append("destination", e);
        axios.post("./done.php", post).then(res => {
          this.loading = false;
          this.fetchTodo();
        }).catch(err => {
          this.loading = false;
        }); 
      }
    },
    mounted(){
      axios.get("../../../util_api/liffId.php").then((res) => {
        liff.init({liffId: res.data.liffId}).then(() => {
          this.isInit = true;
          this.fetchTodo();
        });
      });
    }
  }).use(vuetify).mount('#container');

});
