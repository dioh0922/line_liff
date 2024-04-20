<template>

<v-app>
  <v-app-bar flat color="red-lighten-4" text-align="center" app>
      <h3 class="mx-auto">外貨サブスク計算</h3>
  </v-app-bar>
  <v-main>
      <v-container>
          <v-row justify="center" v-if="!isInit">
              <v-progress-circular indeterminate :size="79"></v-progress-circular>
          </v-row>
          <div v-else>
              
              <v-row>
                <v-col>
                  値段<v-text-field type="number" class="outline" v-model="target" ></v-text-field>
                </v-col>
                <v-col>
                  レート(￥/＄)：{{rate}} 
                </v-col>
              </v-row>

              <v-row>
                <v-list>
                  <v-list-item
                    :title="calc(target)"
                  >
                  </v-list-item>

                  <v-list-item
                  v-for="item in list"
                  :title="calc(item)"
                  >
                  </v-list-item>
                </v-list>
              </v-row>
              
              <v-row>
                  <v-btn class="mx-auto mt-10" width="80%" color="grey" @click="done">トークに戻る</v-btn>
              </v-row>
          </div>
      </v-container>
  </v-main>
  <Dialog :msg="dialog.msg" :disp="dialog.disp" @close="closeDialog"></Dialog>
</v-app>
</template>

<script setup lang="ts">
import {ref, onMounted, computed} from "vue"
import axios from "axios"
import liff from "@line/liff"
import Dialog from "@/components/Dialog.vue"
const sprintf = require('sprintf-js').sprintf
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
const list = ref([5,10]);
const rate = ref(0);
const target = ref(0);

const calc = computed(() => (i) => {
  return  sprintf("＄%s：￥%s", i, i * rate.value);
});

const getRate = () => {
  axios.get('./ratePloxy.php').then(res => {
    rate.value = res.data;
  }).catch(er => {
    console.log(er);
  }).finally(() => {
    loading.value = false;
  });
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
      getRate();
    });
  });
});
</script>

<style>
.line-break{
    white-space: pre-wrap;
}
</style>