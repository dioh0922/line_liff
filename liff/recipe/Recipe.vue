<template>

<v-app>
  <v-app-bar flat color="red-lighten-4" text-align="center" app>
      <h3 class="mx-auto">調味料変換</h3>
  </v-app-bar>
  <v-main>
      <v-container>
          <v-row justify="center" v-if="!isInit || loading">
              <v-progress-circular indeterminate :size="79"></v-progress-circular>
          </v-row>

          <div v-else>
              
            <v-row>
              <v-btn class="mx-auto mt-10" width="80%" @click="openAddDialog">追加</v-btn>
            </v-row>
            <v-row>
              <div class="d-flex justify-center mx-auto mt-10">
                <v-list>
                  <v-list-item v-for="item in list">
                    {{ item.item }}：{{ item.type }}{{ item.value }} {{ calc(item) }}
                  </v-list-item>
                </v-list>
              </div>
            </v-row>

            <v-row>
              <v-btn class="mx-auto mt-10" width="80%" color="blue" @click="callApi">変換</v-btn>
            </v-row>

            <v-row>
              <v-btn class="mx-auto mt-10" width="80%" color="grey" @click="done">トークに戻る</v-btn>
            </v-row>
          </div>
      </v-container>
  </v-main>
  <AddDialog :disp="addDisp" @add="add($event)" @close="closeAddDialog"></AddDialog>
  <Dialog :msg="dialog.msg" :disp="dialog.disp" @close="closeDialog"></Dialog>
</v-app>
</template>

<script setup lang="ts">
import {ref, onMounted, computed} from "vue"
import axios from "axios"
import liff from "@line/liff"
import Dialog from "@/components/Dialog.vue"
import AddDialog from "./AddDialog.vue"
const sprintf = require('sprintf-js').sprintf
const loading = ref(true)
const isInit = ref(false)
const complete = ref(false)
const addDisp = ref(false)
const dialog = ref({
  msg: "",
  disp: false
})
const list = ref([])
const target = ref(0)

const openAddDialog = () => {
  addDisp.value = true
}
const add = (e) => {
  list.value.push({...e, id: list.value.length + 1, translate: null})
  addDisp.value = false
}
const closeAddDialog = () => {
  addDisp.value = false
}

const calc = (obj) => {
  if(obj.translate != null){
    return "=" + obj.translate
  }else{
    return ""
  }
}

const done = () => {
  liff.closeWindow()
}
const closeDialog = () => {
  dialog.value.msg = ""
  dialog.value.disp = false
}

const callApi = () => {
  loading.value = true
  axios.post("./aiTranslate.php", list.value.map(item => {return {id: item.id, value:item.item + ':' + item.type + item.value}}))
  .then((res) => {
    loading.value = false
    res.data.forEach(el => {
      const target = list.value.find(item => item.id === el.id)
      target.translate = el.gram
    })
  })
}

onMounted(() => {
  axios.get("../../../util_api/liffId.php").then((res) => {
    liff.init({liffId: res.data.liffId}).then(() => {    
      isInit.value = true
      loading.value = false
    });
  });
});
</script>

<style>
.line-break{
    white-space: pre-wrap;
}
</style>