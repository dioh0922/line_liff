<template>
      <v-dialog :model-value="disp">
        <v-card>
            <v-card-text class="mx-auto">
                <h4>{{msg}}</h4>
            </v-card-text>
            <v-btn @click="closeDialog" class="mx-auto mb-5" width="50%" color="grey">閉じる</v-btn>
        </v-card>
        
    </v-dialog>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from "vue"
const props = defineProps({
  disp: Boolean,
  msg: String
})
const closeDialog = () => {
  emits("close")
}
const emits = defineEmits(["close"])
</script>

<style>
</style>