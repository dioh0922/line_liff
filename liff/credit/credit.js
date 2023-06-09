(window.onload = () => {

  const { createApp } = Vue;
  const vuetify = Vuetify.createVuetify({
    theme: {
 
    }
  });

  const app = createApp({
    data() {
      return {
        pay: 0,
        detail: "",
        loading: false,
        isInit: false,
        complete: false,
        dialog: {
            msg: "",
            disp: false
        }
      }
    },
    methods:{
      submit(){
        let post = new FormData();
        post.append("destination", this.text);
        if(parseInt(this.pay) <= 0 || this.detail === ""){
            this.openDialog("内容を入力");
            return ;
        }
        this.loading = true;

        let post_data = new FormData();
        post_data.append("value", this.pay);
        post_data.append("detail", this.detail);
        axios.post("./save.php", post_data).then(res => {
            this.complete = true;
        }).catch((er) => {
            this.openDialog(er);
        }).finally(() => {
            this.loading = false;
        });
      },
      done() {
        liff.closeWindow();
      },
      openDialog(msg){
        this.dialog.msg = msg;
        this.dialog.disp = true;
        this.loading = false;
      },
      closeDialog(){
        this.dialog.msg = "";
        this.dialog.disp = false;
      },
    },
    mounted(){
      axios.get("../../../util_api/liffId.php").then((res) => {
        liff.init({liffId: res.data.liffId}).then(() => {    
          this.isInit = true;
        });
      });
    }
  }).use(vuetify).mount('#container');

});