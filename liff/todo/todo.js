(window.onload = () => {

  const { createApp } = Vue;
  const vuetify = Vuetify.createVuetify({
    theme: {
 
    }
  });
  
  const app = createApp({
    data() {
      return {
        title: "",
        todo: "",
        loading: false,
        isInit: false,
        lists:[],
        complete: false,
        taskDialog:{
          title: "",
          detail: "",
          disp: false
        },
        dialog: {
            msg: "",
            disp: false
        }
      }
    },
    methods:{
      submit(){
        if(this.title === "" || this.todo === ""){
            this.openDialog("内容を入力");
            return ;
        }
        this.loading = true;

        let post_data = new FormData();
        post_data.append("title", this.title);
        post_data.append("todo", this.todo);
        axios.post("./save.php", post_data).then(res => {
            this.complete = true;
            this.getList();
        }).catch((er) => {
            this.openDialog(er);
        }).finally(() => {
            this.loading = false;
        });
      },
      getList(){
        axios.get("./get.php").then(response => {
          if(response.data.result == 1){
            this.lists = response.data.lists;
          }
        }).catch(er => {
          this.openDialog(er.toString());
        }).finally(() => {
          this.loading = false;
        });
      },
      showTodo(item){
        this.taskDialog.title = item.title;
        this.taskDialog.detail = item.detail;
        this.taskDialog.disp = true;
      },
      closeTodo(){
        this.taskDialog.title = "";
        this.taskDialog.detail = "";
        this.taskDialog.disp = false;
      },
      taskDone(item){
        let post_data = new FormData();
        post_data.append("title", item.title);
        this.loading = true;
        axios.post("./done.php", post_data).then(response => {
          if(response.data.result == 1){
            this.loading = false;
            this.getList();
          }
        }).catch(er => {

        })
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
          this.getList();
        });
      });
    }
  }).use(vuetify).mount('#container');

});