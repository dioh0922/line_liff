<template>
    <v-app>
        <v-app-bar  color="yellow-lighten-4">
            <v-col>
                <p class="text-center" justify="center">旅行メモ</p>
            </v-col>
        </v-app-bar>
        <v-main>
            <v-container>
                
                <v-row justify="center" v-if="!isInit">
                    <v-progress-circular indeterminate :size="79"></v-progress-circular>
                </v-row>
   
                <div v-else>

                    <v-row justify="center">
                        <v-text-field v-model="text" placeholder="目的地を入力"></v-text-field>
                    </v-row>

                    <v-row justify="center">
                        <v-btn :block=true :loading="loading" color="info" @click="submit">追加</v-btn>
                    </v-row>

                    <v-row class="mt-10"  justify="space-around">
                        <v-expansion-panels color="green-lighten-4" v-if="isInit">
                            <v-expansion-panel title="TODO">
                                <v-expansion-panel-text  v-for="(item, idx) in lists" border>
                                    <v-col class="d-flex">
                                        <v-sheet class="my-auto">
                                            {{item.destination}}
                                        </v-sheet>
                                        <v-sheet  class="my-auto ml-auto">
                                            <v-btn  color="red-lighten-5"  @click="submitDone(item.destination)">完了</v-btn>
                                        </v-sheet>
                                    </v-col>
                                </v-expansion-panel-text>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-row>
                </div>
            </v-container>
        </v-main>
    </v-app>
    <Dialog :msg="dialog.msg" :disp="dialog.disp" @close="closeDialog"></Dialog>

</template>

<script setup lang="ts">
import {ref, onMounted} from "vue"
import axios from "axios"
import liff from "@line/liff"
import Dialog from "@/components/Dialog.vue"
const text = ref("")
const lists = ref([])
const loading = ref(false)
const isInit = ref(false)
const dialog = ref({
  msg: "",
  disp:false
})
const fetchTodo = () => {
  axios.get("./get.php").then(res => {
    if(res.data.result == 1){
      loading.value = false;
      lists.value = res.data.lists;
    }
  }).catch(err => {
    loading.value = false;
  });
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
const submitDone = (e) => {
  loading.value = true;
  let post = new FormData();
  post.append("destination", e);
  axios.post("./done.php", post).then(res => {
    loading.value = false;
    fetchTodo();
  }).catch(err => {
    loading.value = false;
  }); 
}
const submit = () => {
  loading.value = true;
  let post = new FormData();
  post.append("destination", text.value);
  axios.post("./add.php", post).then(res => {
    if(res.data.result == 1){
      text.value = "";
      loading.value = false;
      fetchTodo();
      openDialog("行先を追加しました");
    }else{
      openDialog("追加に失敗しました")
    }
  }).catch(err => {
    loading.value = false;
  });
}

onMounted (() => {
  axios.get("../../../util_api/liffId.php").then((res) => {
    liff.init({liffId: res.data.liffId}).then(() => {
      isInit.value = true;
      fetchTodo();
    });
  });
})
</script>

<style>
</style>

