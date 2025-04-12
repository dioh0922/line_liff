<template>
  <v-dialog :model-value="disp">
      <v-card>
          <v-card-text class="mx-auto line-break">
              <h4>{{title}}</h4>
              <p>{{detail}}</p>
          </v-card-text>
          <v-btn @click="closeTodo" class="mx-auto mb-5" width="50%" color="grey">閉じる</v-btn>
      </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import {defineProps, defineEmits} from "vue"
const props = defineProps({
  disp:Boolean,
  detail: String,
  title: String,
  loading: Boolean
})

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

