
function submit(){

    let pay = parseInt(document.getElementById("pay-value").value);
    let detail = document.getElementById("pay-detail").value;
    if(parseInt(pay) <= 0 || detail === ""){
        return ;
    }

    document.getElementById("btn-load").className = "";
    document.getElementById("btn-content").innerHTML = "Loading...";

    let post_data = new FormData();
    post_data.append("value", pay);
    post_data.append("detail", detail);
    axios.post("./save.php", post_data).then(res => {
        document.getElementById("close").className = "row ms-1 mb-3";
    }).catch(er => {

    }).finally(() => {
        document.getElementById("btn-load").className = "fade";
        document.getElementById("btn-content").innerHTML = "送信";
    });
}


(window.onload = () => {
  const { createApp } = Vue;
  const vuetify = Vuetify.createVuetify({
    theme: {
 
    }
  });

  createApp({
    data() {
      return {
        pay: 0,
        detail: "",
        loading: false,
        done: false,
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
            this.done = true;
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
      }
    }
  }).use(vuetify).mount('#container');
});