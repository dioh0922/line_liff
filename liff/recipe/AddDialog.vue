<template>
  <v-dialog :model-value="disp">
      <v-card>
        <v-text-field label="調味料" v-model="item" class="mx-auto mb-5" style="width: 50%" ></v-text-field>
        <v-select
          v-model="type"
          label="Select"
          :items="[
            '大さじ', 
            '小さじ',
            ]"
          class="mx-auto mb-5"
          style="width: 50%"
        ></v-select>
          <v-text-field label="分量" type="number" min="1" v-model="value" class="mx-auto mb-5" style="width: 50%" ></v-text-field>
            <v-btn color="green-lighten-1" @click="addRecipe" class="mx-auto mb-5" width="50%">追加</v-btn>
          <v-btn @click="closeTodo" class="mx-auto mb-5" width="50%" color="grey">閉じる</v-btn>
      </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import {defineProps, defineEmits, ref} from "vue"
const props = defineProps({
  disp:Boolean,
  title: String,
  detail: String,
  loading: Boolean
})
const item = ref("")
const type = ref("")
const value = ref("")
const addRecipe = () => {
  emits("add", {item: item.value, type: type.value, value: value.value})
}
const closeTodo = () => {
  emits("close")
}
const emits = defineEmits(["done", "close"])
</script>

<style>
.line-break{
    white-space: pre-wrap;
}
</style>

