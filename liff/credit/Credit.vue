<template>
  <v-app>
    <v-app-bar flat color="green" text-align="center" app>
        <h3 class="mx-auto">使った内容と金額を入力</h3>
    </v-app-bar>
    <v-main>
        <v-container>
            <v-row justify="center" v-if="!isInit">
                <v-progress-circular indeterminate :size="79"></v-progress-circular>
            </v-row>
            <div v-else>
                <v-row>
                    <h3 class="mx-auto">内容</h3>
                </v-row>
                <v-row>
                    <v-col cols="10" class="mx-auto">
                        <v-text-field class="mx-auto" width="60%" v-model="detail"></v-text-field>
                    </v-col>
                </v-row>
                <v-row>
                    <h3 class="mx-auto">金額</h3>
                </v-row>
                <v-row>
                    <v-col cols="10" class="mx-auto">
                        <v-text-field class="mx-auto" type="number" min="1" width="60%" v-model="pay"></v-text-field>
                    </v-col>
                </v-row>

                <v-row v-show="!complete">
                    <v-btn class="mx-auto" width="80%" color="blue" @click="submit" :loading="loading">送信</v-btn>
                </v-row>

                <v-row v-show="complete">
                    <v-btn class="mx-auto mt-10" width="80%" color="grey" @click="done">トークに戻る</v-btn>
                </v-row>
            </div>
        </v-container>
    </v-main>
    <Dialog :msg="dialog.msg" :disp="dialog.disp" @close="closeDialog"></Dialog>
  </v-app>
</template>

<script setup lang="ts">
import {onMounted, ref} from "vue"
import axios from "axios"
import liff from "@line/liff"
import Dialog from "@/components/Dialog.vue"
const dialog = ref({
  msg: "",
  disp: false
})

const pay = ref(0)
const detail = ref("")
const loading = ref(false)
const isInit = ref(false)
const complete = ref(false)

const submit = () => {
  if(parseInt(pay.value) <= 0 || detail.value === ""){
      openDialog("内容を入力");
      return ;
  }
  loading.value = true;

  let post_data = new FormData();
  post_data.append("value", pay.value);
  post_data.append("detail", detail.value);
  axios.post("./save.php", post_data).then(res => {
      complete.value = true;
  }).catch((er) => {
      openDialog(er);
  }).finally(() => {
      loading.value = false;
  });
}
const done = () => {
  liff.closeWindow();
}

const openDialog = (msg: string) => {
  dialog.value.msg = msg;
  dialog.value.disp = true;
  loading.value = false;
}
const closeDialog = () => {
  dialog.value.msg = "";
  dialog.value.disp = false;
}
onMounted(()=>{
  axios.get("../../../util_api/liffId.php").then((res) => {
    liff.init({liffId: res.data.liffId}).then(() => {    
      isInit.value = true;
    });
  });
})

</script>

<style></style>