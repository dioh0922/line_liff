<template>

<v-app>
  <v-app-bar flat color="red-lighten-4" text-align="center" app>
      <h3 class="mx-auto">TODO追加</h3>
  </v-app-bar>
  <v-main>
      <v-container>
          <v-row justify="center" v-if="!isInit">
              <v-progress-circular indeterminate :size="79"></v-progress-circular>
          </v-row>
          <div v-else>
              <v-row>
                  <h3 class="mx-auto">概要</h3>
              </v-row>
              <v-row>
                  <v-col cols="10" class="mx-auto">
                      <v-text-field class="mx-auto" v-model:model-value="title" width="60%" v-model="title"></v-text-field>
                  </v-col>
              </v-row>

              <v-row>
                  <h3 class="mx-auto">詳細</h3>
              </v-row>

              <v-row>
                  <v-col cols="10" class="mx-auto">
                      <v-textarea class="mx-auto" v-model:model-value="todo" width="60%" maxLength="300"></v-textarea>
                  </v-col>
              </v-row>

              <v-row v-show="!complete">
                  <v-btn class="mx-auto" width="80%" color="blue" @click="submit" :loading="loading">送信</v-btn>
              </v-row>

              <v-row v-show="complete">
                  <v-btn class="mx-auto mt-10" width="80%" color="grey" @click="done">トークに戻る</v-btn>
              </v-row>

              <v-row class="mt-5">
                  <v-col cols="10" class="mx-auto">
                      <v-expansion-panels color="mt-1" v-if="isInit">
                          <v-expansion-panel>
                              <v-expansion-panel-title color="green-lighten-4">
                                  開発TODO
                              </v-expansion-panel-title>

                              <v-expansion-panel-text  v-for="(item, idx) in lists">
                                  <v-btn color="warning" width="80%" :loading="loading" @click="showTodo(item)">{{item.item}}</v-btn>                                            
                                  <v-btn color="green-lighten-1" width="20%" @click="taskDone(item)" :loading="loading">完了</v-btn>
                              </v-expansion-panel-text>
                          </v-expansion-panel>
                      </v-expansion-panels>
                  </v-col>
              </v-row>
          </div>
      </v-container>
  </v-main>
  <TaskDialog :disp="taskDialog.disp" :title="taskDialog.title" :detail="taskDialog.detail" :loading="loading" @done="taskDone($event)" @close="closeTodo"></TaskDialog>
  <Dialog :msg="dialog.msg" :disp="dialog.disp" @close="closeDialog"></Dialog>
</v-app>
</template>

<script setup lang="ts">
import {ref, onMounted} from "vue"
import axios from "axios"
import liff from "@line/liff"
import Dialog from "@/components/Dialog.vue"
import TaskDialog from "./TaskDialog.vue"
const title = ref("")
const todo = ref("")
const loading = ref(false)
const isInit = ref(false)
const lists = ref([]) 
const complete = ref(false)
const taskDialog = ref({
  title: "",
  detail: "",
  disp: false
})
const dialog = ref({
  msg: "",
  disp: false
})

const submit = () => {
  if(title.value === "" || todo.value === ""){
      openDialog("内容を入力");
      return ;
  }
  loading.value = true;

  let post_data = new FormData();
  post_data.append("item", title.value);
  post_data.append("detail", todo.value);
  axios.post("./save.php", post_data).then(res => {
      complete.value = true;
      getList();
  }).catch((er) => {
      openDialog(er);
  }).finally(() => {
      loading.value = false;
  });
}

const getList = () => {
  axios.get("./get.php").then(response => {
    if(response.data.result == 1){
      lists.value = response.data.lists;
    }
  }).catch(er => {
    openDialog(er.toString());
  }).finally(() => {
    loading.value = false;
  });
}
const showTodo = (item) => {
  taskDialog.value.title = item.item;
  taskDialog.value.detail = item.detail;
  taskDialog.value.disp = true;
}
const closeTodo = () => {
  taskDialog.value.title = "";
  taskDialog.value.detail = "";
  taskDialog.value.disp = false;
}
const taskDone = (item) => {
  let post_data = new FormData();
  post_data.append("id", item.id);
  loading.value = true;
  axios.post("./done.php", post_data).then(response => {
    if(response.data.result == 1){
      loading.value = false;
      getList();
      closeTodo();
    }
  }).catch(er => {

  })
}
const done = () => {
  liff.closeWindow();
}
const openDialog = (msg) => {
  dialog.value.msg = msg;
  dialog.value.disp = true;
  loading.value = false;
}
const closeDialog = () => {
  dialog.value.msg = "";
  dialog.value.disp = false;
}
onMounted(() => {
  axios.get("../../../util_api/liffId.php").then((res) => {
    liff.init({liffId: res.data.liffId}).then(() => {    
      isInit.value = true;
      getList();
    });
  });
});
</script>

<style>
.line-break{
    white-space: pre-wrap;
}
</style>