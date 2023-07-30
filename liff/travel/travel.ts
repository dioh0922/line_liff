
import {createApp} from "vue"
import {vuetify} from"../util/vuetify"
import Todo from "./Travel.vue"

(window.onload = () => {

  createApp(Todo).use(vuetify).mount('#container')

});

